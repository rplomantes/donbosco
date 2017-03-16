<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;


class CashierController extends Controller
{
      public function __construct()
	{
		$this->middleware('auth');
	}
        
   function view($idno){
       if(\Auth::user()->accesslevel==env('USER_CASHIER') || \Auth::user()->accesslevel == env('USER_CASHIER_HEAD')){
       $student = \App\User::where('idno',$idno)->first();
       $status = \App\Status::where('idno',$idno)->first();  
       $reservation = 0;
       $deposit = 0;
       $totalprevious = 0;
       $ledgers = null;
       $dues = null;
       $penalty = 0;
       $totalmain = 0;
       $totalothers=0;
       session()->put('remind', "");
       //Get other added collection
       $matchfields = ["idno"=>$idno, "categoryswitch"=>env("OTHER_FEE")];
       $othercollections = \App\Ledger::where($matchfields)->get();
       //get total othercollection
       
       if(count($othercollections) > 0 ){
           foreach($othercollections as $othercollection){
               $totalothers = $totalothers + $othercollection->amount - $othercollection->payment - $othercollection->debitmemo;
           }
       }
       
       //get previous balance
       $previousbalances = DB::Select("select schoolyear, sum(amount)- sum(plandiscount)- sum(otherdiscount)
               - sum(debitmemo) - sum(payment) as amount from ledgers where idno = '$idno' 
               and categoryswitch >= '"  .env('PREVIOUS_ELEARNING_FEE') ."' group by schoolyear");
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
                       
       }
       
               }
               
               $studentdeposit = DB::Select("select amount from student_deposits where idno = '$idno' and status=1");
               if(count($studentdeposit)>0 ){
                   foreach($studentdeposit as $deposits){
                       $deposit = $deposit + $deposits->amount;
                   }
               }
           
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
        
           $debitdms = DB::SELECT("select * from dedits where idno = '" . $idno . "' and "
                   . "paymenttype = '3' order by transactiondate");
           return view('cashier.studentledger',  compact('debitdms','debits','penalty','totalmain','totalprevious','previousbalances','othercollections','student','status','ledgers','reservation','dues','totalothers','deposit'));
           
       }   
       
   }
   
    function setStatus($idno){
        $newstatus = \App\User::where('idno',$idno)->first();
        if($newstatus->status < '2'){
            $newstatus->status = '2';
            $newstatus->update();
        }
        
        return true;
    }
    
    function getRefno(){
         $newref= \Auth::user()->id . \Auth::user()->reference_number;
         return $newref;
    }
    
    function getOR(){
        //$user = \App\User::where('idno', Auth::user()->idno )->first();
        $receiptno = \Auth::user()->receiptno;
        return $receiptno;
       
    }
    
    function setreceipt($id){
       //return  $id = Auth::user()->id;
       $receiptno = \Auth::user()->receiptno;
       return view('cashier.setreceipt',compact('id','receiptno'));
    }
    
    function setOR(Request $request){
     $receiptno = \App\User::where('id', \Auth::user()->id)->first();
     $receiptno->receiptno = $request->receiptno;
     $receiptno->save();
     return redirect('/');
    }
    
     function payment(Request $request){
            $account=null;
            $orno = $this->getOR();
            $refno = $this->getRefno();
            $discount = 0;
            $change = 0;
          
            $this->reset_or();
            if($request->totaldue > 0 ){
            $totaldue = $request->totaldue;       
            //$accounts = \App\Ledger::where('idno',$request->idno)->where('categoryswitch','<=','6')->orderBy('categoryswitch')->get();
                $accounts = DB::SELECT("select * from ledgers where idno = '".$request->idno."' and categoryswitch <= '6' "
                     ." and (amount - payment - debitmemo - plandiscount - otherdiscount) > 0 order By duedate, categorySwitch");    
                    foreach($accounts as $account){
                    
                        $balance = $account->amount - $account->payment - $account->plandiscount - $account->otherdiscount - $account->debitmemo;
                       
                            if($balance < $totaldue){
                                $discount = $discount + $account->plandiscount + $account->otherdiscount;
                                $updatepay = \App\Ledger::where('id',$account->id)->first();
                                $updatepay->payment = $updatepay->payment + $balance;
                                $updatepay->save();
                                $totaldue = $totaldue - $balance;
                                $this->credit($request->idno, $account->id, $refno, $orno, $account->amount-$account->payment-$account->debitmemo);
                            } else {
                                $updatepay = \App\Ledger::where('id',$account->id)->first();
                                $updatepay->payment = $updatepay->payment + $totaldue;
                                    $updatepay->save();
                                    if($totaldue==$balance){
                                    $discount = $discount + $account->plandiscount + $account->otherdiscount;    
                                    $this->credit($request->idno, $account->id, $refno, $orno, $account->amount -$account->payment - $account->debitmemo);
                                    }else{      
                                    $this->credit($request->idno, $account->id, $refno, $orno, $totaldue);
                                    }
                                $totaldue = 0;
                                break;
                            }
                    }
             
                    $this->changestatatus($request->idno, $request->reservation);
                        if($request->reservation > 0){
                        $this->debit_reservation_discount($refno, $orno,$request->idno,env('DEBIT_RESERVATION'), $request->reservation,'Reservation');
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
                        $updatepay = \App\Ledger::where('id',$up->id)->first();
                        $updatepay->payment = $updatepay->payment + $balance;
                        $updatepay->save();
                        $previous = $previous - $balance;
                        $this->credit($request->idno, $up->id, $refno, $orno, $up->amount);
                        } else {
                        $updatepay = \App\Ledger::where('id',$up->id)->first();
                        $updatepay->payment = $updatepay->payment + $previous;
                        $updatepay->save();
                        $this->credit($request->idno, $up->id, $refno, $orno, $previous);
                        $previous = 0;
                        break;
                        }
                    }   
                }
            
            if(isset($request->other)){
                    foreach($request->other as $key=>$value){
                    if($value > 0){
                    $updateother = \App\Ledger::find($key);
                    $updateother->payment = $updateother->payment + $value;
                    $updateother->save();
                    $this->credit($updateother->idno, $updateother->id, $refno, $orno, $value);
                    }
                    }
                
                    $statusnow =  \App\Status::where('idno',$request->idno)->where('department','TVET')->first();
                    if(count($statusnow)>0){
                        if($statusnow->status=="1"){
                        $statusnow->status="2";
                        $statusnow->update(); 
                        $this->setuptuitionfee(1, $request->idno, $statusnow->schoolyear);
                        
                        }
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
                        $updatepay->payment = $updatepay->payment + $balance;
                        $updatepay->save();
                        $penalty = $penalty - $balance;
                        $this->credit($request->idno, $pen->id, $refno, $orno, $pen->amount);
                        } else {
                        $updatepay = \App\Ledger::where('id',$pen->id)->first();
                        $updatepay->payment = $updatepay->payment + $penalty;
                        $updatepay->save();
                        $this->credit($request->idno, $pen->id, $refno, $orno, $penalty);
                        $penalty = 0;
                        break;
                        }
                    }
                }
            
            if($request->fape > 0){
                $this->debit_reservation_discount($refno, $orno,$request->idno,7, $request->fape,'FAPE');
                
                if($request->totalamount < $request->fape){
                    $remainingbalance = $request->fape - $request->totalamount;
                    $creditstudentdeposit = new \App\Credit;
                    $creditstudentdeposit->idno = $request->idno;
                    $creditstudentdeposit->transactiondate = Carbon::now();
                    $creditstudentdeposit->refno = $refno;
                    $creditstudentdeposit->receiptno = $orno;
                    $creditstudentdeposit->categoryswitch = '9';
                    $creditstudentdeposit->entry_type = '1';
                    $creditstudentdeposit->accountingcode = '210100';
                    $creditstudentdeposit->acctcode='Other Current Liabilities';
                    $creditstudentdeposit->description='Student Deposit';
                    $creditstudentdeposit->receipt_details = 'Student Deposit';
                    $creditstudentdeposit->amount = $remainingbalance;
                    $creditstudentdeposit->postedby = \Auth::user()->idno;
                    $creditstudentdeposit->sub_department = 'None';
                    $creditstudentdeposit->save(); 
                    
                    $deposit = new \App\StudentDeposit;
                    $deposit->amount = $remainingbalance; 
                    $deposit->idno = $request->idno;
                    $deposit->transactiondate = Carbon::now();
                    $deposit->postedby = \Auth::user()->idno;
                    $deposit->save();
                }
            }
            
            if($request->deposit > 0){
                $this->debit_reservation_discount($refno, $orno,$request->idno,8, $request->deposit,'Student Deposit');
                $this->reduce_deposit($request->deposit,$request->idno);
            }
                
            $bank_branch = "";
            $check_number = "";
            
            //if($request->receivecheck > "0"){
            $bank_branch=$request->bank_branch; 
            $check_number = $request->check_number;
            $iscbc = 0;
                if($request->iscbc =="cbc"){
                    $iscbc = 1;
                }
            $depositto = $request->depositto;    
            $totalcash = $request->receivecash - $request->change;
            $receiveamount = $request->receivecash ;
            $remarks=$request->remarks;
            $this->debit($refno, $orno,$request->idno,env('DEBIT_CHECK') , $bank_branch, $check_number,$totalcash, $request->receivecheck, $iscbc,$depositto,$receiveamount,$remarks);
            //}
            
            
           //if($request->receivecash > "0){
            //$this->debit($request->idno,env('DEBIT_CASH') , $bank_branch, $check_number, $request->receiveamount, '0');
            //}
                    
            if($discount > 0 ){
                $discountname="Plan Discount";
                $schoolyear = \App\ctrSchoolYear::first()->schoolyear;
                $disc = \App\Discount::where('idno',$request->idno)->first();
                if($disc != ""){
                    
                    $discountname = $disc->description;
                }
              $this->debit_reservation_discount($refno, $orno,$request->idno,env('DEBIT_DISCOUNT') , $discount, $discountname);
                  
          }
            
          
          return $this->viewreceipt($refno, $request->idno);
          //return redirect(url('/viewreceipt',array($refno,$request->idno)));  
          //return view("cashier.payment", compact('previous','idno','reservation','totaldue','totalother','totalprevious','totalpenalty'));
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
               
               $this->setuptuitionfee(1, $idno, $status->schoolyear);
           }
       }
   }
   
   function reduce_deposit($deposit,$idno){
       do{
           $deposits = \App\StudentDeposit::where('idno',$idno)->where('amount','>',0)->where('status',1)->first();
           if(count($deposits)>0){
               if($deposits->amount < $deposit){
                   $deposit = $deposit - $deposits->amount;
                   $deposits->amount = 0;
                   $deposits->save();
               }else{
                   $current_amount = $deposits->amount;
                   $deposits->amount = $deposits->amount - $deposit;
                   $deposits->save();

                   $deposit = $deposit - $current_amount;
               }   
           }
           
       }while($deposit > 0);
       
       return $deposit;
       
   }
   
  function addreservation($idno){
      $status=  \App\Status::where('idno',$idno)->first();
      $addcredit = new \App\Credit;
      $addcredit->idno = $idno;
      $addcredit->transactiondate = Carbon::now();
      $addcredit->refno = $this->getRefno();
      $addcredit->receiptno = $this->getOR();
      $addcredit->categoryswitch = "9";
      $addcredit->entry_type = "1";
      $addcredit->accountingcode = '210400';
      $addcredit->acctcode = " Enrollment Reservation";
      $addcredit->description = "Enrollment Reservation";
      $addcredit->receipt_details = "Enrollment Reservation";
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
      $adddebit->paymenttype = '5';
      $adddebit->amount = "1000.00";
      $adddebit->accountingcode = '210400';
      $adddebit->acctcode = " Enrollment Reservation";
      $adddebit->description = "Enrollment Reservation";
      $adddebit->refno = $this->getRefno();
      $adddebit->receiptno = $this->getOR();
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
    function reset_or(){
        $resetor = \App\User::where('idno', \Auth::user()->idno)->first();
        $resetor->receiptno = $resetor->receiptno + 1;
        $resetor->reference_number = $resetor->reference_number + 1;
        $resetor->save();
    }
    
    function consumereservation($idno){
        $crs= \App\AdvancePayment::where('idno',$idno)->get();
        foreach($crs as $cr){
            $cr->status = "1";
            $cr->postedby = \Auth::user()->idno;
            $cr->save();
        }
    }
   
    function debit($refno, $orno,$idno, $paymenttype, $bank_branch, $check_number,$cashamount,$checkamount,$iscbc,$depositto,$receiveamount,$remarks){
        $student= \App\User::where('idno', $idno)->first();
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $idno;
        $debitaccount->transactiondate=Carbon::now();
        $debitaccount->refno=$refno;
        switch($depositto){
            case 'China Bank':
                $acctcode = 'CBC-CA 1049-00 00027-8';
                $accountingcode = \App\ChartOfAccount::where('accountname','CBC-CA 1049-00 00027-8')->first();
                break;
            case 'BPI 1':
                $acctcode = 'BPI- CA 1885-1129-82';
                $accountingcode = \App\ChartOfAccount::where('accountname','BPI- CA 1885-1129-82')->first();
                break;
            case 'BPI 2':
                $acctcode = 'BPICA 1881-0466-59';
                $accountingcode = \App\ChartOfAccount::where('accountname','BPICA 1881-0466-59')->first();
                break;
        }
        
        if($paymenttype == 1 || $paymenttype == 4 || $paymenttype == 5 || $paymenttype == 7 ){
            $debitaccount->entry_type = 1;
        }else if($paymenttype == 3){
            $debitaccount->entry_type = 2;
        }
        $debitaccount->acctcode = $acctcode;
        $debitaccount->receiptno = $orno;
        $debitaccount->paymenttype = $paymenttype;
        $debitaccount->bank_branch = $bank_branch;
        $debitaccount->check_number = $check_number;
        $debitaccount->accountingcode = $accountingcode->acctcode;
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->receiveamount=$receiveamount;
        $debitaccount->iscbc = $iscbc;
        $debitaccount->depositto = $depositto;
        $debitaccount->checkamount = $checkamount;
        $debitaccount->amount = $cashamount;
        $debitaccount->remarks = $remarks;
        $debitaccount->postedby=\Auth::user()->idno;
        $debitaccount->save();
            
    }
   
    function debit_reservation_discount($refno, $orno,$idno,$debittype,$amount,$discountname){
        if($discountname == "Plan Discount"){
            $accountcode='410100';
            $acctcode='Cash - Semi Payment Discount';
            $description = 'Cash - Semi Payment Discount';
        }else if($discountname == "Reservation"){
            $accountcode='210400';
            $acctcode='Enrollment Reservation';
            $description = 'Enrollment Reservation';
        }else if($discountname == "FAPE" || $discountname == "Student Deposit"){
            $accountcode='210100';
            $acctcode='Other Current Liabilities';
            $description = $discountname;
        }else{
            $accountcode='410200';
            $acctcode='Brothers Discount';
            $description = $discountname;
        }
        $student = \App\User::where('idno',$idno)->first();
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $idno;
        $debitaccount->transactiondate=Carbon::now();
        $debitaccount->accountingcode=$accountcode;
        $debitaccount->acctcode=$acctcode;
        $debitaccount->description=$description;
        $debitaccount->refno=$refno;
        $debitaccount->entry_type='1';
        $debitaccount->receiptno = $orno;
        $debitaccount->paymenttype = $debittype;
        $debitaccount->receivefrom = $student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debitaccount->amount = $amount;    
        $debitaccount->postedby=\Auth::user()->idno;
        $debitaccount->save();
        
    }
  
   function credit($idno, $idledger, $refno, $receiptno, $amount){
       $ledger = \App\Ledger::find($idledger);
       $newcredit = new \App\Credit;
       $newcredit->idno=$idno;
       $newcredit->transactiondate = Carbon::now();
       $newcredit->referenceid = $idledger;
       $newcredit->refno = $refno;
       $newcredit->receiptno=$receiptno;
       $newcredit->accountingcode = $ledger->accountingcode;
       $newcredit->categoryswitch = $ledger->categoryswitch;
       $newcredit->acctcode = $ledger->acctcode;
       $newcredit->description = $ledger->description;
       $newcredit->receipt_details = $ledger->receipt_details;
       $newcredit->duedate=$ledger->duedate;
       $newcredit->amount=$amount;
       $newcredit->entry_type='1';
       $newcredit->sub_department=$ledger->sub_department;
       $newcredit->schoolyear=$ledger->schoolyear;
       $newcredit->period=$ledger->period;
       $newcredit->postedby=\Auth::user()->idno;
       $newcredit->save();
       
   } 
   
   function viewreceipt($refno, $idno = 0){
       $student = \App\User::where('idno',$idno)->first();
       $status= \App\Status::where('idno',$idno)->first();
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->first();
       $debit_reservation = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
       $debit_fape = \App\Dedit::where('refno',$refno)->where('paymenttype','7')->first();
       $debit_deposit = \App\Dedit::where('refno',$refno)->where('paymenttype','8')->first();
       $debit_cash = \App\Dedit::where('refno',$refno)->where('paymenttype','1')->first();
       $debit_dm = \App\Dedit::where('refno',$refno)->where('paymenttype','3')->first();
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate, sub_department from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate, sub_department");
       $timeissued =  \App\Credit::where('refno',$refno)->first();
       $timeis=date('h:i:s A',strtotime($timeissued->created_at));
       $tdate = \App\Dedit::where('refno',$refno)->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       return view("cashier.viewreceipt",compact('posted','timeis','tdate','student','debits','credits','status','debit_discount','debit_reservation','debit_cash','debit_dm','idno','refno','debit_fape','debit_deposit'));
       
   }
   
   
    function printreceipt($refno, $idno = 0){
       $student = \App\User::where('idno',$idno)->first();
       $status= \App\Status::where('idno',$idno)->first();
       $debits = \App\Dedit::where('refno',$refno)->get();
       $debit_discount = \App\Dedit::where('refno',$refno)->where('paymenttype','4')->first();
       $debit_reservation = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
       $debit_fape = \App\Dedit::where('refno',$refno)->where('paymenttype','7')->first();
       $debit_deposit = \App\Dedit::where('refno',$refno)->where('paymenttype','8')->first();
       $debit_cash = \App\Dedit::where('refno',$refno)->where('paymenttype','1')->first();
       $debit_check = \App\Dedit::where('refno',$refno)->where('paymenttype','2')->first();
       $debit_dm = \App\Dedit::where('refno',$refno)->where('paymenttype','3')->first();
       $credits = DB::Select("select sum(amount) as amount, receipt_details, transactiondate, sub_department from credits "
               . "where refno = '$refno' group by receipt_details, transactiondate, sub_department");
       $timeissued =  \App\Credit::where('refno',$refno)->first();
       $timeis=date('h:i:s A',strtotime($timeissued->created_at));
       $tdate = \App\Dedit::where('refno',$refno)->first();
       $posted = \App\User::where('idno',$tdate->postedby)->first();
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper([0, 0, 336, 440], 'portrait');
       $pdf->loadView("cashier.printreceipt",compact('posted','timeis','tdate','student','debits','credits','status','debit_discount','debit_reservation','debit_cash','debit_dm','idno','refno','debit_fape','debit_deposit'));
       return $pdf->stream();
        
    
}

    function otherpayment($idno){
        $student =  \App\User::where('idno',$idno)->first();
        $status = \App\Status::where('idno',$idno)->first();
        $acct_departments = DB::Select('Select * from ctr_acct_dept order by sub_department');
        $advances = \App\AdvancePayment::where("idno",$idno)->where("status",'2')->get();
        $advance=0;
        if(count($advances)>0){    
            foreach($advances as $adv){
               $advance=$advance+$adv->amount;
            }
        }
        $accounttypes = DB::Select("select distinct accounttype from ctr_other_payments order by accounttype");
        $paymentothers = DB::Select("select sum(amount) as amount, receipt_details from credits where idno ='" . $idno . "' and (categoryswitch = '7' OR categoryswitch = '9') and isreverse = '0' group by receipt_details");
        return view('cashier.otherpayment',compact('acct_departments','student','status','accounttypes','advance','paymentothers'));
    }

    function othercollection(Request $request){
        $or = $this->getOR();
        $refno = $this->getRefno();
        $status = \App\Status::where('idno',$request->idno)->where('status','2')->first();
        $student=  \App\User::where('idno',$request->idno)->first();
        
        $this->reset_or();
        if($request->reservation > 0 ){
            $newreservation = new \App\AdvancePayment;
            $newreservation->idno = $request->idno;
            $newreservation->transactiondate = Carbon::now();
            $newreservation->refno = $refno;
            $newreservation->amount = $request->reservation;
            $newreservation->status = '2';
            $newreservation->postedby = \Auth::user()->idno;
            $newreservation->save();
            
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->entry_type='1';
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->accountingcode = '210400';
            $creditreservation->acctcode="Enrollment Reservation";
            $creditreservation->description="Enrollment Reservation";
            $creditreservation->receipt_details = "Enrollment Reservation";
            $creditreservation->amount = $request->reservation;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->save();     
        }
        
        if($request->amount1 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount1);
            $creditreservation->acctcode=$request->groupaccount1;
            $creditreservation->description=$request->particular1;
            $creditreservation->receipt_details = $request->particular1;
            $creditreservation->amount = $request->amount1;
            $creditreservation->entry_type='1';
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department1;
            $creditreservation->save(); 
            
        }
        
           if($request->amount2 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount2);
            $creditreservation->acctcode=$request->groupaccount2;
            $creditreservation->description=$request->particular2;
            $creditreservation->receipt_details = $request->particular2;
            $creditreservation->amount = $request->amount2;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department2;
            $creditreservation->save(); 
            
        }
        
         if($request->amount3 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount3);
            $creditreservation->acctcode=$request->groupaccount3;
            $creditreservation->description=$request->particular3;
            $creditreservation->receipt_details = $request->particular3;
            $creditreservation->amount = $request->amount3;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department3;
            $creditreservation->save(); 
            
        }
         if($request->amount4 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount4);
            $creditreservation->acctcode=$request->groupaccount4;
            $creditreservation->description=$request->particular4;
            $creditreservation->receipt_details = $request->particular4;
            $creditreservation->amount = $request->amount4;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department4;
            $creditreservation->save(); 
            
        }
        
         if($request->amount5 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount5);
            $creditreservation->acctcode=$request->groupaccount5;
            $creditreservation->description=$request->particular5;
            $creditreservation->receipt_details = $request->particular5;
            $creditreservation->amount = $request->amount5;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department5;
            $creditreservation->save(); 
            
        }
        
         if($request->amount6 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount6);
            $creditreservation->acctcode=$request->groupaccount6;
            $creditreservation->description=$request->particular6;
            $creditreservation->receipt_details = $request->particular6;
            $creditreservation->amount = $request->amount6;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department6;
            $creditreservation->save(); 
        }
        
         if($request->amount7 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $request->idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount7);
            $creditreservation->acctcode=$request->groupaccount7;
            $creditreservation->description=$request->particular7;
            $creditreservation->receipt_details = $request->particular7;
            $creditreservation->amount = $request->amount7;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->sub_department = $request->acct_department7;
            $creditreservation->save(); 
            
        }
        
        //debit
        $iscbc = 0;
         if($request->iscbc =="cbc"){
                    $iscbc = 1;
                }
                
        switch($request->depositto){
            case 'China Bank':
                $acctcode = 'CBC-CA 1049-00 00027-8';
                $accountingcode = \App\ChartOfAccount::where('accountname','CBC-CA 1049-00 00027-8')->first();
                break;
            case 'BPI 1':
                $acctcode = 'BPI- CA 1885-1129-82';
                $accountingcode = \App\ChartOfAccount::where('accountname','BPI- CA 1885-1129-82')->first();
                break;
            case 'BPI 2':
                $acctcode = 'BPICA 1881-0466-59';
                $accountingcode = \App\ChartOfAccount::where('accountname','BPICA 1881-0466-59')->first();
                break;
        }
        
        $debit = new \App\Dedit;
        $debit->idno = $request->idno;
        $debit->transactiondate = Carbon::now();
        $debit->refno = $refno;
        $debit->acctcode = $acctcode;
        $debit->receiptno = $or;
        $debit->paymenttype= "1";
        $debit->entry_type="1";
        $debit->accountingcode=$accountingcode->acctcode;
        $debit->bank_branch=$request->bank_branch;
        $debit->check_number=$request->check_number;
        $debit->iscbc=$iscbc;
        $debit->amount = $request->totalcredit - $request->check;
        $debit->receiveamount = $request->cash;
        $debit->checkamount=$request->check;
        $debit->receivefrom=$student->lastname . ", " . $student->firstname . " " . $student->extensionname . " " .$student->middlename;
        $debit->depositto=$request->depositto;
        $debit->remarks=$request->remarks;
        $debit->postedby= \Auth::user()->idno;
        $debit->save();
        
        
        return $this->viewreceipt($refno, $request->idno);
        
        //return redirect(url('/viewreceipt',array($refno,$request->idno)));
    }
    
    function collectionreport($transactiondate){

        $matchfields = ['postedby'=>\Auth::user()->idno, 'transactiondate'=>$transactiondate];
        //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno,non_students.fullname from dedits "
                . "left join users on users.idno = dedits.idno left join non_students on non_students.idno = dedits.idno where"
                . " dedits.postedby = '".\Auth::user()->idno."' and dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno order by dedits.refno" );
        //$collections = \App\User::where('postedby',\Auth::user()->idno)->first()->dedits->where('transactiondate',date('Y-m-d'))->get();
        return view('cashier.collectionreport', compact('collections','transactiondate'));
    }
    
    function printcollection($idno,$transactiondate){
        
         $matchfields = ['postedby'=>$idno, 'transactiondate'=>$transactiondate];
        //$collections = \App\Dedit::where($matchfields)->get();
        $collections = DB::Select("select sum(dedits.amount) as amount, sum(dedits.checkamount) as checkamount, users.idno, users.lastname, users.firstname,"
                . " dedits.transactiondate, dedits.isreverse, dedits.receiptno, dedits.refno from users, dedits where users.idno = dedits.idno and"
                . " dedits.postedby = '".\Auth::user()->idno."' and dedits.transactiondate = '" 
                . $transactiondate . "' and dedits.paymenttype = '1' group by users.idno, dedits.transactiondate, users.lastname, users.firstname, dedits.isreverse,dedits.receiptno,dedits.refno" );
        
        $teller=\Auth::user()->firstname." ". \Auth::user()->lastname;
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView("print.printcollection",compact('collections','transactiondate','teller'));
        return $pdf->stream();  
    }
    function cancell($refno,$idno){
        
        $credits = \App\Credit::where('refno',$refno)->get();
        foreach($credits as $credit){
          
         
         $ledger = \App\Ledger::find($credit->referenceid);
        
         if(isset($ledger->payment)){
         $ledger->payment = $ledger->payment - $credit->amount + $ledger->plandiscount + $ledger->otherdiscount;
         $ledger->save();
         }
         
         
         if($credit->description == "Reservation"){
             \App\AdvancePayment::where('refno',$refno)->delete();
         }
         }
        
         
        $matchfield=["refno"=>$refno,"paymenttype"=>"5"];
        $debitreservation = \App\Dedit::where($matchfield)->first();
        if(count($debitreservation)>0){
          $res=\App\AdvancePayment::where('idno',$idno)->where('status','1')->first();
          if(isset($res->status)){
          $res->status = '0';
          $res->save();//->update(['status'=>'0']);
         
        }
        }
        
        $matchfields=["refno"=>$refno,"paymenttype"=>"8"];
        $debitdeposit = \App\Dedit::where($matchfields)->first();
        if(count($debitdeposit)>0){
          $deposit=  \App\StudentDeposit::where('idno',$idno)->where('status',1)->first();
          $deposit->amount = $deposit->amount + $debitdeposit->amount;
          $deposit->save();

        }
        \App\Credit::where('refno',$refno)->update(['isreverse'=>'1','reversedate'=>  Carbon::now(), 'reverseby'=> \Auth::user()->idno]);
        \App\Dedit::where('refno',$refno)->update(['isreverse'=>'1']);
        
        return $this->view($idno);
        //return redirect(url('cashier',$idno));
    
    }
    
    function restore($refno,$idno){
        
        $credits = \App\Credit::where('refno',$refno)->get();
        foreach($credits as $credit){
        $ledger = \App\Ledger::find($credit->referenceid);
        if(isset($ledger->payment)){
        $ledger->payment = $ledger->payment + $credit->amount - $ledger->plandiscount - $ledger->otherdiscount;
        $ledger->save();
        }
         if($credit->description == "Reservation"){
            $res = new \App\AdvancePayment;
            $res->idno = $idno;
            $res->transactiondate = Carbon::now();
            $res->refno = $refno;
            $res->amount=$credit->amount;
            $debrest=  \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
            if(count($debrest)>0){
            $res->status="1";    
            }else{
            $res->status="2";
            }
            $res->postedby = \Auth::user()->idno;
            $res->save();
         }
        }
        
       $debitreservation = \App\Dedit::where('refno',$refno)->where('paymenttype','5')->first();
        if(count($debitreservation)>0){
           
            \App\AdvancePayment::where('idno',$idno)->where('status','0')->update(['status'=>'1']);
        }
        
        $matchfields=["refno"=>$refno,"paymenttype"=>"8"];
        $debitdeposit = \App\Dedit::where($matchfields)->first();
        if(count($debitdeposit)>0){
          $deposit=  \App\StudentDeposit::where('idno',$idno)->where('status',1)->first();
          $deposit->amount = $deposit->amount - $debitdeposit->amount;
          $deposit->save();

        }
        
        \App\Credit::where('refno',$refno)->update(['isreverse'=>'0','reversedate'=>  '0000-00-00', 'reverseby'=> '']);
        \App\Dedit::where('refno',$refno)->update(['isreverse'=>'0']);
       // \App\AdvancePayment::where('refno',$refno)->where('idno',$idno)->update(['status' => '0']);
        return $this->view($idno);
        //return redirect(url('cashier',$idno));
    }
    
    function postencashment(Request $request){
        $refno = $this->getRefno();
        $encashment = new \App\Encashment;
        $encashment->transactiondate= Carbon::now();
        $encashment->refno = $refno;
        $encashment->payee = $request->payee;
        $encashment->whattype = $request->whattype;
        //$encashment->depositto = $request->depositto;
        $encashment->withdrawfrom =$request->withdrawfrom;
        $encashment->bank_branch=$request->bank_branch;
        $encashment->check_number=$request->check_number;
        $encashment->amount=$request->amount;
        $encashment->postedby = \Auth::user()->idno;
        $encashment->save();
        
        $resetor = \App\User::where('idno', \Auth::user()->idno)->first();
        $resetor->reference_number = $resetor->reference_number + 1;
        $resetor->save();
        return $this->viewencashmentdetail($encashment->refno);
        //return redirect(url('viewencashmentdetail',$encashment->refno));
        
        //return view('cashier.viewencashment',compact('encashment'));
        
    }
    
    function viewencashmentdetail($refno){
        $encashment = \App\Encashment::where('refno',$refno)->first();
        return view('cashier.viewencashment',compact('encashment'));
    }
    
    function encashment(){
        
        return view('cashier.encashment');
    }
    
    function encashmentreport(){
        $matchfields=['postedby'=>\Auth::user()->idno, 'transactiondate' => date('Y-m-d')];
        $encashmentreports = \App\Encashment::where($matchfields)->get();
        return view('cashier.viewencashmentreport',compact('encashmentreports'));
    }
    
    function printencashment($idno){
        
        $matchfields=['postedby'=>$idno, 'transactiondate' => date('Y-m-d')];
        $encashmentreports = \App\Encashment::where($matchfields)->get();
        $pdf = \App::make('dompdf.wrapper');
      
        $pdf->loadView("print.printencashment",compact('encashmentreports'));
       return $pdf->stream();
        
        
    }
    
    function reverseencashment($refno){
        $encashment = \App\Encashment::where('refno',$refno)->first();
        if($encashment->isreverse == '0'){
            $encashment->isreverse = '1';
        } else {
            $encashment->isreverse = '0';
        }
        $encashment->save();
        return $this->encashmentreport();
        
        //return redirect(url('encashmentreport'));
    }
    
    function previous($idno){
        $student = \App\User::where('idno',$idno)->first();
        $schoolyears = DB::Select("select distinct schoolyear from ledgers where idno = '$idno'");
        return view('cashier.previous',compact('student','schoolyears'));
    }
    function actualcashcheck($batch,$transactiondate){
        $cbcash=0;
        $cbcheck=0;
        $bpi1cash=0;
        $bpi1check=0;
        $bpi2cash=0;
        $bpi2check=0;
        $action="add";
        $totalissued=0;
        
        $actual = \App\ActualDeposit::where('postedby',\Auth::user()->idno)->where('transactiondate',$transactiondate)->where('batch',$batch)->first();
        
        if(count($actual)>0){
          $action="update";  
        }
        
        
        $chinabank = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . " from dedits where postedby = '".\Auth::user()->idno . "' and "
                . " transactiondate = '" . $transactiondate . "' and paymenttype = '1' and "
                . " depositto = 'China Bank' and isreverse = '0' and batch='$batch'");
        
        if(count($chinabank)>0){
            foreach($chinabank as $cb){
                $cbcash = $cbcash + $cb->amount;
                $cbcheck = $cbcheck + $cb->checkamount;
            }
        }
        
        $bpi1 = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . " from dedits where postedby = '".\Auth::user()->idno . "' and "
                . " transactiondate = '" .$transactiondate. "' and paymenttype = '1' and "
                . " depositto = 'BPI 1' and isreverse = '0' and batch='$batch'");
        
        if(count($bpi1)>0){
            foreach($bpi1 as $cb){
                $bpi1cash = $bpi1cash + $cb->amount;
                $bpi1check = $bpi1check + $cb->checkamount;
            }
        }
        
        $bpi2 = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . " from dedits where postedby = '".\Auth::user()->idno . "' and "
                . " transactiondate = '" . $transactiondate. "' and paymenttype = '1' and "
                . " depositto = 'BPI 2' and isreverse = '0' and batch='$batch'");
        
        if(count($bpi2)>0){
            foreach($bpi2 as $cb){
                $bpi2cash = $bpi2cash + $cb->amount;
                $bpi2check = $bpi2check + $cb->checkamount;
            }
        }
        
        //$totalissued = $cbcash + $cbcheck + $bpi1cash + $bpi1check + $bpi2cash + $bpi2check;
        
        $encashments = DB::Select("select sum(amount) as amount, withdrawfrom from encashments where postedby = '" . \Auth::user()->idno. "' "
                . " and transactiondate = '". $transactiondate."' and isreverse = '0' group by withdrawfrom");
        
        $encashcbc=0;
        $encashbpi1=0;
        $encashbpi2=0;
        
        foreach($encashments as $encash){
            if($encash->withdrawfrom == "China Bank"){
                $encashcbc = $encashcbc + $encash->amount;
            }
            if($encash->withdrawfrom == "BPI 1"){
                $encashbpi1 = $encashbpi1 + $encash->amount;
            }
            if($encash->withdrawfrom == "BPI 2"){
                $encashbpi2 = $encashbpi2 + $encash->amount;
            }
        }
        
        $totalissued = $cbcash + $cbcheck + $bpi1cash + $bpi1check + $bpi2cash + $bpi2check;
        //$totalissued = $totalissued - $encashcbc - $encashbpi1 - $encashbpi2;
        return view('cashier.actualcashcheck',compact('chinabank','bpi1','bpi2',
                'chinabank1','encashments','transactiondate','cbcash','cbcheck','bpi1cash',
                'bpi1check','bpi2cash','bpi2check','encashcbc','encashbpi1','encashbpi2','actual','action','transactiondate','totalissued','batch'));
        
    }
    function nonstudent(){
       
        $accounttypes = DB::Select("select distinct accounttype,acctcode from ctr_other_payments");
        return view('cashier.nonstudent', compact('accounttypes'));
        
    }
    
    function getaccountingcode($accountname){
        $coa = \App\ChartOfAccount::where('accountname',$accountname)->first();
        return $coa->acctcode;
    }
    
    function postnonstudent(Request $request){
        
       $refno = $this->getRefno();
       $or = $this->getOR(); 
       $payee = strtoupper(str_replace(' ', '', $request->name));
       $payeeexist = DB::Select("Select UPPER(REPLACE(fullname,' ','')) as name,fullname,idno from non_students where UPPER(REPLACE(fullname,' ','')) = '$payee'");
       if(count($payeeexist) > 0){
           foreach($payeeexist as $payees){
               $idno = $payees->idno;
               $name = $payees->fullname;
           }
       }else{
        $newpayee = new \App\NonStudent;
        $newpayee->idno = uniqid();
        $newpayee->fullname = $request->name;
        $newpayee->save();
        
        $idno = $newpayee->idno;
        $name = $newpayee->fullname;
       }
       $this->reset_or();
        if($request->amount1 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '7';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount1);
            $creditreservation->acctcode=$request->groupaccount1;
            $creditreservation->description=$request->particular1;
            $creditreservation->receipt_details = $request->particular1;
            $creditreservation->amount = $request->amount1;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->save(); 
        }
        
        if($request->amount2 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '7';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount2);
            $creditreservation->acctcode=$request->groupaccount2;
            $creditreservation->description=$request->particular2;
            $creditreservation->receipt_details = $request->particular2;
            $creditreservation->amount = $request->amount2;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->save(); 
        }
        
        if($request->amount3 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '7';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount3);
            $creditreservation->acctcode=$request->groupaccount3;
            $creditreservation->description=$request->particular3;
            $creditreservation->receipt_details = $request->particular3;
            $creditreservation->amount = $request->amount3;
            $creditreservation->postedby = \Auth::user()->idno;

            $creditreservation->save(); 
        }
        
        if($request->amount4 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '7';
            $creditreservation->accountingcode=$this->getaccountingcode($request->groupaccount4);
            $creditreservation->acctcode=$request->groupaccount4;
            $creditreservation->description=$request->particular4;
            $creditreservation->receipt_details = $request->particular4;
            $creditreservation->amount = $request->amount4;
            $creditreservation->postedby = \Auth::user()->idno;

            $creditreservation->save(); 
        }
        
        switch($request->depositto){
            case 'China Bank':
                $accountingcode = \App\ChartOfAccount::where('accountname','CBC-CA 1049-00 00027-8')->first();
                break;
            case 'BPI 1':
                $accountingcode = \App\ChartOfAccount::where('accountname','BPI- CA 1885-1129-82')->first();
                break;
            case 'BPI 2':
                $accountingcode = \App\ChartOfAccount::where('accountname','BPICA 1881-0466-59')->first();
                break;
        }
        
        $debit = new \App\Dedit;
        $debit->idno = $idno;
        $debit->transactiondate = Carbon::now();
        $debit->refno = $refno;
        $debit->receiptno = $or;
        $debit->paymenttype= "1";
        $debit->entry_type= "1";
        $debit->accountingcode= $accountingcode->acctcode;
        $debit->bank_branch=$request->bank_branch;
        $debit->check_number=$request->check_number;
        $debit->description = 'Cash';
        $debit->amount = $request->totalcredit;
        $debit->checkamount=$request->check;
        $debit->receiveamount = $request->cash;
        $debit->receivefrom=$name;
        $debit->depositto=$request->depositto;
        $debit->postedby= \Auth::user()->idno;
        $debit->save();
        
        return $this->viewreceipt($refno, $idno);
        
    }
    function checklist($trandate){
        $checklists = DB::Select("select bank_branch, check_number, sum(checkamount) as checkamount, receiptno, receivefrom  from dedits "
                . "where paymenttype = '1' and isreverse = '0' and postedby = '" . \Auth::user()->idno . "'"
                . " and transactiondate = '" .$trandate . "' group by bank_branch, check_number, receiptno, receivefrom order by transactiondate, refno");
      //$checklist = DB::Select("select * from dedits");
        
        return view('cashier.checklist', compact('checklists')); 
    }
    function postactual(Request $request){
        if($request->action1 == "add"){
            $postactual = new \App\ActualDeposit;
            $postactual->postedby = \Auth::user()->idno;
            $postactual->transactiondate = $request->transactiondate;
            $postactual->cbccash = $request->actualcbccash;
            $postactual->cbccheck = $request->actualcbccheck;
            $postactual->bpi1cash = $request->actualbpi1cash;
            $postactual->bpi1check = $request->actualbpi1check;
            $postactual->bpi2cash = $request->actualbpi2cash;
            $postactual->bpi2check = $request->actualbpi2check;
            $postactual->encashcbc = $request->actualencashcbc;
            $postactual->encashbpi1 = $request->actualencashbpi1;
            $postactual->encashbpi2 = $request->actualencashbpi2;
            $postactual->variance = $request->actualcbccash + $request->actualcbccheck +
                    $request->actualbpi1cash + $request->actualbpi1check +
                    $request->actualbpi2cash + $request->actualencashcbc + $request->actualencashbpi1 +
                    $request->actualencashbpi2 + $request->actualbpi2check - $request->totalissued;
            
            $postactual->save();
        }
          else  
          {
          $postactual = \App\ActualDeposit::where('postedby',\Auth::user()->idno)->where('transactiondate',$request->transactiondate)->where('batch',$request->batch)->first();   
            $postactual->postedby = \Auth::user()->idno;
            $postactual->transactiondate = $request->transactiondate;
            $postactual->cbccash = $request->actualcbccash;
            $postactual->cbccheck = $request->actualcbccheck;
            $postactual->bpi1cash = $request->actualbpi1cash;
            $postactual->bpi1check = $request->actualbpi1check;
            $postactual->bpi2cash = $request->actualbpi2cash;
            $postactual->bpi2check = $request->actualbpi2check;
            $postactual->encashcbc = $request->actualencashcbc;
            $postactual->encashbpi1 = $request->actualencashbpi1;
            $postactual->encashbpi2 = $request->actualencashbpi2;
            $postactual->variance = $request->actualcbccash + $request->actualcbccheck +
            $request->actualbpi1cash + $request->actualbpi1check +
            $request->actualbpi2cash + $request->actualbpi2check - $request->totalissued;
               $postactual->update();
        }
        return $this->actualcashcheck($request->batch, $request->transactiondate);
        //return redirect(url('actualcashcheck',array($request->batch,$request->transactiondate)));
    }
    
    function printactualcash($transactiondate){
        $cbcash=0;
        $cbcheck=0;
        $bpi1cash=0;
        $bpi1check=0;
        $bpi2cash=0;
        $bpi2check=0;
        $action="add";
        $totalissued=0;
        $actual = \App\ActualDeposit::where('postedby',\Auth::user()->idno)->where('transactiondate',$transactiondate)->first();
        
        if(count($actual)>0){
          $action="update";  
        }
        
        
        $chinabank = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . " from dedits where postedby = '".\Auth::user()->idno . "' and "
                . " transactiondate = '" . $transactiondate . "' and paymenttype = '1' and "
                . " depositto = 'China Bank' and isreverse = '0'");
        
        if(count($chinabank)>0){
            foreach($chinabank as $cb){
                $cbcash = $cbcash + $cb->amount;
                $cbcheck = $cbcheck + $cb->checkamount;
            }
        }
        
        $bpi1 = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . " from dedits where postedby = '".\Auth::user()->idno . "' and "
                . " transactiondate = '" .$transactiondate. "' and paymenttype = '1' and "
                . " depositto = 'BPI 1' and isreverse = '0'");
        
        if(count($bpi1)>0){
            foreach($bpi1 as $cb){
                $bpi1cash = $bpi1cash + $cb->amount;
                $bpi1check = $bpi1check + $cb->checkamount;
            }
        }
        
        $bpi2 = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                . " from dedits where postedby = '".\Auth::user()->idno . "' and "
                . " transactiondate = '" . $transactiondate. "' and paymenttype = '1' and "
                . " depositto = 'BPI 2' and isreverse = '0'");
        
        if(count($bpi2)>0){
            foreach($bpi2 as $cb){
                $bpi2cash = $bpi2cash + $cb->amount;
                $bpi2check = $bpi2check + $cb->checkamount;
            }
        }
        
        $totalissued = $cbcash + $cbcheck + $bpi1cash + $bpi1check + $bpi2cash + $bpi2check;
        
        $encashments = DB::Select("select sum(amount) as amount, withdrawfrom from encashments where postedby = '" . \Auth::user()->idno. "' "
                . " and transactiondate = '". $transactiondate."' and isreverse = '0' group by withdrawfrom");
        
        $encashcbc=0;
        $encashbpi1=0;
        $encashbpi2=0;
        
        foreach($encashments as $encash){
            if($encash->withdrawfrom == "China Bank"){
                $encashcbc = $encashcbc + $encash->amount;
            }
            if($encash->withdrawfrom == "BPI 1"){
                $encashbpi1 = $encashbpi1 + $encash->amount;
            }
            if($encash->withdrawfrom == "BPI 2"){
                $encashbpi2 = $encashbpi2 + $encash->amount;
            }
        }
        
        $totalissued = $cbcash + $cbcheck + $bpi1cash + $bpi1check + $bpi2cash + $bpi2check;
        $totalissued = $totalissued - $encashcbc - $encashbpi1 - $encashbpi2;
        
        
       $pdf = \App::make('dompdf.wrapper');
       //$pdf->setPaper([0, 0, 336, 440], 'portrait');
       $pdf->loadView("print.printactualcash",compact('chinabank','bpi1','bpi2',
       'chinabank1','encashments','transactiondate','cbcash','cbcheck','bpi1cash',
       'bpi1check','bpi2cash','bpi2check','encashcbc','encashbpi1','encashbpi2','actual','action','transactiondate','totalissued'));
       return $pdf->stream();
        
        
        
        
    }
    function actualdeposit($transactiondate){
          
            $debits = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount, "
                    . " depositto from dedits where paymenttype = '1' and isreverse = '0' and postedby = '"
                    . \Auth::user()->idno . "' and transactiondate = '$transactiondate' group by depositto");
            $encashments = DB::Select("select whattype, sum(amount) as amount from encashments where "
                    . "isreverse = '0' and postedby ='". \Auth::user()->idno ."' and transactiondate = '$transactiondate' "
                    . "group by whattype");
            
            $debitstotal= DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                    . "  from dedits where paymenttype = '1' and isreverse = '0' and postedby = '"
                    . \Auth::user()->idno . "' and transactiondate = '$transactiondate'");
            $totaldebit = 0;
            if(count($debitstotal)>0){
                foreach($debitstotal as $dt){
            $totaldebit = $totaldebit + $dt->amount + $dt->checkamount;
            }
            } 
            
            $deposit_slips = \App\DepositSlip::where('transactiondate', $transactiondate)
                    ->where('postedby',\Auth::user()->idno)->orderBy('bank')->get();
                    
            
            return view('cashier.actualdeposit',compact('deposit_slips','transactiondate','debits','encashments','totaldebit'));       
        
            
    }
    
    function printactualdeposit($transactiondate){
        
        $debits = DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount, "
                    . " depositto from dedits where paymenttype = '1' and isreverse = '0' and postedby = '"
                    . \Auth::user()->idno . "' and transactiondate = '$transactiondate' group by depositto");
            $encashments = DB::Select("select whattype, sum(amount) as amount from encashments where "
                    . "isreverse = '0' and postedby ='". \Auth::user()->idno ."' and transactiondate = '$transactiondate' "
                    . "group by whattype");
            
            $debitstotal= DB::Select("select sum(amount) as amount, sum(checkamount) as checkamount "
                    . "  from dedits where paymenttype = '1' and isreverse = '0' and postedby = '"
                    . \Auth::user()->idno . "' and transactiondate = '$transactiondate'");
            $totaldebit = 0;
            if(count($debitstotal)>0){
                foreach($debitstotal as $dt){
            $totaldebit = $totaldebit + $dt->amount + $dt->checkamount;
            }
            } 
            
            $deposit_slips = \App\DepositSlip::where('transactiondate', $transactiondate)
                    ->where('postedby',\Auth::user()->idno)->orderBy('bank')->get();
    
             $pdf = \App::make('dompdf.wrapper');
       $pdf->loadView("cashier.printactualdeposit",compact('deposit_slips','transactiondate','debits','encashments','totaldebit'));
       return $pdf->stream();
                }
    
    
    
    
    function cutoff($transactiondate){
        $batch = \App\Dedit::where('transactiondate',$transactiondate)->where('postedby',\Auth::user()->idno)->orderBy('batch','desc')->first();
        //return $batch->batch +1;
        
        $updatecreditbatch = \App\Credit::where('transactiondate',$transactiondate)->where('postedby',\Auth::user()->idno)
                ->where('batch','0')->get();
        foreach($updatecreditbatch as $updatecredit){
            $updatecredit->batch=$batch->batch+1;
            $updatecredit->update();
        }
        
        $updatedebitbatch = \App\Dedit::where('transactiondate',$transactiondate)->where('postedby',\Auth::user()->idno)
                ->where('batch','0')->get();
        
        foreach($updatedebitbatch as $updatedebit){
            $updatedebit->batch=$batch->batch+1;
            $updatedebit->update();
        }
            
        
        
        $newactual = new \App\ActualDeposit;
        $newactual->batch=$batch->batch+1;
        $newactual->transactiondate = $transactiondate;
        $newactual->postedby=\Auth::user()->idno;
        $newactual->save();
        
        return $this->actualdeposit($transactiondate);
        //return redirect(url('actualdeposit',$transactiondate));
        
    }
    
    public function getaccountname($acctcode){
        $coa = \App\ChartOfAccount::where('acctcode',$acctcode)->first();
        return $coa->accountname;
    }
    
    function setuptuitionfee($entrytype,$idno,$schoolyear){
        $entry_type = \App\CtrAutoEntry::where('entry_type',$entrytype)->first();
        
        
        //$tuition = \App\Ledger::select('sum(amount) as amount')->where('accountingcode',$entry_type->indic)->where('idno',$idno)->where('schoolyear',$schoolyear)->first();
        $tuitions = DB::Select("Select sum(amount) as amount from ledgers where accountingcode = ".$entry_type->indic." and idno  = '".$idno."' and schoolyear = '".$schoolyear."'");
        $amount = 0;
        foreach($tuitions as $tuition){
            $amount = $amount + $tuition->amount;
        }
        
        $refno = uniqid(11);
        
        $credit = new \App\Credit;
        $credit->idno =$idno;
        $credit->transactiondate = Carbon::now();
        $credit->refno = $refno;
        $credit->accountingcode = $entry_type->credit;
        $credit->acctcode = $this->getaccountname($entry_type->credit);
        $credit->description = "Tuition Fee";
        $credit->receipt_details = "Tuition Fee Setup";
        $credit->entry_type = 5;
        $credit->amount = $amount;
        $credit->schoolyear = $schoolyear;
        $credit->postedby = \Auth::User()->idno;
        $credit->save();
        
        $debit = new \App\Dedit;
        $debit->idno = $idno;
        $debit->transactiondate = Carbon::now();
        $debit->refno = $refno;
        $debit->paymenttype = 6;
        $debit->entry_type = 5;
        $debit->accountingcode = $entry_type->debit;
        $debit->acctcode = $this->getaccountname($entry_type->debit);
        $debit->description = "Tuition Fee";
        $debit->amount = $amount;
        $debit->postedby = \Auth::User()->idno;
        $debit->schoolyear = $schoolyear;
        $debit->save();
        
        
        return null;
    }
    
   }
