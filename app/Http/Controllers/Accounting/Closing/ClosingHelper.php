<?php

namespace App\Http\Controllers\Accounting\Closing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class ClosingHelper extends Controller
{
    public static function account_entries($fiscalyear,$accountingcode){
        $debits = self::get_debit($fiscalyear,$accountingcode);
        $credits = self::get_credit($fiscalyear,$accountingcode);
        
        $accounts = $debits->merge($credits);
        
        return $accounts;
    }

    
    public static function get_debit($fiscalyear,$accountingcode){
        $debits =  DB::table('dedits')->select(DB::Raw('accountingcode,acct_department,sub_department,checkamount+amount as debit,0 as credit,transactiondate'))->where('fiscalyear',$fiscalyear)->where('isreverse',0)->where('accountingcode',$accountingcode)->get();
        return collect($debits);
    }
    
    public static function get_credit($fiscalyear,$accountingcode){
        $credits = DB::table('credits')->select(DB::Raw('accountingcode,acct_department,sub_department,0 as debit,amount as credit,transactiondate'))->where('fiscalyear',$fiscalyear)->where('isreverse',0)->where('accountingcode',$accountingcode)->get();
        return collect($credits);
    }
    
    static function computeTotal($fiscalyear,$accountingcode,$entry){
        
        $total = 0;
        $accounts = self::account_entries($fiscalyear, $accountingcode);
        
        if($entry == 'debit'){
            $total = $accounts->sum('debit') - $accounts->sum('credit');
        }else{
            $total = $accounts->sum('credit') - $accounts->sum('debit');
        }
        
        return $total;
    }
    
    static function create_credit($refno,$fiscalyear,$accountingcode,$entry_type,$amount){
        if($amount !=0){
            $credit = new \App\Credit();
            $credit->transactiondate = Carbon::now();
            $credit->refno = $refno;
            $credit->receiptno = $refno;
            $credit->categoryswitch = '0';
            $credit->entry_type = $entry_type;
            $credit->accountingcode = $accountingcode;
            $credit->acctcode=  \App\ChartOfAccount::getAccountName($accountingcode);
            $credit->description= \App\ChartOfAccount::getAccountName($accountingcode);
            $credit->receipt_details = \App\ChartOfAccount::getAccountName($accountingcode);
            $credit->amount = $amount;
            $credit->fiscalyear = $fiscalyear;
            $credit->postedby = \Auth::user()->idno;
            $credit->acct_department = 'None';
            $credit->sub_department = 'None';
            $credit->save();   
        }
    }
    
    static function create_debit($refno,$fiscalyear,$accountingcode,$entry_type,$amount){
        if($amount !=0){
            $debit = new \App\Dedit;
            $debit->transactiondate = Carbon::now();
            $debit->entry_type = $entry_type;
            $debit->amount = $amount;
            $debit->accountingcode = $accountingcode;
            $debit->acctcode = \App\ChartOfAccount::getAccountName($accountingcode);
            $debit->description = \App\ChartOfAccount::getAccountName($accountingcode);
            $debit->refno = $refno;
            $debit->fiscalyear = $fiscalyear;
            $debit->postedby = \Auth::user()->idno;
            $debit->acct_department = "None";
            $debit->sub_department = "None";
            $debit->save();   
        }
    }
}
