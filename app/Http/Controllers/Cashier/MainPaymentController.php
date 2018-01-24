<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Cashier\ReceiptController;

class MainPaymentController extends Controller
{
    function payment(Request $request){
        $account=null;
        $orno = $this->getOR();
        $refno = $this->getRefno();
        $discount = 0;
        $plandiscount = 0;
        $otherdiscount = array();
        $change = 0;

        $this->reset_or();
        if($request->totaldue > 0 ){
        $totaldue = $request->totaldue;   
        $curr_duedates = array();
            $accounts = DB::SELECT("select * from ledgers where idno = '".$request->idno."' and categoryswitch <= '6' "
                 ." and (amount - payment - debitmemo - plandiscount - otherdiscount) > 0 order By duedate, categorySwitch");
                foreach($accounts as $account){
                    
                    $balance = $account->amount - $account->payment - $account->plandiscount - $account->otherdiscount - $account->debitmemo;

                        if($balance < $totaldue){
                            $discount = $discount + $account->plandiscount + $account->otherdiscount;
                            if($account->plandiscount > 0){
                                $plandiscount = $plandiscount + $account->plandiscount;
                            }
                            if($account->otherdiscount > 0){
                                if(array_key_exists($account->discountcode, $otherdiscount)){
                                    $acctdisc = $otherdiscount [$account->discountcode];
                                }else{
                                    $acctdisc = 0;
                                }
                                $otherdiscount [$account->discountcode]= $acctdisc + $account->otherdiscount;
                            }
                            
                            
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
                                if($account->plandiscount > 0){
                                    $plandiscount = $plandiscount + $account->plandiscount;
                                }
                                if($account->otherdiscount > 0){
                                    if(array_key_exists($account->discountcode, $otherdiscount)){
                                        $acctdisc = $otherdiscount [$account->discountcode];
                                    }else{
                                        $acctdisc = 0;
                                    }
                                    $otherdiscount [$account->discountcode]= $acctdisc + $account->otherdiscount;
                                }
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
        
        $prevdiscount = 0;
        
        if($request->previous > 0 ){
            $previous = $request->previous;
            $updateprevious = DB::SELECT("select * from ledgers where idno = '".$request->idno."' and categoryswitch >= '11' "
                     . " and amount - payment - debitmemo - plandiscount - otherdiscount > 0 order By categoryswitch");
                foreach($updateprevious as $up){
                $balance = $up->amount - $up->payment - $up->plandiscount - $up->otherdiscount - $up->debitmemo;
                    if($balance < $previous ){
                        $prevdiscount = $prevdiscount + $up->plandiscount + $up->otherdiscount;
                        $updatepay = \App\Ledger::where('id',$up->id)->first();
                        $updatepay->payment = $updatepay->payment + $balance;
                        $updatepay->save();
                        $previous = $previous - $balance;
                        $this->credit($request->idno, $up->id, $refno, $orno, $up->amount-$up->payment-$up->debitmemo);
                    } else {
                        $updatepay = \App\Ledger::where('id',$up->id)->first();
                        $updatepay->payment = $updatepay->payment + $previous;
                        $updatepay->save();
                        if($previous==$balance){
                            $prevdiscount = $prevdiscount + $up->plandiscount + $up->otherdiscount;    
                            $this->credit($request->idno, $up->id, $refno, $orno, $up->amount - $up->payment - $up->debitmemo);
                        }else{      
                            $this->credit($request->idno, $up->id, $refno, $orno, $previous);
                        }

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
                $creditstudentdeposit->acct_department = 'None';
                $creditstudentdeposit->sub_department = 'None';
                $creditstudentdeposit->save(); 
                
                $hasdeposit = \App\StudentDeposit::where('id',$request->idno)->exists();
                if($hasdeposit){
                    $deposit = \App\StudentDeposit::where('id',$request->idno)->first();
                    $deposit->amount = $deposit->amount + $remainingbalance; 
                }else{
                    $deposit = new \App\StudentDeposit;
                    $deposit->amount = $remainingbalance;
                    $deposit->idno = $request->idno;
                    $deposit->refno = $refno;
                    $deposit->transactiondate = Carbon::now();
                    $deposit->postedby = \Auth::user()->idno;
                }
                $deposit->save();
            }
        }

        if($request->deposit > 0){
            $this->debit_reservation_discount($refno, $orno,$request->idno,8, $request->deposit,'Student Deposit');
            $this->reduce_deposit($request->deposit,$request->idno);
        }

        $bank_branch = "";
        $check_number = "";

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

      if($plandiscount > 0){
          $this->debit_reservation_discount($refno, $orno,$request->idno,env('DEBIT_DISCOUNT') , $plandiscount, "Plan Discount");
      }
      
      if(count($otherdiscount) > 0){
          foreach($otherdiscount as $key =>$amount){
            $this->debit_reservation_discount($refno, $orno,$request->idno,env('DEBIT_DISCOUNT') , $amount, $key);    
          }
      }
       return ReceiptController::viewreceipt($refno, $request->idno);
   }
}
