<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Accounting\TvetSoaController;

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
 /* 
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
*/
function dmcmreport($transactiondate){
$matchfields = ['postedby'=>\Auth::user()->idno, 'transactiondate'=>$transactiondate];
//$matchfields = ['transactiondate'=>$transactiondate];

    //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse,  dedits.refno, dedits.acctcode from users, dedits where users.idno = dedits.idno and"
                . " dedits.postedby = '".\Auth::user()->idno."' and dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.entry_type = '2' group by users.idno, dedits.transactiondate, users.lastname, users.firstname, dedits.isreverse,dedits.refno, dedits.acctcode order by dedits.refno" );
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

 function summarymain($schoolyear){ 
     return view('accounting.showsummarymain',compact('schoolyear'));
     
 }   
 function maincollection($entry,$fromtran,$totran){
     
      $trials = DB::Select("select r.accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa join "
                . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  and entry_type = '$entry' group by accountingcode "
                . "UNION ALL "
                . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  and entry_type = '$entry' group by accountingcode) r "
                . "on coa.acctcode = r.accountingcode group by accountingcode order by coa.id");
     
     //$credits = DB::Select("select sum(amount) as amount,acctcode from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and entry_type = '$entry' group by acctcode");
     // $debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and entry_type = '$entry' group by acctcode, depositto");
     //$debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and (paymenttype = '1' or paymenttype = '2') group by depositto");
     //$debitdebitmemos = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode from dedits where (transactiondate between '$fromtran' and '$totran') and paymenttype = '3' and isreverse = '0' group by acctcode");
     //$debitdiscounts = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '4'");
     //$debitreservations = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '5'");
   
    //return view('accounting.maincollection',compact('credits','debitcashchecks','fromtran','totran','entry')); 
      
      return view('accounting.debitcreditsummary',compact('trials','entry','fromtran','totran'));
 }
 
  function printmaincollection($entry,$fromtran,$totran){
      $trials = DB::Select("select r.accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa join "
                . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  and entry_type = '$entry' group by accountingcode "
                . "UNION ALL "
                . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  and entry_type = '$entry' group by accountingcode) r "
                . "on coa.acctcode = r.accountingcode group by accountingcode order by coa.id");
     
     //$credits = DB::Select("select sum(amount) as amount,acctcode from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and entry_type = '$entry' group by acctcode");
      //$debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and entry_type = '$entry' group by acctcode, depositto");
     //$debitcashchecks = DB::Select("select sum(amount)+sum(checkamount) as totalamount, depositto from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and (paymenttype = '1' or paymenttype = '2') group by depositto");
     //$debitdebitmemos = DB::Select("select sum(amount)+sum(checkamount) as totalamount, acctcode from dedits where (transactiondate between '$fromtran' and '$totran') and paymenttype = '3' and isreverse = '0' group by acctcode");
     //$debitdiscounts = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '4'");
     //$debitreservations = DB::Select("select sum(amount)+sum(checkamount) as totalamount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' and paymenttype = '5'");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('print.printcashentry',compact('trials','fromtran','totran','entry')); 
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
  


    
    



    //Grade School Batch Print for SOA
    function statementofaccount(){
        $schoolyear = \App\CtrRefSchoolyear::first();
        $sy=$schoolyear->schoolyear;
        $levels = \App\CtrLevel::all();
        $payscheds=DB::Select("select distinct plan from ctr_payment_schedules order by plan");
        return view('accounting.statementofaccount',compact('sy','levels','payscheds'));
    }
    function studentsoa($idno,Request $request){
        $statuses = \App\Status::where('idno',$idno)->first();
        session()->put('remind', $request->reminder);
        if($statuses->department == "TVET"){
            return TvetSoaController::printTvetSoa($idno, $request->soadate);
        }else{
            return $this->printsoa($idno, $request->soadate);
        }
        
    }
    function printsoa($idno, $trandate){
          $statuses = \App\Status::where('idno',$idno)->first();
          $users = \App\User::where('idno',$idno)->first();
          $balances = DB::Select("select sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount, "
                  . "sum(payment) as payment, sum(debitmemo) as debitmemo, receipt_details, categoryswitch  from ledgers  where "
                  . " idno = '$idno'  and (categoryswitch <= '6' or ledgers.receipt_details LIKE 'Trainee%') group by "
                  . "receipt_details, categoryswitch order by categoryswitch");
          
          if($statuses->department == "TVET"){
                return TvetSoaController::printTvetSoa($idno, $trandate);
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
    
    function overallcollection($transactiondate){
        $matchfields = ['transactiondate'=>$transactiondate];
        //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno, dedits.postedby,non_students.fullname from dedits left join users on users.idno = dedits.idno left join non_students on non_students.idno = dedits.idno where"
                . " dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, dedits.postedby, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
        
        return view('accounting.overallcollection',compact('collections','transactiondate'));
    
} 
}
