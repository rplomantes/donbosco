<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Controllers\Cashier\CashierController;
use Carbon\Carbon;
class OtherPaymentControll extends Controller
{
    function nonstudent(){

        $acct_departments = DB::Select('Select * from ctr_acct_dept order by sub_department');
        return view('cashier.nonstudent', compact('acct_departments'));
        
    }
    

    
    function postnonstudent(Request $request){
       $fiscal = \App\CtrFiscalyear::first();
       $refno = CashierController::getRefno();
       $or = CashierController::getOR(); 
       $payee = strtoupper(str_replace(' ', '', $request->name));
        $newpayee = new \App\NonStudent;
        $newpayee->idno = uniqid();
        $newpayee->fullname = $request->name;
        $newpayee->save();
        
        $idno = $newpayee->idno;
        $name = $newpayee->fullname;
       
       CashierController::reset_or();
       
        if($request->amount1 > 0){
            
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount1);
            $creditreservation->acctcode=$request->groupaccount1;
            if(isset($request->particular1) && ($request->particular1 != "" || $request->particular1 != NULL)){
                $creditreservation->description=$request->particular1;
                $creditreservation->receipt_details=$request->particular1;
            }else{
                $creditreservation->description=$request->groupaccount1;
                $creditreservation->receipt_details=$request->groupaccount1;
            }
            $creditreservation->amount = $request->amount1;
            $creditreservation->entry_type='1';
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department1);
            $creditreservation->sub_department = $request->acct_department1;
            $creditreservation->save(); 
            
        }
        
           if($request->amount2 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount2);
            $creditreservation->acctcode=$request->groupaccount2;
            if(isset($request->particular2) && ($request->particular2 != "" || $request->particular2 != NULL)){
                $creditreservation->description=$request->particular2;
                $creditreservation->receipt_details=$request->particular2;
            }else{
                $creditreservation->description=$request->groupaccount2;
                $creditreservation->receipt_details=$request->groupaccount2;
            }
            $creditreservation->amount = $request->amount2;
            $creditreservation->postedby = \Auth::user()->idno;
            
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department2);
            $creditreservation->sub_department = $request->acct_department2;
            $creditreservation->save(); 
            
        }
        
         if($request->amount3 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount3);
            $creditreservation->acctcode=$request->groupaccount3;
            if(isset($request->particular3) && ($request->particular3 != "" || $request->particular3 != NULL)){
                $creditreservation->description=$request->particular3;
                $creditreservation->receipt_details=$request->particular3;
            }else{
                $creditreservation->description=$request->groupaccount3;
                $creditreservation->receipt_details=$request->groupaccount3;
            }
            $creditreservation->amount = $request->amount3;
            $creditreservation->postedby = \Auth::user()->idno;
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department3);
            $creditreservation->sub_department = $request->acct_department3;
            $creditreservation->save(); 
            
        }
         if($request->amount4 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount4);
            $creditreservation->acctcode=$request->groupaccount4;
            if(isset($request->particular4) && ($request->particular4 != "" || $request->particular4 != NULL)){
                $creditreservation->description=$request->particular4;
                $creditreservation->receipt_details=$request->particular4;
            }else{
                $creditreservation->description=$request->groupaccount4;
                $creditreservation->receipt_details=$request->groupaccount4;
            }
            $creditreservation->amount = $request->amount4;
            $creditreservation->postedby = \Auth::user()->idno;
            
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department4);
            $creditreservation->sub_department = $request->acct_department4;
            $creditreservation->save(); 
            
        }
        
         if($request->amount5 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount5);
            $creditreservation->acctcode=$request->groupaccount5;
            if(isset($request->particular5) && ($request->particular5 != "" || $request->particular5 != NULL)){
                $creditreservation->description=$request->particular5;
                $creditreservation->receipt_details=$request->particular5;
            }else{
                $creditreservation->description=$request->groupaccount5;
                $creditreservation->receipt_details=$request->groupaccount5;
            }
            $creditreservation->amount = $request->amount5;
            $creditreservation->postedby = \Auth::user()->idno;
            
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department5);
            $creditreservation->sub_department = $request->acct_department5;
            $creditreservation->save(); 
            
        }
        
         if($request->amount6 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount6);
            $creditreservation->acctcode=$request->groupaccount6;
            if(isset($request->particular6) && ($request->particular6 != "" || $request->particular6 != NULL)){
                $creditreservation->description=$request->particular6;
                $creditreservation->receipt_details=$request->particular6;
            }else{
                $creditreservation->description=$request->groupaccount6;
                $creditreservation->receipt_details=$request->groupaccount6;
            }
            $creditreservation->amount = $request->amount6;
            $creditreservation->postedby = \Auth::user()->idno;
            
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department6);
            $creditreservation->sub_department = $request->acct_department6;
            $creditreservation->save(); 
        }
        
         if($request->amount7 > 0){
            $creditreservation = new \App\Credit;
            $creditreservation->idno = $idno;
            $creditreservation->transactiondate = Carbon::now();
            $creditreservation->refno = $refno;
            $creditreservation->receiptno = $or;
            $creditreservation->categoryswitch = '9';
            $creditreservation->entry_type='1';
            $creditreservation->accountingcode=CashierController::getaccountingcode($request->groupaccount7);
            $creditreservation->acctcode=$request->groupaccount7;
            if(isset($request->particular7) && ($request->particular7 != "" || $request->particular7 != NULL)){
                $creditreservation->description=$request->particular7;
                $creditreservation->receipt_details=$request->particular7;
            }else{
                $creditreservation->description=$request->groupaccount7;
                $creditreservation->receipt_details=$request->groupaccount7;
            }
            $creditreservation->amount = $request->amount7;
            $creditreservation->postedby = \Auth::user()->idno;
            
            $creditreservation->fiscalyear=$fiscal->fiscalyear;
            $creditreservation->acct_department = CashierController::mainDepartment($request->acct_department7);
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
            case 'China Bank 2':
                $acctcode = 'CBC-SA 149-093601-3';
                $accountingcode = \App\ChartOfAccount::where('accountname','CBC-SA 149-093601-3')->first();
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
        $debit->idno = $idno;
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
        $debit->receivefrom=$name;
        $debit->depositto=$request->depositto;
        $debit->remarks=$request->remarks;
        
        $debit->fiscalyear=$fiscal->fiscalyear;
        $debit->acct_department = "None";
        $debit->sub_department = "None";
        $debit->postedby= \Auth::user()->idno;
        $debit->save();
        
        return CashierController::viewreceipt($refno, $idno);
        
    }
}
