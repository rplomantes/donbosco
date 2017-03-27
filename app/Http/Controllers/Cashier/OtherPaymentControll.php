<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class OtherPaymentControll extends Controller
{
    function nonstudent(){
       
        $accounttypes = DB::Select("select distinct accounttype,acctcode from ctr_other_payments");
        return view('cashier.nonstudent', compact('accounttypes'));
        
    }
    
    function postnonstudent(Request $request){
       $fiscal = \App\CtrFiscalyear::first()->fiscalyear;
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
            $creditreservation->fiscalyear = $fiscal;
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
            $creditreservation->fiscalyear = $fiscal;
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
            $creditreservation->fiscalyear = $fiscal;
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
            $creditreservation->fiscalyear = $fiscal;
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
        $debit->fiscalyear = $fiscal;
        $debit->checkamount=$request->check;
        $debit->receiveamount = $request->cash;
        $debit->receivefrom=$name;
        $debit->depositto=$request->depositto;
        $debit->postedby= \Auth::user()->idno;
        $debit->save();
        
        return $this->viewreceipt($refno, $idno);
        
    }
}
