<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebitMemoController extends Controller
{
    //
     public function __construct()
	{
		$this->middleware('auth');
	}
        
     function viewdebitmemo($idno){
         return view('accounting.viewdebitmemo',compact('idno'));
     }   
     
     function debitcredit(Request $request){
       $totaldue = $request->totaldue;
       $previous = $request->previous;
       $others = $request->others;
       $idno = $request->idno;
       $refno = $request->refno;
       $receiptno=$request->receiptno;
       $entry_type=$request->entry_type;
       $acctcode = $request->acctcodedebit;
       $subsidiary = $request->subsidiary;
       $department = $request->department;
       $debitamount = $request->debitamount;
       
       //Add to credit/debit//
            //main account//
            if($totaldue > 0){
            //$sql = "Select * from ledgers where categoryswitch <= '6' "
            //   . "and idno = '$idno' and amount-payment-plandiscount-otherdiscount-debitmemo > 0 order by duedate, categoryswitch";
            //$this->addcredit($idno, $totaldue, $refno, $receiptno, $entry_type,$sql);
            }
            //previous accounts//
            if($previous > 0){
            //$sql = "Select * from ledgers where categoryswitch >= '10'  and categoryswitch < '19'"
            //   . "and idno = '$idno' and amount-payment-plandiscount-otherdiscount-debitmemo > 0 order by duedate, categoryswitch";
            //$this->addcredit($idno, $previous, $refno, $receiptno, $entry_type,$sql);
            }
            //others//
            $string="";
            
       
       return $request ;     
       
       //Add to accountings//
            //main account//
       
            //previous accounts//
       
            //others//
       
       //update Ledger//
     }
     
     function addcredit($idno, $totalmain,$refno,$receiptno,$entry_type,$sql){
       $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;  
       $unpaids=DB::Select($sql);  
       foreach($unpaids as $unpaid){
           $balance = $unpaid->amount-$unpaid->payment-$unpaid->plandiscount-$unpaid->otherdiscount-$unpaid->debitmo;
           if($totalmain >= $balance){
                $addcredit = new \App\Credit;
                $totaldiscount = $unpaid->plandiscount + $unpad->otherdiscount;
                if($totaldiscount > 0){
                $addcredit->amount = $balance + $totaldiscount;    
                }else{
                $addcredit->amount = $balance;
                }
               
                $updateledger = \App\Ledger::find($unpaid->id);
                $updateledger->debitmemo = $updateledger->debitmo + $balance;
                $updateledger->update();
                
                $totalmain = $totalmain-$balance;
           } else {
               if($totalmain == 0){
                   break;
               }else{
                    $addcredit = new \App\Credit;
                    $addcredit->amount=$totalmain;
                    $updateledger = \App\Ledger::find($unpaid->id);
                    $updateledger->debitmemo = $updateledger->debitmo + $totalmain;
                    $updateledger->update();
                    $totalmain = 0;
               }
           }
           $addcredit->idno=$idno;
           $addcredit->transactiondate = Carbon::now();
           $addcredit->referenceid = $unpaid->id;
           $addcredit->refno = $refno;
           $addcredit->receiptno=$receiptno;
           $addcredit->categoryswitch = $unpaid->categoryswitch;
           $addcredit->accountingcode = $unpaid->accountingcode;
           $addcredit->acctcode = $unpaid->acctcode;
           $addcredit->description = $unpaid->description;
           $addcredit->receipt_details = $unpaid->receipt_details;
           $addcredit->entry_type=$entry_type;
           $addcredit->duedate = $unpaid->duedate;
           $addcredit->schoolyear = $unpaid->schoolyear;
           $addcredit->fiscalyear = $fiscalyear;
           $addcredit->period = $unpaid->period;
           $addcredit->postedby = \Auth::user()->idno;
           $addcredit->acct_department = $unpaid->acct_department;
           $addcredit->sub_department = $unpaid->sub_department; 
           $addcredit->save();
       }
       
     }
     
     function adddiscount($ledgerid,$entry_type,$refno,$receiptno,$plandiscount,$otherdiscount){
         $ledger = \App\Ledger::find($ledgerid);
         $student = \App\User::where('idno',$ledger->idno)->first();
         $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
         if($plandiscount > 0 ){
             $adddebitdiscount = new \App\Dedit;
             $adddebitdiscount->idno = $ledger->idno;
             $adddebitdiscount->transactiondate = Carbon::now();
             $adddebitdiscount->refno = $refno;
             $adddebitdiscount->paymenttype = "3";
             $adddebitdiscount->receiptno = $receiptno;
             $adddebitdiscount->entry_type=$entry_type;
             $adddebitdiscount->accountingcode = "410100";
             $adddebitdiscount->acctcode = "Cash-Semi payment discount";
             $adddebitdiscount->description = $ledger->description;
             $adddebitdiscount->amount = $plandiscount;
             $adddebitdiscount->receivefrom = $student->firstname . " " . $student->lastname;
             $adddebitdiscount->acct_department = $ledger->acct_department;
             $adddebitdiscount->sub_department = $ledger->sub_department;
             $adddebitdiscount->postedby = \Auth::user()->idno;
             $adddebitdiscount->schoolyear = $ledger->schoolyear;
             $adddebitdiscount->fiscalyear = $fiscalyear;
             $adddebitdiscount->save();
         }
         if($otherdiscount>0){
             $discountcode = \App\CtrDiscount::where('discountcode',$ledger->discountcode)->first();
             $accountingcode="410100";
             $accountingname="Cash-Semi payment discount";
             if(count($discountcode)>0){
                 $accountingcode = $discountcode->accountingcode;
                 $accountingname = $discountcode->accountingname;
             }
             $adddebitdiscount = new \App\Dedit;
             $adddebitdiscount->idno = $ledger->idno;
             $adddebitdiscount->transactiondate = Carbon::now();
             $adddebitdiscount->refno = $refno;
             $adddebitdiscount->paymenttype = "3";
             $adddebitdiscount->receiptno = $receiptno;
             $adddebitdiscount->entry_type=$entry_type;
             $adddebitdiscount->accountingcode = $accountingcode;
             $adddebitdiscount->acctcode = $accountingname;
             $adddebitdiscount->description = $ledger->description;
             $adddebitdiscount->amount = $otherdiscount;
             $adddebitdiscount->receivefrom = $student->firstname . " " . $student->lastname;
             $adddebitdiscount->acct_department = $ledger->acct_department;
             $adddebitdiscount->sub_department = $ledger->sub_department;
             $adddebitdiscount->postedby = \Auth::user()->idno;
             $adddebitdiscount->schoolyear = $ledger->schoolyear;
             $adddebitdiscount->fiscalyear = $fiscalyear;
             $adddebitdiscount->save();
         }
         
     }
     function adddebitmain(){
     
         
     }
         
     
}
