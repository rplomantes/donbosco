<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingController extends Controller
{

    
    public function __construct()
	{
		$this->middleware('auth');
	}
//
    
    function view($idno){
       if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')){
       $student = \App\User::where('idno',$idno)->first();
       $status = \App\Status::where('idno',$idno)->first();  
       $reservation = 0;
       $totalprevious = 0;
       $ledgers = null;
       $dues = null;
       $penalty = 0;
       $totalmain = 0;
      
       //Get other added collection
       $matchfields = ["idno"=>$idno, "categoryswitch"=>env("OTHER_FEE")];
       $othercollections = \App\Ledger::where($matchfields)->get();
       //get previous balance
       $previousbalances = DB::Select("select schoolyear, sum(amount)- sum(plandiscount)- sum(otherdiscount)
               - sum(debitmemo) - sum(payment) as amount from ledgers where idno = '$idno' 
               and categoryswitch >= '"  .env('PREVIOUS_MISCELLANEOUS_FEE') ."' group by schoolyear");
       if(count($previousbalances)>0){ 
       foreach($previousbalances as $prev){
            $totalprevious = $totalprevious + $prev->amount;
       }}
       //get reservation
       if(isset($status->status)){
           //if($status->status == "1"){
           $reservations = DB::Select("select amount as amount from advance_payments where idno = '$idno' and status = '0'");
           if(count($reservations)>0){
               foreach($reservations as $reserve)
               {
                   $reservation = $reservation + $reserve->amount;
               }
       }}
           
       //get current account
          if(isset($status->department)){
                $currentperiod = \App\ctrSchoolYear::where('department',$status->department)->first();  
                $ledgers = DB::Select("select sum(amount) as amount, sum(plandiscount) as plandiscount, sum(otherdiscount) as otherdiscount,
                sum(payment) as payment, sum(debitmemo) as debitmemo, receipt_details, schoolyear, period, categoryswitch from ledgers where
                idno = '$idno' and categoryswitch <= '10' group by receipt_details, schoolyear, period, categoryswitch order by categoryswitch");
               
                if(count($ledgers)>0){
                    foreach($ledgers as $ledger){
                        if($ledger->categoryswitch <= '6'){
                        $totalmain=$totalmain+$ledger->amount - $ledger->plandiscount -$ledger->otherdiscount - $ledger->debitmemo - $ledger->payment;
                        }
                        
                        }
                }
                
                $dues = DB::Select("select sum(amount) - sum(plandiscount) - sum(otherdiscount)
                - sum(payment)- sum(debitmemo) as balance, sum(plandiscount) + sum(otherdiscount) as discount, duedate, duetype from ledgers where
                idno = '$idno' and categoryswitch <= '6' group by duedate, duetype");
          
                /*foreach($dues as $due){
                if(((strtotime(date('Y-m-d'))/(60*60*24)) - strtotime($due->duedate)/(60*60*24)) > 1){
                   $penalty = $penalty + ($due->balance * 0.05);
                }
               }
       
           if($penalty > 0 && $penalty < 250){
               $penalty = 250;
           }*/
                
                $matchfields=["idno"=>$idno,"categoryswitch"=>env('PENALTY_CHARGE')];
                $penalties = \App\Ledger::where($matchfields)->get();
                if(count($penalties)>0){
                    foreach($penalties as $pen){
                        $penalty= $penalty + $pen->amount - $pen->debitmemo - $pen->otherdiscount - $pen->payment;
                    }
                }
           
        } 
           
           //history of payments
           $debits = DB::SELECT("select * from dedits where idno = '" . $idno . "' and "
                   . "paymenttype <= '2' order by transactiondate");
           //debit description
           $debitdms = DB::SELECT("select * from dedits where idno = '" . $idno . "' and "
                   . "paymenttype = '3' order by transactiondate");
           
          $debitdescriptions = DB::Select("select * from ctr_debitaccounts");
           return view('accounting.studentledger',  compact('debitdms','debits','penalty','totalmain','totalprevious','previousbalances','othercollections','student','status','ledgers','reservation','dues','debitdescriptions'));

       }   
       
   }
   
   function debitcredit(Request $request){
       $account=null;
       $discount = 0;
       $discount1= 0;
       $other = 0;
       $refno = $this->getRefno();
       
       if($request->totaldue > '0'){
           $totaldue = $request->totaldue;
           if($request->reservation > 0 ){
               $totaldue=$totaldue + $request->reservation;
           }
                $accounts = DB::SELECT("select * from ledgers where idno = '".$request->idno."' and categoryswitch <= '6' "
                     . " and (amount - payment - debitmemo - plandiscount - otherdiscount) > 0 order By duedate, categorySwitch");    
                    foreach($accounts as $account){
                        $balance = $account->amount - $account->payment - $account->plandiscount - $account->otherdiscount - $account->debitmemo;
                        if($balance < $totaldue){
                            $discount = $discount + $account->plandiscount + $account->otherdiscount;
                            $updatepay = \App\Ledger::where('id',$account->id)->first();
                            $updatepay->debitmemo = $updatepay->debitmemo + $balance;
                            $updatepay->save();
                            $totaldue = $totaldue - $balance;
                            $credit = new  \App\Credit;
                            $credit->idno = $request->idno;
                            $credit->transactiondate = Carbon::now();
                            $credit->referenceid = $account->id;
                            $credit->refno = $refno;
                            $credit->categoryswitch = $account->categoryswitch;
                            $credit->acctcode = $account->acctcode;
                            $credit->description = $account->description;
                            $credit->receipt_details = $account->receipt_details;
                            $credit->duedate=$account->duedate;
                            $credit->amount=$account->amount-$account->payment-$account->debitmemo;
                            $credit->schoolyear=$account->schoolyear;
                            $credit->period=$account->period;
                            $credit->postedby=\Auth::user()->idno;
                            $credit->save();
                            //$this->credit($request->idno, $account->id, $refno, $account->amount-$account->payment-$account->debitmemo);
                            } else {
                                
                            $updatepay = \App\Ledger::where('id',$account->id)->first();
                            $updatepay->debitmemo = $updatepay->debitmemo + $totaldue;
                            $updatepay->save();
                            $credit = new \App\Credit;
                            $credit->idno = $request->idno;
                            $credit->transactiondate = Carbon::now();
                            $credit->referenceid = $account->id;
                            $credit->refno = $refno;
                            $credit->categoryswitch = $account->categoryswitch;
                            $credit->acctcode = $account->acctcode;
                            $credit->description = $account->description;
                            $credit->receipt_details = $account->receipt_details;
                            $credit->duedate=$account->duedate;
                            if($balance == $totaldue){
                            $discount = $discount + $account->plandiscount + $account->otherdiscount;
                            $credit->amount=$account->amount-$account->payment-$account->debitmemo;
                                } else {
                            $credit->amount=$totaldue;
                                }       
                            $credit->schoolyear=$account->schoolyear;
                            $credit->period=$account->period;
                            $credit->postedby=\Auth::user()->idno;
                            $credit->save();
                            
                            //$this->credit($request->idno, $account->id, $refno, $totaldue + $account->plandiscount + $account->otherdiscount);
                            $totaldue = 0;
                            break;
                          }
                    }
                $this->changestatatus($request->idno, $request->reservation);
                if($request->reservation > 0){
                $this->debit_reservation_discount($request->idno,env('DEBIT_RESERVATION') , $request->reservation);
                $this->consumereservation($request->idno);
                }
                
       } 
       
             if($request->previous > 0 ){
             $previous = $request->previous;
             $updateprevious = DB::SELECT("select * from ledgers where idno = '".$request->idno."' and categoryswitch >= '11' "
                     . " and amount - payment - debitmemo - plandiscount - otherdiscount > 0 order By categoryswitch");
             foreach($updateprevious as $up){
                 $balance = $up->amount - $up->payment - $up->plandiscount - $up->otherdiscount - $up->debitmemo;
                 if($balance < $previous ){
                     $discount1 = $discount1 + $up->plandiscount + $up->otherdiscount;
                     $updatepay = \App\Ledger::where('id',$up->id)->first();
                     $updatepay->debitmemo = $updatepay->debitmemo + $balance;
                     $updatepay->save();
                     $previous = $previous - $balance;
                     $credit = new  \App\Credit;
                     $credit->idno = $request->idno;
                     $credit->transactiondate = Carbon::now();
                     $credit->referenceid = $up->id;
                     $credit->refno = $refno;
                     $credit->categoryswitch = $up->categoryswitch;
                     $credit->acctcode = $up->acctcode;
                     $credit->description = $up->description;
                     $credit->receipt_details = $up->receipt_details;
                     $credit->duedate=$up->duedate;
                     $credit->amount=$up->amount-$up->payment-$up->debitmemo;
                     $credit->schoolyear=$up->schoolyear;
                     $credit->period=$up->period;
                     $credit->postedby=\Auth::user()->idno;
                     $credit->save();
                     
                    // $this->credit($request->idno, $up->id, $refno,  $up->amount - $up->payment - $up->debitmemo);
                 } else {
                            $updatepay = \App\Ledger::where('id',$up->id)->first();
                            $updatepay->debitmemo = $updatepay->debitmemo + $previous;
                            $updatepay->save();
                            $credit = new \App\Credit;
                            $credit->idno = $request->idno;
                            $credit->transactiondate = Carbon::now();
                            $credit->referenceid = $up->id;
                            $credit->refno = $refno;
                            $credit->categoryswitch = $up->categoryswitch;
                            $credit->acctcode = $up->acctcode;
                            $credit->description = $up->description;
                            $credit->receipt_details = $up->receipt_details;
                            $credit->duedate=$up->duedate;
                            if($balance == $previous){
                            $discount = $discount + $up->plandiscount + $up->otherdiscount;
                            $credit->amount=$up->amount-$up->payment-$up->debitmemo;
                                } else {
                            $credit->amount=$previous;
                                }       
                            $credit->schoolyear=$up->schoolyear;
                            $credit->period=$up->period;
                            $credit->postedby=\Auth::user()->idno;
                            $credit->save();
                            //$this->credit($request->idno, $up->id, $refno, $previous);
                            $previous = 0;
                            break;
                       }
                 
                 
             }   
            }
            
            if(isset($request->other)){
                foreach($request->other as $key=>$value){
                    $other = $other + $value;
                    $updateother = \App\Ledger::find($key);
                    $updateother->debitmemo = $updateother->debitmemo + $value;
                    $updateother->save();
                    //$this->credit($updateother->idno, $updateother->id, $refno, $orno, $value);
                    $ledger = \App\Ledger::find($key);
                    $newcredit = new \App\Credit;
                    $newcredit->idno=$request->idno;
                    $newcredit->transactiondate = Carbon::now();
                    $newcredit->referenceid = $updateother->id;
                    $newcredit->refno = $refno;
                    $newcredit->categoryswitch = $ledger->categoryswitch;
                    $newcredit->acctcode = $ledger->acctcode;
                    $newcredit->description = $ledger->description;
                    $newcredit->receipt_details = $ledger->receipt_details;
                    $newcredit->duedate=$ledger->duedate;
                    $newcredit->amount=$value;
                    $newcredit->schoolyear=$ledger->schoolyear;
                    $newcredit->period=$ledger->period;
                    $newcredit->postedby=\Auth::user()->idno;
                    $newcredit->save();    
                    
                }
            }
            
              if($request->penalty > 0){
           $penalty = $request->penalty;
            $updatepenalties = DB::SELECT("select * from ledgers where idno = '".$request->idno."' and categoryswitch = '". env('PENALTY_CHARGE'). "' "
                     . " and amount - payment - debitmemo - plandiscount - otherdiscount > 0");
           foreach($updatepenalties as $pen){
               $balance = $pen->amount - $pen->payment = $pen->plandiscount - $pen-> otherdiscount - $pen->debitmemo;
               
               if($balance < $penalty ){
                     $updatepay = \App\Ledger::where('id',$pen->id)->first();
                     $updatepay->debitmemo = $updatepay->debitmemo + $balance;
                     $updatepay->save();
                     $penalty = $penalty - $balance;
                     $credit = new  \App\Credit;
                            $credit->idno = $request->idno;
                            $credit->transactiondate = Carbon::now();
                            $credit->referenceid = $pen->id;
                            $credit->refno = $refno;
                            $credit->categoryswitch = $pen->categoryswitch;
                            $credit->acctcode = $pen->acctcode;
                            $credit->description = $pen->description;
                            $credit->duedate=$pen->duedate;
                            $credit->receipt_details = $pen->receipt_details;
                            $credit->amount=$pen->amount-$pen->payment-$pen->debitmemo;
                            $credit->schoolyear=$pen->schoolyear;
                            $credit->period=$pen->period;
                            $credit->postedby=\Auth::user()->idno;
                     
                     
                     
                     //$this->credit($request->idno, $pen->id, $refno, $orno, $pen->amount);
                 } else {
                            $updatepay = \App\Ledger::where('id',$pen->id)->first();
                            $updatepay->debitmemo = $updatepay->debitmemo + $penalty;
                            $updatepay->save();
                             $credit = new \App\Credit;
                            $credit->idno = $request->idno;
                            $credit->transactiondate = Carbon::now();
                            $credit->referenceid = $pen->id;
                            $credit->refno = $refno;
                            $credit->categoryswitch = $pen->categoryswitch;
                            $credit->acctcode = $pen->acctcode;
                            $credit->description = $pen->description;
                            $credit->receipt_details = $pen->receipt_details;
                            $credit->duedate=$pen->duedate;
                            
                            $credit->amount=$totaldue;
                                   
                            $credit->schoolyear=$pen->schoolyear;
                            $credit->period=$pen->period;
                            $credit->postedby=\Auth::user()->idno;
                            $credit->save();
                      //      $this->credit($request->idno, $pen->id, $refno, $orno, $penalty);
                            $penalty = 0;
                            break;
                       }
           }
            
           
          
           
       }
        if($discount > 0){
        $student = \App\User::where('idno',$request->idno)->first();
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $request->idno;
        $debitaccount->transactiondate=Carbon::now();
        $debitaccount->refno=$this->getRefno();
        $debitaccount->paymenttype = '4';
        $debitaccount->acctcode="Discount";
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->amount = $discount;    
        $debitaccount->postedby=\Auth::user()->idno;
        $debitaccount->save();
        }
        $student= \App\User::where('idno', $request->idno)->first();
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $request->idno;
        $debitaccount->transactiondate=Carbon::now();
        $debitaccount->refno=$this->getRefno();
        $debitaccount->receiptno = $this->getRefno();
        $debitaccount->paymenttype = "3";
        $debitaccount->acctcode=$request->debitdescription;
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->amount = $request->totaldue + $request->penalty + $request->previous + $other;    
        $debitaccount->postedby=\Auth::user()->idno;
        $debitaccount->save();
        
        $this->reset_or(); 
          
                return redirect(url('/viewdm',array($refno,$request->idno)));    
    //   return $request;
   }
    
   function reset_or(){
        $resetor = \App\User::where('idno', \Auth::user()->idno)->first();
        $resetor->reference_number = $resetor->reference_number + 1;
        $resetor->save();
    }
    
     function getRefno(){
         $newref= \Auth::user()->id . \Auth::user()->reference_number;
         return $newref;
    }
    
   
   function changestatatus($idno, $reservation){
   $status = \App\Status::where('idno',$idno)->first();    
       if(count($status)> 0 ){
           if($status->status == "1"){
               if($reservation == "0"){
               $this->addreservation($idno);
               }
               $status->status='2';
               $status->date_enrolled=Carbon::now();
               $status->save();
           }
       }
   }
    
   function debit_reservation_discount($idno,$debittype,$amount){
        $student = \App\User::where('idno',$idno)->first();
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $idno;
        $debitaccount->transactiondate=Carbon::now();
        $debitaccount->refno=$this->getRefno();
        $debitaccount->receiptno = $this->getRefno();
        $debitaccount->acctcode = "Reservation";
        $debitaccount->paymenttype = $debittype;
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->amount = $amount;    
        $debitaccount->postedby=\Auth::user()->idno;
        $debitaccount->save();
        
    }
    
     function consumereservation($idno){
        $crs= \App\AdvancePayment::where('idno',$idno)->get();
        foreach($crs as $cr){
            $cr->status = "1";
            $cr->postedby = \Auth::user()->idno;
            $cr->save();
        }
    }
    
    function addreservation($idno){
      $status=  \App\Status::where('idno',$idno)->first();
      $addcredit = new \App\Credit;
      $addcredit->idno = $idno;
      $addcredit->transactiondate = Carbon::now();
      $addcredit->refno = $this->getRefno();
      $addcredit->receiptno = $this->getRefno();
      $addcredit->categoryswitch = "9";
      $addcredit->acctcode = "Reservation";
      $addcredit->description = "Reservation";
      $addcredit->receipt_details = "Reservation";
      $addcredit->amount = "1000.00";
      if(isset($status->schoolyear)){
      $addcredit->schoolyear=$status->schoolyear;
      }
      $addcredit->postedby=\Auth::user()->idno;
      $addcredit->save();
      
      $adddebit = new \App\Dedit;
      $adddebit->idno = $idno;
      $adddebit->transactiondate = Carbon::now();
      $adddebit->paymenttype = '5';
      $adddebit->amount = "1000.00";
      $adddebit->acctcode="Reservation";
      $adddebit->refno = $this->getRefno();
      $adddebit->receiptno = $this->getRefno();
      $adddebit->postedby = \Auth::user()->idno;
      if(isset($status->schoolyear)){
      $adddebit->schoolyear = $status->schoolyear;
      }
      $adddebit->save();
      
      $addreservation = new \App\AdvancePayment;
      $addreservation->idno = $idno;
      $addreservation->amount = "1000.00";
      $addreservation->refno = $this->getRefno();
      $addreservation->transactiondate=Carbon::now();
      $addreservation->postedby=\Auth::user()->idno;
      $addreservation->status = "1";
      $addreservation->save();
      
  }
  
  function viewdm($refno, $idno){
       $student = \App\User::where('idno',$idno)->first();
       $status= \App\Status::where('idno',$idno)->first();
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->first();
       $debit_dm = \App\Dedit::where('refno',$refno)->where('paymenttype','3')->first();
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate");
       $tdate = \App\Dedit::where('refno',$refno)->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       return view("accounting.viewdm",compact('posted','tdate','student','debits','credits','status','debit_discount','debit_dm'));
       
  }
  
  function printdmcm($refno, $idno){
       $student = \App\User::where('idno',$idno)->first();
       $status= \App\Status::where('idno',$idno)->first();
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->first();
       $debit_dm = \App\Dedit::where('refno',$refno)->where('paymenttype','3')->first();
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate");
       $tdate = \App\Dedit::where('refno',$refno)->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       $pdf = \App::make('dompdf.wrapper');
      // $pdf->setPaper([0, 0, 336, 440], 'portrait');
       $pdf->loadView("print.printdmcm",compact('posted','tdate','student','debits','credits','status','debit_discount','debit_reservation','debit_cash','debit_dm'));
       return $pdf->stream();
  
}
function dmcmallreport($transactiondate){
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse,  dedits.refno, dedits.acctcode from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '3' group by users.idno, dedits.transactiondate, users.lastname, users.firstname, dedits.isreverse,dedits.refno, dedits.acctcode order by dedits.refno" );

              return view('accounting.dmcmreport', compact('collections','transactiondate'));
}

function dmcmreport($transactiondate){
$matchfields = ['postedby'=>\Auth::user()->idno, 'transactiondate'=>$transactiondate];
//$matchfields = ['transactiondate'=>$transactiondate];

    //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse,  dedits.refno, dedits.acctcode from users, dedits where users.idno = dedits.idno and"
                . " dedits.postedby = '".\Auth::user()->idno."' and dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '3' group by users.idno, dedits.transactiondate, users.lastname, users.firstname, dedits.isreverse,dedits.refno, dedits.acctcode order by dedits.refno" );
        //$collections = \App\User::where('postedby',\Auth::user()->idno)->first()->dedits->where('transactiondate',date('Y-m-d'))->get();

        return view('accounting.dmcmreport', compact('collections','transactiondate'));
}

function collectionreport($datefrom, $dateto){
    $credits = DB::Select("select sum(amount) as amount, acctcode from credits where isreverse = '0' and transactiondate between '" . $datefrom . "' and '" . $dateto ."' "
            . " group by acctcode");
    
    $debits = DB::Select("select sum(amount) as amount, acctcode from dedits where isreverse = '0' and transactiondate between '" . $datefrom . "' and '".$dateto. "' "
            . " group by acctcode");
    return view('accounting.collectionreport',compact('credits', 'debits','datefrom','dateto'));
}

 function printdmcmreport($idno,$transactiondate){
        
         $matchfields = ['postedby'=>$idno, 'transactiondate'=>$transactiondate];
        //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno from users, dedits where users.idno = dedits.idno and"
                . " dedits.postedby = '".\Auth::user()->idno."' and dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '3' group by users.idno, dedits.transactiondate, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno" );
        
        $teller=\Auth::user()->firstname." ". \Auth::user()->lastname;
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView("print.printdmcmreport",compact('collections','transactiondate','teller'));
        return $pdf->stream();  
    }

 function summarymain($schoolyer){ 
     return view('accounting.showsummarymain',compact('schoolyear'));
     
 }   
 function maincollection($fromtran,$totran){
     $credits = DB::Select("select sum(amount) as amount,acctcode from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' group by acctcode");
      $debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' group by acctcode, depositto");
     //$debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and (paymenttype = '1' or paymenttype = '2') group by depositto");
     //$debitdebitmemos = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode from dedits where (transactiondate between '$fromtran' and '$totran') and paymenttype = '3' and isreverse = '0' group by acctcode");
     //$debitdiscounts = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '4'");
     //$debitreservations = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '5'");
   
    return view('accounting.maincollection',compact('credits','debitcashchecks','fromtran','totran')); 
 }
 
  function printmaincollection($fromtran,$totran){
     $credits = DB::Select("select sum(amount) as amount,acctcode from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' group by acctcode");
      $debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' group by acctcode, depositto");
     //$debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and (paymenttype = '1' or paymenttype = '2') group by depositto");
     //$debitdebitmemos = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode from dedits where (transactiondate between '$fromtran' and '$totran') and paymenttype = '3' and isreverse = '0' group by acctcode");
     //$debitdiscounts = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '4'");
     //$debitreservations = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '5'");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('print.printcashentry',compact('credits','debitcashchecks','fromtran','totran')); 
        return $pdf->stream();  

 }
 
 function studentledger($level){
     
     if($level == 'all'){
         $ledgers = DB::Select("select u.idno, u.lastname, u.firstname, u.middlename, sum(l.amount) as amount, sum(l.payment) as payment, sum(l.debitmemo) as debitmemo, "
                 . "sum(l.plandiscount) as plandiscount,sum(l.otherdiscount) as otherdiscount, s.level, s.strand, s.course from users u, ledgers l, statuses s where u.idno = l.idno and u.idno = "
                 . "s.idno and s.status='2' group by u.idno, u.lastname, u.firstname, u.middlename, s.level, s.strand, s.course order by s.level, u.lastname, u.firstname");
     
         
     } else {
          $ledgers = DB::Select("select u.idno, u.lastname, u.firstname, u.middlename, sum(l.amount) as amount, sum(l.payment) as payment, sum(l.debitmemo) as debitmemo, "
                 . "sum(l.plandiscount) as plandiscount,sum(l.otherdiscount) as otherdiscount, s.level, s.strand, s.course from users u, ledgers l, statuses s where u.idno = l.idno and u.idno = "
                 . "s.idno and s.status='2' and s.level = '$level' group by u.idno, u.lastname, u.firstname, u.middlename, s.level, s.strand, s.course order by s.level, u.lastname, u.firstname");
     
        
     }
     return view('accounting.studentgenledger',compact('ledgers'));
 }

function cashcollection($transactiondate){
 
    $computedreceipts = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount, postedby, transactiondate, depositto  from dedits where "
            . "transactiondate = '" . $transactiondate . "' and isreverse = '0' and paymenttype = '1' group by transactiondate, postedby, depositto order by postedby");
    
//    $actualcashs = DB::Select("select * from actual_deposits where transactiondate = '$transactiondate' order by postedby");
    $encashments = DB::Select("select sum(amount) as amount, whattype, postedby from encashments  "
            . " where transactiondate = '$transactiondate' group by whattype, postedby");
       
    
    $actualcbc =  \App\DepositSlip::where('transactiondate',$transactiondate)->where('bank',"China Bank")->get();
    $actualbpi1 = \App\DepositSlip::where('transactiondate', $transactiondate)->where('bank',"BPI 1")->get();
    $actualbpi2 = \App\DepositSlip::where('transactiondate',$transactiondate)->where('bank',"BPI 2")->get();
    
    return view('accounting.cashcollection', compact('computedreceipts','transactiondate','actualcbc','actualbpi1','actualbpi2', 'encashments'));

    }
  
 function printactualoverall($transactiondate){
     $computedreceipts = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount, postedby, transactiondate, depositto  from dedits where "
            . "transactiondate = '" . $transactiondate . "' and isreverse = '0' and paymenttype = '1' group by transactiondate, postedby, depositto order by postedby");
    
//    $actualcashs = DB::Select("select * from actual_deposits where transactiondate = '$transactiondate' order by postedby");
    $encashments = DB::Select("select sum(amount) as amount, whattype, postedby from encashments  "
            . " where transactiondate = '$transactiondate' group by whattype, postedby");
       
    
    $actualcbc =  \App\DepositSlip::where('transactiondate',$transactiondate)->where('bank',"China Bank")->get();
    $actualbpi1 = \App\DepositSlip::where('transactiondate', $transactiondate)->where('bank',"BPI 1")->get();
    $actualbpi2 = \App\DepositSlip::where('transactiondate',$transactiondate)->where('bank',"BPI 2")->get();
    
    
    
 $pdf = \App::make('dompdf.wrapper');
      // $pdf->setPaper([0, 0, 336, 440], 'portrait');
       $pdf->loadView('accounting.printactualoverall', compact('computedreceipts','transactiondate','actualcbc','actualbpi1','actualbpi2', 'encashments'));
       return $pdf->stream();
       
 }   
  

function overallcollection($transactiondate){
  $matchfields = ['transactiondate'=>$transactiondate];
        //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby,non_students.fullname from dedits left join users on users.idno = dedits.idno left join non_students on non_students.idno = dedits.idno where"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
        
    return view('accounting.overallcollection',compact('collections','transactiondate'));
    
} 

function cashreceipts($transactiondate){
    $rangedate = date("Y-m",strtotime($transactiondate));
    $asOf = date("l, F d, Y",strtotime($transactiondate));
    $wilddate = $rangedate."-%";
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and "
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
      
     $otheraccounts = DB::Select("select sum(credits.amount) as amount, credits.receipt_details, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits, credits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and credits.refno=dedits.refno and credits.categoryswitch >= '7'   and credits.receipt_details != 'Reservation' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, "
                . " users.firstname, credits.receipt_details, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
    
     $othersummaries = DB::Select("select sum(credits.amount) as amount, credits.acctcode, "
                . " dedits.transactiondate from users, dedits, credits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and credits.refno=dedits.refno and credits.categoryswitch >= '7'  and credits.acctcode != 'Reservation' and dedits.paymenttype = '1' and dedits.isreverse = '0' group by  dedits.transactiondate, "
                . " credits.acctcode order by credits.acctcode" );
     
   //FORWARDED BALANCE  
   $totalcashdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '1' and isreverse = '0'" );
   
   $totalcash=0.00;
   
   foreach($totalcashdb as $tcd){
       $totalcash = $totalcash + $tcd->amount + $tcd->checkamount;
   }
  
   $totaldiscountdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totaldiscountdb)>0){
    $totaldiscount = $totaldiscountdb[0]->amount + $totaldiscountdb[0]->checkamount;
   }else{
   $totaldiscount = 0;
   }
   
   $drreservationdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '5' and isreverse = '0'" );
   if(count($drreservationdb)>0){
        $drreservation = $drreservationdb[0]->amount + $drreservationdb[0]->checkamount;
    }else {
       $drreservation=0;
    }
    
   $totalfapedb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totalfapedb)>0){
    $totalfape = $totalfapedb[0]->amount;
   }else{
   $totalfape = 0;
   }
    
   $elearningcr = $this->getcrmonthmain(1, $wilddate, $transactiondate);
   $misccr = $this->getcrmonthmain(2, $wilddate, $transactiondate);
   $bookcr = $this->getcrmonthmain(3, $wilddate, $transactiondate);
   $departmentcr = $this->getcrmonthmain(4, $wilddate, $transactiondate);
   $registrationcr =$this->getcrmonthmain(5, $wilddate, $transactiondate);
   $tuitioncr = $this->getcrmonthmain(6, $wilddate, $transactiondate);
   $crreservationdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch = '9' and acctcode ='Reservation'");
   if(count($crreservationdb)>0){
       $crreservation = $crreservationdb[0]->amount;
   } else {
       $crreservation = 0;
   }
   
   
   $crothersdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch >= '7' and acctcode !='Reservation' and isreverse = '0'");
   if(count($crothersdb)>0){
       $crothers = $crothersdb[0]->amount;
   } else {
       $crothers = 0;
   }
   //END FORWARD BALANCE
   
   
   $forward = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and dedits.transactiondate < '$transactiondate'" );
   $forwardbal = $forward[0]->amount+$forward[0]->checkamount;
     
    $allcollections = array();
    $int=0;
    
    
foreach ($collections as $collection){
    $allcollections[$int] = array(
        $collection->receiptno,
        $collection->lastname." ,".$collection->firstname,
        $collection->amount+$collection->checkamount, 
        $this->getReservationDebit($collection->refno),
        $this->getcreditamount($collection->refno,1),
        $this->getcreditamount($collection->refno,2),
        $this->getcreditamount($collection->refno,3),
        $this->getcreditamount($collection->refno,4),
        $this->getcreditamount($collection->refno,5),
        $this->getcreditamount($collection->refno,6),
        $this->getReservationCredit($collection->refno),
        $this->getcreditamount1($collection->refno,7),
        $collection->isreverse,
        $this->getDiscount($collection->refno),
        $this->getFapeDebit($collection->refno)
            
        );
    
    $int=$int+1;
}
    
    //return $othersummaries;
    return view('accounting.cashreceiptdetails',compact('elearningcr','misccr','bookcr','departmentcr','registrationcr','tuitioncr','crreservation','crothers','totalcash','totaldiscount','drreservation','allcollections','transactiondate','otheraccounts','othersummaries','forwardbal','asOf','totalfape'));
    //return $forwardbal;
}

function printcashreceipts($transactiondate){
    $rangedate = date("Y-m",strtotime($transactiondate));
    $asOf = date("l, F d, Y",strtotime($transactiondate));
    $wilddate = $rangedate."-%";
    $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
  
    
    //FORWARD BALANCE
   $totalcashdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '1' and isreverse = '0'" );
   
   $totalcash=0.00;
   
   foreach($totalcashdb as $tcd){
       $totalcash = $totalcash + $tcd->amount + $tcd->checkamount;
   }
  
   $totaldiscountdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totaldiscountdb)>0){
    $totaldiscount = $totaldiscountdb[0]->amount + $totaldiscountdb[0]->checkamount;
   }else{
   $totaldiscount = 0;
   }
   
   $drreservationdb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '5' and isreverse = '0'" );
   if(count($drreservationdb)>0){
        $drreservation = $drreservationdb[0]->amount + $drreservationdb[0]->checkamount;
    }else {
       $drreservation=0;
    }
    
   $totalfapedb = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . "from dedits where "
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and transactiondate < '$transactiondate' and paymenttype = '4' and isreverse = '0'" );
   if(count($totalfapedb)>0){
    $totalfape = $totalfapedb[0]->amount;
   }else{
   $totalfape = 0;
   }
    
   $elearningcr = $this->getcrmonthmain(1, $wilddate, $transactiondate);
   $misccr = $this->getcrmonthmain(2, $wilddate, $transactiondate);
   $bookcr = $this->getcrmonthmain(3, $wilddate, $transactiondate);
   $departmentcr = $this->getcrmonthmain(4, $wilddate, $transactiondate);
   $registrationcr =$this->getcrmonthmain(5, $wilddate, $transactiondate);
   $tuitioncr = $this->getcrmonthmain(6, $wilddate, $transactiondate);
   $crreservationdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch = '9' and acctcode ='Reservation'");
   if(count($crreservationdb)>0){
       $crreservation = $crreservationdb[0]->amount;
   } else {
       $crreservation = 0;
   }
   
   
   $crothersdb = DB::Select("Select sum(amount) as amount from credits where transactiondate like "
           . "'$wilddate' and transactiondate < '$transactiondate' and categoryswitch >= '7' and acctcode !='Reservation' and isreverse = '0'");
   if(count($crothersdb)>0){
       $crothers = $crothersdb[0]->amount;
   } else {
       $crothers = 0;
   }
   
   //END FORWARD BALANCE
   
   
   $forward = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby from users, dedits where users.idno = dedits.idno and"
                . " dedits.transactiondate LIKE '" 
                . $wilddate. "' and dedits.transactiondate < '$transactiondate'" );
   $forwardbal = $forward[0]->amount+$forward[0]->checkamount;
     
    $allcollections = array();
    $int=0;
    
    
foreach ($collections as $collection){
    $allcollections[$int] = array(
        $collection->receiptno,
        $collection->lastname." ,".$collection->firstname,
        $collection->amount+$collection->checkamount, 
        $this->getReservationDebit($collection->refno),
        $this->getcreditamount($collection->refno,1),
        $this->getcreditamount($collection->refno,2),
        $this->getcreditamount($collection->refno,3),
        $this->getcreditamount($collection->refno,4),
        $this->getcreditamount($collection->refno,5),
        $this->getcreditamount($collection->refno,6),
        $this->getReservationCredit($collection->refno),
        $this->getcreditamount1($collection->refno,7),
        $collection->isreverse,
        $this->getDiscount($collection->refno),
        $this->getFapeDebit($collection->refno)
        );
    $int=$int+1;
}
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper('legal','landscape');
       $pdf->loadView('print.printcashreceipt',compact('elearningcr','misccr','bookcr','departmentcr','registrationcr','tuitioncr','crreservation','crothers','totalcash','totaldiscount','drreservation','allcollections','transactiondate','otheraccounts','othersummaries','forwardbal','asOf','totalfape'));
       return $pdf->stream();
    //return $forwardbal;
}

    function getDiscount($refno){
        $discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->first();
        $mt=0;
        if(count($discount)>0){
          $mt=$discount->amount;  
        }
        return $mt;
    }
    
    function getcrmonthmain($cswitch, $monthdate, $trandate){
        $total = DB::Select("Select sum(Amount) as amount from credits where categoryswitch = '$cswitch' and "
                . "isreverse = '0' and transactiondate like '$monthdate' and transactiondate < '$trandate'");
        if(count($total)> 0){
            $credit = $total[0]->amount;
        } else {
            $credit=0;
        }
        return $credit;
    }

    function getReservationCredit($refno){
        $mt=0;
        $amount=  \App\Credit::where('refno',$refno)->where('acctcode','Reservation')->first();
        if(count($amount)>0){
            $mt = $amount->amount;
        }
        return $mt;
    }
    function getReservationDebit($refno){
        $mt=0;
        $amount = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
        if(count($amount)>0){
            $mt = $amount->amount;
        }
        return $mt;
        }
    function getFapeDebit($refno){
        $mt=0;
        $amount = \App\Dedit::where('refno',$refno)->where('paymenttype','7')->first();
        if(count($amount)>0){
            $mt = $amount->amount;
        }
        return $mt;
        }
    function getcreditamount($refno,$categoryswitch){
        $amount = DB::Select("select sum(amount) as amount from credits where refno = '$refno' and categoryswitch = '$categoryswitch'");
        
        foreach($amount as $mnt){
            $mt = $mnt->amount;
        }
        
        if(!isset($mt)){
            $mt=0;
        }
        return $mt;
    }
    
    function getcreditamount1($refno,$categoryswitch){
        $amount = DB::Select("select sum(amount) as amount from credits where refno = '$refno' and categoryswitch >= '$categoryswitch' and acctcode != 'Reservation'");
        
        foreach($amount as $mnt){
            $mt = $mnt->amount;
        }
        
        if(!isset($mt)){
            $mt=0;
        }
        return $mt;
    }
    
    
    //TVET Batch Print for SOA
    function tvetsoa(){
        $batch = DB::Select("Select distinct period from statuses where department = 'TVET'");
        $sys = DB::Select("Select min(schoolyear) as schoolyear from statuses where department = 'TVET'");
        return view('accounting.tvetsoa',compact('batch','sys'));
    }
    
    function gettvetsoasummary(Request $request){
        session()->put('remind', $request->reminder);
        $trandate = date('Y-m-d',strtotime($request->day.'-'.$request->month.'-'.$request->year));

        if($request->section=="All"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$request->batch' and statuses.status = '2' and statuses.course = '$request->course' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");

        }
        else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$request->batch' and statuses.status = '2' and statuses.course = '$request->course' and statuses.section = '$request->section' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");
        }

        return view('accounting.showtvetsoa',compact('soasummary','request','trandate'));
        //return $trandate;
    }
    
    function printtvetsoasummary($period,$course,$section,$trandate){
        if($section=="All"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");

        }
        else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course' and statuses.section = '$section' and ledgers.duedate <= '$trandate'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadview('print.printtvetsoasummary',compact('soasummary','period','course','section','trandate'));
        return $pdf->stream();
    }
    
    function printtvetallsoa($period,$course,$section,$trandate){
        if($section=="All"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");

        }
        else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.section, statuses.period, "
            . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
            . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
            . " statuses.period = '$period' and statuses.status = '2' and statuses.course = '$course' and statuses.section = '$section'"
            . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level order by statuses.section ASC, users.lastname, users.firstname, statuses.plan");
        }

        $reminder = session('remind');
        
        return view('print.printalltvetsoa',compact('soasummary','period','course','section','trandate','reminder'));
        
    }
    
    //Grade School Batch Print for SOA
    function statementofaccount(){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $sy=$schoolyear->schoolyear;
        $levels = \App\CtrLevel::all();
        $payscheds=DB::Select("select distinct plan from ctr_payment_schedules order by plan");
        return view('accounting.statementofaccount',compact('sy','levels','payscheds'));
    }
    function studentsoa($idno,Request $request){
        session()->put('remind', $request->reminder);
        return $this->printsoa($idno, $request->soadate);
    }
    function printsoa($idno, $trandate){
          $statuses = \App\Status::where('idno',$idno)->first();
          $users = \App\User::where('idno',$idno)->first();
          $balances = DB::Select("select sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount, "
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, receipt_details, categoryswitch  from ledgers  where "
                  . " idno = '$idno'  and (categoryswitch <= '6' or ledgers.receipt_details LIKE 'Trainee%') group by "
                  . "receipt_details, categoryswitch order by categoryswitch");
          
          if($statuses->department == "TVET"){
          $balances = DB::Select("select sum(amount)+sum(s.discount)+sum(s.subsidy)+sum(sponsor) as amount ,sum(amount) as trainees ,sum(s.discount) as discount, sum(payment) as payment, sum(sponsor) as sponsor,"
                  . "sum(s.subsidy) as subsidy ,receipt_details from ledgers join tvet_subsidies as s on s.idno=ledgers.idno and s.batch=ledgers.period where ledgers.idno = '$idno' and ledgers.receipt_details LIKE 'Trainee%' group by receipt_details, categoryswitch order by categoryswitch");
          }
          
          $schedules=DB::Select("select sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount, "
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, duedate  from ledgers  where "
                  . " idno = '$idno' and (categoryswitch <= '6' or ledgers.receipt_details LIKE 'Trainee%') group by "
                  . "duedate order by duedate");

          $others=DB::Select("select sum(amount) - sum(plandiscount) - sum(otherdiscount) - "
                  . "sum(payment) - sum(debitmemo) as balance ,sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount,"
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, receipt_details,description, categoryswitch from ledgers  where "
                  . " idno = '$idno' and categoryswitch > '6' and ledgers.receipt_details NOT LIKE 'Trainee%'  group by "
                  . "receipt_details, transactiondate order by LEFT(receipt_details, 4) ASC,id");
          $schedulebal = 0;
          if(count($schedules)>0){
              foreach($schedules as $sched){
                  if($sched->duedate <= $trandate){
                   $schedulebal = $schedulebal + $sched->amount - $sched->discount -$sched->debitmemo - $sched->payment;
                  }
              }
          }
          $otherbalance = 0;
          if(count($others)>0){
              foreach($others as $ot){
                  $otherbalance = $otherbalance+$ot->balance;
              }
          }

          $totaldue = $schedulebal + $otherbalance;
          $reminder = session('remind');
          $pdf = \App::make('dompdf.wrapper');
          // $pdf->setPaper([0, 0, 336, 440], 'portrait');
          $pdf->loadView("print.printsoa",compact('statuses','users','balances','trandate','schedules','others','otherbalance','totaldue','reminder'));
          return $pdf->stream();
    }
 
    function getsoasummary($level,$strand,$section,$trandate,$plan,$amtover){
        $plans =array();
        $plans = $plan;
        if(in_array("monthly1monthly2", $plans)){
           $plans [] = "Monthly 1";
           $plans [] = "Monthly 2";
        }
        $planparam = "AND (plan IN(";
        foreach($plans as $plans){
            $planparam = $planparam."'".$plans."',";
        }
        $planparam = substr($planparam, 0, -1);
        $planparam = $planparam . "))";

        session()->put('planparam', $planparam);

          if($strand=="none"){
              if($section=="All"){$soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan, statuses.section, statuses.level, "
                   . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                   . " from users, statuses, ledgers,ctr_sections where ctr_sections.section = statuses.section and ctr_sections.level = statuses.level and users.idno = statuses.idno and users.idno = ledgers.idno and "
                   . " statuses.level = '$level' and statuses.status = '2' and ledgers.duedate <= '$trandate' $planparam  "
                   . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level having amount > '$amtover' order by ctr_sections.id ASC, users.lastname, users.firstname, statuses.plan");

              }else{
              $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan,statuses.section, statuses.level,"
                   . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                   . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and "
                   . " statuses.level = '$level' and statuses.section='$section' and statuses.status = '2' and ledgers.duedate <= '$trandate' $planparam  "
                   . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section, statuses.level having amount > '$amtover' order by users.lastname, users.firstname, statuses.plan");
              }
          }   else{  
           $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan,"
                   . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                   . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and statuses.status = '2' and "
                   . " statuses.level = '$level' and statuses.strand='$strand' and statuses.section='$section' and ledgers.duedate <= '$trandate' $planparam  "
                   . " group by statuses.idno, users.lastname, users.firstname, users.middlename having amount > '$amtover' order by users.lastname, users.firstname, statuses.plan");    
          }



               return view('accounting.showsoa',compact('soasummary','trandate','level','section','strand','amtover','plan'));
          //     return $planparam;
           }
        
    function printallsoa($level,$strand,$section,$trandate,$amtover){

         $planparam = session('planparam');   

          if($strand=="none"){
          if($section=="All"){$soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan, statuses.section, statuses.level, "
                   . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                   . " from users, statuses, ledgers,ctr_sections where ctr_sections.section = statuses.section and ctr_sections.level = statuses.level and users.idno = statuses.idno and users.idno = ledgers.idno and  statuses.status = 2 and"
                   . " statuses.level = '$level'  and ledgers.duedate <= '$trandate' $planparam  "
                   . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level having amount > '$amtover' order by ctr_sections.id ASC, users.lastname, users.firstname, statuses.plan");

              }else{
              $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan,statuses.section, statuses.level,"
                   . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                   . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and  statuses.status = 2 and"
                   . " statuses.level = '$level' and statuses.section='$section' and ledgers.duedate <= '$trandate' $planparam  "
                   . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section, statuses.level having amount > '$amtover' order by users.lastname, users.firstname, statuses.plan");    
              }
          }   else{  
           $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan,"
                   . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                   . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and  statuses.status = 2 and"
                   . " statuses.level = '$level' and statuses.strand='$strand' and statuses.section='$section' and ledgers.duedate <= '$trandate' $planparam  "
                   . " group by statuses.idno, users.lastname, users.firstname, users.middlename having amount > '$amtover' order by users.lastname, users.firstname, statuses.plan");    
          }

          $reminder = session('remind');
          return view('print.printallsoa',compact('soasummary','trandate','level','section','strand','amtover','plan','reminder'));


            }
        
    function printsoasummary($level,$strand,$section,$trandate,$amtover){
            $planparam = session('planparam'); 
           if($strand=="none"){
              if($section=="All"){$soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan, statuses.section, statuses.level, "
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers,ctr_sections where ctr_sections.section = statuses.section and ctr_sections.level = statuses.level and users.idno = statuses.idno and users.idno = ledgers.idno and  statuses.status = 2 and"
                    . " statuses.level = '$level'  and ledgers.duedate <= '$trandate' $planparam  "
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section,statuses.level having amount > '$amtover' order by ctr_sections.id ASC, users.lastname, users.firstname, statuses.plan");

               }else{
               $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan,statuses.section, statuses.level,"
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and  statuses.status = 2 and"
                    . " statuses.level = '$level' and statuses.section='$section' and ledgers.duedate <= '$trandate' $planparam  "
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename,statuses.section, statuses.level having amount > '$amtover' order by users.lastname, users.firstname, statuses.plan");    
               }
           }   else{  
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.plan,"
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and  statuses.status = 2 and"
                    . " statuses.level = '$level' and statuses.strand='$strand' and statuses.section='$section' and ledgers.duedate <= '$trandate' $planparam  "
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename having amount > '$amtover' order by users.lastname, users.firstname, statuses.plan");    
           }



            $pdf = \App::make('dompdf.wrapper');
          // $pdf->setPaper([0, 0, 336, 440], 'portrait');
            $pdf->loadview('print.printsoasummary',compact('soasummary','trandate','level','section','strand','amtover','plan'));
            return $pdf->stream();
             }
             
    //Penalty
    function penalties(){
                $duemonths = DB::Select('select distinct plan from statuses');
              return view('accounting.penaltydue',compact('duemonths'));  
            }
        
    function postviewpenalty(Request $request){
            $currentdate= Carbon::now(); 
            $forthemonth = date('M Y',strtotime($currentdate));
            $postings = \App\penaltyPostings::where('duemonth',$forthemonth)->where('plan',$request->plan)->get();
            $schoolyear = \App\CtrRefSchoolyear::first();
            $sy=$schoolyear->schoolyear;
            $levels = \App\CtrLevel::all();
            $plan = $request->plan;
            //non monthly 2
            if($plan=="Monthly 2"){
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand, "
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers where users.idno = statuses.idno and users.idno = ledgers.idno and statuses.department != 'TVET' and "
                    . " ledgers.duedate <= '$currentdate' and statuses.status='2' and statuses.plan = 'Monthly 2'"
                    . " AND ledgers.acctcode IN ('Tuition Fee','Registration & Other Institutional Fees','Department Facilities')"
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand  order by statuses.strand, users.lastname, users.firstname");
            }else{
            $soasummary = DB::Select("select statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand, "
                    . " sum(ledgers.amount) - sum(ledgers.payment) - sum(ledgers.debitmemo) - sum(ledgers.plandiscount) - sum(ledgers.otherdiscount) as amount "
                    . " from users, statuses, ledgers,ctr_sections,ctr_levels where ctr_levels.level = statuses.level and ctr_sections.level = statuses.level and ctr_sections.section = statuses.section and users.idno = statuses.idno and users.idno = ledgers.idno and statuses.department != 'TVET' and "
                    . " ledgers.duedate <= '$currentdate' and statuses.status='2' and ledgers.acctcode like 'Tuition %' and statuses.plan = '$plan'"
                    . " group by statuses.idno, users.lastname, users.firstname, users.middlename, statuses.level, statuses.section, statuses.strand  order by ctr_levels.id ASC, ctr_sections.id ASC, statuses.strand, users.lastname, users.firstname");

            }

            return view('accounting.penalties',compact('sy','levels','currentdate','postings','soasummary','plan','forthemonth'));
            }
 
    function postpenalties(Request $request){
                $findpost = \App\penaltyPostings::where('duemonth',$request->duemonth)->where('plan',$request->plan)->first();
                if(count($findpost)==0){

                $idnumber = $request->idnumber;
                $schoolyear = \App\CtrRefSchoolyear::first();
                $plan=$request->plan;
                $duemonth=$request->duemonth;
                foreach($idnumber as $value){
                    $status=  \App\Status::where('idno',$value)->first();
                    $newpenalty = new \App\Ledger;
                    $newpenalty->idno = $value;
                    $newpenalty->department=$status->department;
                    $newpenalty->level=$status->level;
                    $newpenalty->course=$status->course;
                    $newpenalty->strand=$status->strand;
                    $newpenalty->transactiondate= Carbon::now();
                    $newpenalty->categoryswitch = '7';
                    $newpenalty->acctcode="Other Revenue";
                    $newpenalty->description="Penalty(" . date('M Y') .")";
                    $newpenalty->receipt_details="Miscellaneous Others";
                    $newpenalty->amount=$this->addpenalties($value,$plan);
                    $newpenalty->schoolyear=$status->schoolyear;
                    $newpenalty->period=$status->period;
                    $newpenalty->duedate=Carbon::now();
                    $newpenalty->duetype='0';
                    $newpenalty->postedby=\Auth::user()->idno;
                    $newpenalty->save();
                }
                $addpost = new \App\penaltyPostings;
                $addpost->dateposted=Carbon::now();
                $addpost->plan=$request->plan;
                $addpost->duemonth=$request->duemonth;
                $addpost->postedby=\Auth::user()->idno;
                $addpost->save();
                return view('accounting.successfullyadded');



                // return $request->idnumber;

                }else{
                return "Already Posted";    
                }
            }
 
    function addpenalties($idnumber,$plan){

                $currentdate= Carbon::now();
                if($plan != "Monthly 2"){
                $soasummary = DB::Select("select "
                    . " sum(amount) - sum(payment) - sum(debitmemo) - sum(plandiscount) - sum(otherdiscount) as amount from"
                    . " ledgers where idno = '$idnumber' and "
                    . " duedate <= '$currentdate'  and categoryswitch = '6'");
                } else {
                    $soasummary = DB::Select("select "
                    . " sum(amount) - sum(payment) - sum(debitmemo) - sum(plandiscount) - sum(otherdiscount) as amount from"
                    . " ledgers where idno = '$idnumber' and "
                    . " duedate <= '$currentdate'  and categoryswitch <= '6'");
                }
                foreach($soasummary as $soa){
                    $amount = $soa->amount;
                }

                $penalty = $soa->amount * 0.05;
                if($penalty < 250){
                    $penalty = 250;
                }
                return $penalty;
            }
        

    //Subsidiary
    function subsidiary(){
                if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')){
                $acctcodes = DB::Select("select distinct description from credits order by description");
                $depts = DB::Select("select distinct sub_department from credits order by sub_department");    
                return view('accounting.subsidiary',compact('acctcodes','depts'));

                }
            }  
        
    function postsubsidiary(Request $request){
       if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')){

    if($request->all=="1"){
        if($request->deptname =="none"){
            $dblist = DB::Select("select users.idno, users.lastname, users.firstname, users.middlename, credits.transactiondate, credits.receiptno, credits.amount, credits.description, credits.postedby "
             . "from users, credits where users.idno = credits.idno and credits.description = '".$request->accountname ."' and credits.isreverse='0' order by users.lastname, users.firstname");
            }else{
                $dblist = DB::Select("select users.idno, users.lastname, users.firstname, users.middlename, credits.transactiondate, credits.receiptno, credits.amount, credits.description, credits.postedby "
                . "from users, credits where users.idno = credits.idno and credits.description = '".$request->accountname ."' and credits.isreverse='0' and credits.sub_department = '". $request->deptname."' order by users.lastname, users.firstname");
    }
    }
    else{ 
      if($request->deptname =="none"){
           $dblist = DB::Select("select users.idno, users.lastname, users.firstname, users.middlename, credits.transactiondate, credits.receiptno, credits.description, credits.amount, credits.postedby "
             . "from users, credits where users.idno = credits.idno and credits.description = '".$request->accountname ."' and credits.isreverse= '0' and credits.transactiondate  between '".$request->from ."' AND '" . $request->to ."'"
             . "order by users.lastname, users.firstname");

      }
      else{
     $dblist = DB::Select("select users.idno, users.lastname, users.firstname, users.middlename, credits.transactiondate, credits.receiptno, credits.description, credits.amount, credits.postedby "
             . "from users, credits where users.idno = credits.idno and credits.isreverse = '0' and credits.description = '".$request->accountname ."' and credits.sub_department = '". $request->deptname. "' and (credits.transactiondate  between '".$request->from ."' AND '" . $request->to ."')"
             . "order by users.lastname, users.firstname");
      }
    }
    $all = $request->all;
    $from = $request->from;
    $to = $request->to;
    return view('print.printsubsidiary',compact('dblist','request'));
       }    
    }
        
    public function setsoasummary(Request $request){
    $level  = $request->level;
    $trandate = $request->year ."-". $request->month ."-" . $request->day;
    $strand="none";
    $plan = $request->plan;
    $amtover = $request->amtover;
    if($amtover == ""){
     $amtover = 0;
    }

    session()->put('remind', $request->reminder);

    $section = $request->section;
    return $this->getsoasummary($level,$strand,$section,$trandate,$plan,$amtover);

    }
}
