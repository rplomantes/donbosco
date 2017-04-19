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
       $others=[];
       if(isset($request->others)){
       $others = $request->others;
       }
       $idno = $request->idno;
       $refno = $request->refno;
       $debitmemono=$request->debitmemono;
       $entry_type=$request->entry_type;
       $totalamount = $request->totalamount;
       $remarks = $request->remarks;
       $student=  \App\User::where('idno',$idno)->first();
       $fullname = strtoupper($student->lastname) . ", " . strtoupper($student->firstname);
       $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
       
       $this->adddebitmemo($idno, $fullname, $refno, $debitmemono, $totalamount, $remarks);
       $this->adddebitmain($refno, $idno, $fullname, $remarks);
       //Add to credit-debit//
            //main account//
            if($totaldue > 0){
            $sql = "Select * from ledgers where categoryswitch <= '6' "
               . "and idno = '$idno' and amount-payment-plandiscount-otherdiscount-debitmemo > 0 order by duedate, categoryswitch";
            $this->addcredit($idno, $totaldue, $refno, $debitmemono, $entry_type, $sql);
            }
            //previous accounts//
            if($previous > 0){
            $sql = "Select * from ledgers where categoryswitch >= '10'  and categoryswitch < '19'"
               . "and idno = '$idno' and amount-payment-plandiscount-otherdiscount-debitmemo > 0 order by duedate, categoryswitch";
            $this->addcredit($idno, $previous, $refno, $debitmemono, $entry_type,$sql);
            }
            //others//
            if(isset($request->others)){
                foreach ($others as $key => $value) {
                  if($value > 0) { 
                    $updateledger = \App\Ledger::find($key);
                    $updateledger->debitmemo = $value;
                    $updateledger->update();
                    $addcredit = new \App\Credit;
                    $addacctg = new \App\Accounting;
                    
                    $addcredit->idno=$idno; 
                    $addacctg->idno = $idno;
                    
                    $addcredit->amount=$value;
                    $addacctg->credit=$value;
                    
                    $addcredit->transactiondate = Carbon::now();
                    $addacctg->transactiondate = Carbon::now();
                    
                    $addcredit->referenceid = $key;
                    $addacctg->ledgerid = $key;
                    
                    $addcredit->refno = $refno;
                    $addacctg->refno = $refno;
                    
                    $addcredit->receiptno=$debitmemono;
                    $addacctg->referenceid=$debitmemono;
                    
                    $addcredit->categoryswitch = $updateledger->categoryswitch;
                    $addacctg->categoryswitch = $updateledger->categoryswitch;
                    
                    $addcredit->accountingcode = $updateledger->accountingcode;
                    $addacctg->accountcode = $updateledger->accountingcode;
                    
                    $addcredit->acctcode = $updateledger->acctcode;
                    $addacctg->accountname = $updateledger->acctcode;
                    
                    $addcredit->description = $updateledger->description;
                    $addacctg->subsidiary=$updateledger->description;
                    
                    $addcredit->receipt_details = $updateledger->receipt_details;
                    $addacctg->receipt_details = $updateledger->receipt_details;
                    
                    $addcredit->entry_type=$entry_type;
                    $addacctg->type=$entry_type;
                    
                    $addcredit->duedate = $updateledger->duedate;
                    $addacctg->duedate = $updateledger->duedate;
                    
                    $addcredit->schoolyear = $updateledger->schoolyear;
                    $addacctg->schoolyear = $updateledger->schoolyear;
                    
                    $addcredit->fiscalyear = $fiscalyear;
                    $addacctg->fiscalyear = $fiscalyear;
                    
                    $addcredit->period = $updateledger->period;
                    $addacctg->period = $updateledger->period;
                    
                    $addcredit->postedby = \Auth::user()->idno;
                    $addacctg->posted_by = \Auth::user()->idno;
                    
                    $addcredit->acct_department = $updateledger->acct_department;
                    $addacctg->acct_department = $updateledger->acct_department;
                    
                    $addcredit->sub_department = $updateledger->sub_department;
                    $addacctg->sub_department = $updateledger->sub_department;
                    $addacctg->isfinal="1";
                    
                    $addcredit->save();
                    $addacctg->save();
                  } 
                }
                
            }
            //Increment voucher number //
            $udatedmno = \App\User::where('idno',\Auth::user()->idno)->first();
            $udatedmno->debitmemono = $udatedmno->debitmemono + 1;
            $udatedmno->update();
                
           //return "Done";
            //return view("accounting.",compact('refno','idno')); 
             return view('accounting.viewdm',compact('refno','idno'));
     }
     
     function addcredit($idno, $totalmain,$refno,$receiptno,$entry_type,$sql){
       $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;  
       $unpaids=DB::Select($sql);  
       foreach($unpaids as $unpaid){
           $balance = $unpaid->amount-$unpaid->payment-$unpaid->plandiscount-$unpaid->otherdiscount-$unpaid->debitmemo;
           if($totalmain >= $balance){
                $addcredit = new \App\Credit;
                $addacctg = new \App\Accounting;
                $totaldiscount = $unpaid->plandiscount + $unpaid->otherdiscount;
                    if($totaldiscount > 0){
                        $addcredit->amount = $balance + $totaldiscount; 
                        $addacctg->credit =$balance + $totaldiscount;
                        $this->adddiscount($unpaid->id, $entry_type, $refno, $receiptno, $unpaid->plandiscount, $unpaid->otherdiscount);
                    }else{
                        $addcredit->amount = $balance;
                        $addacctg->credit = $balance;
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
                    $addacctg = new \App\Accounting;
                    $addcredit->amount=$totalmain;
                    $addacctg->credit=$totalmain;
                    $updateledger = \App\Ledger::find($unpaid->id);
                    $updateledger->debitmemo = $updateledger->debitmo + $totalmain;
                    $updateledger->update();
                    $totalmain = 0;
               }
           }
           $addcredit->idno=$idno;
           $addacctg->idno =$idno;
           
           $addcredit->transactiondate = Carbon::now();
           $addacctg->transactiondate = Carbon::now();
           
           $addcredit->referenceid = $unpaid->id;
           $addacctg->ledgerid=$unpaid->id;
           
           $addcredit->refno = $refno;
           $addacctg->refno = $refno;
           
           $addcredit->receiptno=$receiptno;
           $addacctg->referenceid=$receiptno;
           
           $addcredit->categoryswitch = $unpaid->categoryswitch;
           $addacctg->categoryswitch = $unpaid->categoryswitch;
           
           $addcredit->accountingcode = $unpaid->accountingcode;
           $addacctg->accountcode = $unpaid->accountingcode;
           
           $addcredit->acctcode = $unpaid->acctcode;
           $addacctg->accountname=$unpaid->acctcode;
           
           $addcredit->description = $unpaid->description;
           $addacctg->subsidiary = $unpaid->description;
           
           $addcredit->receipt_details = $unpaid->receipt_details;
           $addacctg->receipt_details =$unpaid->receipt_details;
           
           $addcredit->entry_type=$entry_type;
           $addacctg->type=$entry_type;
           
           $addcredit->duedate = $unpaid->duedate;
           $addacctg->duedate =$unpaid->duedate;
           
           $addcredit->schoolyear = $unpaid->schoolyear;
           $addacctg->schoolyear = $unpaid->schoolyear;
           
           $addcredit->fiscalyear = $fiscalyear;
           $addacctg->fiscalyear =$fiscalyear;
           
           $addcredit->period = $unpaid->period;
           $addacctg->period =$unpaid->period;
           
           $addcredit->postedby = \Auth::user()->idno;
           $addacctg->posted_by = \Auth::user()->idno;
           
           $addcredit->acct_department = $unpaid->acct_department;
           $addacctg->acct_department = $unpaid->acct_department;
           
           $addcredit->sub_department = $unpaid->sub_department; 
           $addacctg->sub_department = $unpaid->sub_department;
           $addacctg->isfinal="1";
           
           $addcredit->save();
           $addacctg->save();
       }
       
     }
     
     function adddiscount($ledgerid,$entry_type,$refno,$receiptno,$plandiscount,$otherdiscount){
         $ledger = \App\Ledger::find($ledgerid);
         $student = \App\User::where('idno',$ledger->idno)->first();
         $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
         if($plandiscount > 0 ){
             $adddebitdiscount = new \App\Dedit;
             $addacctg = new \App\Accounting;
             
             $adddebitdiscount->idno = $ledger->idno;
             $addacctg->idno =$ledger->idno;
             
             $adddebitdiscount->transactiondate = Carbon::now();
             $addacctg->transactiondate = Carbon::now();
             
             $adddebitdiscount->refno = $refno;
             $addacctg->refno = $refno;
             
             $adddebitdiscount->paymenttype = "3";
             $adddebitdiscount->receiptno = $receiptno;
             $addacctg->referenceid = $receiptno;
             
             $adddebitdiscount->entry_type=$entry_type;
             $addacctg->type=$entry_type;
             
             $adddebitdiscount->accountingcode = "410100";
             $addacctg->accountcode = "410100";
             
             $adddebitdiscount->acctcode = "Cash-Semi payment discount";
             $addacctg->accountname = "Cash-Semi payment discount";
             
             $adddebitdiscount->description = $ledger->description;
             $addacctg->subsidiary = $ledger->description;
             
             $adddebitdiscount->amount = $plandiscount;
             $addacctg->debit = $plandiscount;
             
             
             $adddebitdiscount->receivefrom = $student->firstname . " " . $student->lastname;
             $adddebitdiscount->acct_department = $ledger->acct_department;
             $addacctg->acct_department = $ledger->acct_department;
             
             $adddebitdiscount->sub_department = $ledger->sub_department;
             $addacctg->sub_department = $ledger->sub_department;
             
             $adddebitdiscount->postedby = \Auth::user()->idno;
             $addacctg->posted_by = \Auth::user()->idno;
             
             $adddebitdiscount->schoolyear = $ledger->schoolyear;
             $addacctg->schoolyear = $ledger->schoolyear;
             
             $adddebitdiscount->period = $ledger->period;
             $addacctg->period = $ledger->period;
             
             $adddebitdiscount->fiscalyear = $fiscalyear;
             $addacctg->fiscalyear = $fiscalyear;
             $addacctg->isfinal="0";
             $adddebitdiscount->save();
             $addacctg->save();
         }
         if($otherdiscount>0){
             $discountcode = \App\CtrDiscount::where('discountcode',$ledger->discountcode)->first();
             $accountingcode="410100";
             $accountingname="Cash-Semi payment discount";
             if(count($discountcode)>0){
                 $accountingcode = $discountcode->accountingcode;
                 $accountingname = $discountcode->acctname;
             }
             $adddebitdiscount = new \App\Dedit;
             $addacctg= new \App\Accounting;
             
             $adddebitdiscount->idno = $ledger->idno;
             $addacctg->idno = $ledger->idno;
                     
             $adddebitdiscount->transactiondate = Carbon::now();
             $addacctg->transactiondate = Carbon::now();
             
             $adddebitdiscount->refno = $refno;
             $addacctg->refno = $refno;
             
             $adddebitdiscount->paymenttype = "3";
             $adddebitdiscount->receiptno = $receiptno;
             $addacctg->referenceid = $receiptno;
             
             $adddebitdiscount->entry_type=$entry_type;
             $addacctg->type = $entry_type;
             
             $addacctg->isfinal="1";
             
             $adddebitdiscount->accountingcode = $accountingcode;
             $addacctg->accountcode = $accountingcode;
             
             $adddebitdiscount->acctcode = $accountingname;
             $addacctg->accountname = $accountingname;
             
             $adddebitdiscount->description = $ledger->description;
             $addacctg->subsidiary = $ledger->description;
             
             $adddebitdiscount->amount = $otherdiscount;
             $addacctg->debit = $otherdiscount;
             
             $adddebitdiscount->receivefrom = $student->firstname . " " . $student->lastname;
             $adddebitdiscount->acct_department = $ledger->acct_department;
             $addacctg->acct_department = $ledger->acct_department;
             
             $adddebitdiscount->sub_department = $ledger->sub_department;
             $addacctg->sub_department = $ledger->sub_department;
             
             $adddebitdiscount->postedby = \Auth::user()->idno;
             $addacctg->posted_by = \Auth::user()->idno;
             
             $adddebitdiscount->schoolyear = $ledger->schoolyear;
             $addacctg->schoolyear = $ledger->schoolyear;
             
             $adddebitdiscount->period = $ledger->period;
             $addacctg->period = $ledger->period;
             
             $adddebitdiscount->fiscalyear = $fiscalyear;
             $addacctg->fiscalyear = $fiscalyear;
             
             $adddebitdiscount->save();
             $addacctg->save();
         }
         
     }
     function adddebitmain($refno, $idno, $fullname, $remarks){
     $dmaccounting = \App\Accounting::where('refno',$refno)->get();
     foreach($dmaccounting as $dmacct){
     $dmacct->idno = $idno;
     $dmacct->isfinal = "1";
     $dmacct->update();
     
     $addtodebit = new \App\Dedit;
     $addtodebit->idno = $idno;
     $addtodebit->transactiondate = Carbon::now();
     $addtodebit->refno = $dmacct->refno;
     $addtodebit->receiptno = $dmacct->referenceid;
     $addtodebit->accountingcode = $dmacct->accountcode;
     $addtodebit->acctcode = $dmacct->accountname;
     $addtodebit->description =$dmacct->subsidiary;
     $addtodebit->entry_type=$dmacct->type;
     $addtodebit->paymenttype="3";
     $addtodebit->amount=$dmacct->debit;
     $addtodebit->receivefrom = $fullname;
     $addtodebit->acct_department = $dmacct->acct_department;
     $addtodebit->sub_department = $dmacct->sub_department;
     $addtodebit->postedby = \Auth::user()->idno;
     $addtodebit->schoolyear = $dmacct->schoolyear;
     $addtodebit->fiscalyear = $dmacct->fiscalyear;
     $addtodebit->remarks = $remarks;
     $addtodebit->save();
     
         
     }
     
     }
         
     function adddebitmemo($idno,$fullname,$refno,$voucherno,$totalamount,$remarks){
        
         $adddm = new \App\DebitMemo;
         $adddm->fullname = $fullname;
         $adddm->idno = $idno;
         $adddm->refno = $refno;
         $adddm->voucherno = $voucherno;
         $adddm->transactiondate=Carbon::now();
         $adddm->amount=$totalamount;
         $adddm->remarks=$remarks;
         $adddm->postedby=\Auth::user()->idno;
         $adddm->save();        
     }
     
     function dmcmreport($trandate){
         return view('accounting.dmcmreport',compact('trandate'));
     }
     
     function dmcmallreport($fromtran,$totran){
         return view('accounting.dmcmallreport',compact('fromtran','totran'));
     }
     
     function viewdm($refno,$idno){
         return view('accounting.viewdm',compact('refno','idno'));
     }
     
     function printdm($refno){
       $pdf = \App::make('dompdf.wrapper');
       $pdf->loadView("print.printdm",compact('refno'));
       return $pdf->stream();
     }
     
     function restorecanceldm($iscancel,$refno){
         $idno = \App\DebitMemo::where('refno',$refno)->first()->idno;
         $credits =  \App\Credit::where("refno",$refno)->get();
         
         if($iscancel == "Cancel"){
            foreach($credits as $credit){
                $record =  \App\Ledger::find($credit->referenceid);
                    if($record->amount-$record->payment-$record->plandiscount-$record->otherdiscount-$record->debitmemo == 0){
                        $reverseamount = $credit->amount - $record->plandiscount - $record->otherdiscount;  
                    } else {
                        $reverseamount = $credit->amount;  
                    }
                $record->debitmemo = $record->debitmemo - $reverseamount; 
                $record->update();
            }
                \App\Credit::where('refno',$refno)->update(["isreverse"=>'1']);
                \App\Dedit::where('refno',$refno)->update(["isreverse"=>'1']);
                \App\Accounting::where('refno',$refno)->update(["isreversed"=>"1"]);
                \App\DebitMemo::where('refno',$refno)->update(["isreverse"=>"1"]);
         }
         else{
             
            foreach($credits as $credit){
                $record=  \App\Ledger::find($credit->referenceid);
                $amountdiff = $record->amount - $record->payment - $record->plandiscount- $record->otherdiscount- $record->debitmemo;
                if($amountdiff < $credit->amount){
                    $record->debitmemo = $record->debitmemo + $amountdiff;
                } else {
                    $record->debitmemo = $record->debitmemo + $credit->amount;
                }
             $record->update();   
            }
             \App\Credit::where('refno',$refno)->update(["isreverse"=>'0']);
             \App\Dedit::where('refno',$refno)->update(["isreverse"=>'0']);
             \App\Accounting::where('refno',$refno)->update(["isreversed"=>"0"]);
             \App\DebitMemo::where('refno',$refno)->update(["isreverse"=>"0"]);
         }
         
        return view('accounting.viewdm',compact('refno','idno'));
     }
}
