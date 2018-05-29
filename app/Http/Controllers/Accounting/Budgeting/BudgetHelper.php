<?php

namespace App\Http\Controllers\Accounting\Budgeting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class BudgetHelper extends \App\Http\Controllers\Accounting\IndividualAccountSmmary\Helper
{
    //Get Expenses
    
    public static function records($fiscalyear){
        
        $debits = self::get_debit($fiscalyear);
        $credits = self::get_credit($fiscalyear);
        
        $accounts = $debits->merge($credits);
        
        return $accounts;
    }
    
    public static function expenses_ranged($from,$to,$fiscalyear){
        $accounts = self::expenses($fiscalyear)->filter(function($queries) use($from,$to){
            $transanctiondate=date('Y-m-d', strtotime($queries->transactiondate));;
            $dateBegin = date('Y-m-d', strtotime($from));
            $dateEnd = date('Y-m-d', strtotime($to));
            if (($transanctiondate >= $dateBegin) && ($transanctiondate <= $dateEnd))
            {
              return true;
            }
        });
        
        return $accounts;
    }
    
    public static function get_debit($fiscalyear){
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', 300);
        $debits = DB::table('dedits')->select(DB::Raw('accountingcode,description,acct_department,sub_department,sum(checkamount)+sum(amount) as debit,0 as credit,transactiondate'))->where('fiscalyear',$fiscalyear)->where('isreverse',0)->groupBy('accountingcode','sub_department','description')->get();
        return collect($debits);
    }
    
    public static function get_credit($fiscalyear){
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', 300);
        $credits = DB::table('credits')->select(DB::Raw('accountingcode,description,acct_department,sub_department,0 as debit,sum(amount) as credit,transactiondate'))->where('fiscalyear',$fiscalyear)->where('isreverse',0)->groupBy('accountingcode','sub_department','description')->get();
        return collect($credits);
    }
    
    //END Get Expenses
    
    
    //Compute Accounts
    static function acctTotal($accounts,$type){
        $total = 0;
        if($type == 'debit'){
            $total = $accounts->sum('debit') - $accounts->sum('credit');
        }else{
            $total = $accounts->sum('credit') - $accounts->sum('debit');
        }
        
        return ['amount'=>$total,'display'=>number_format($total,2,'.',',')];
    }
    
    static function groupTotal($records,$accounts){
        $total = 0;
        foreach($accounts as $account){
            $type = $account->entry;
            $amount = self::acctTotal($records->where('accountingcode',$account->acctcode,false),$type);
            $total = $total + $amount['amount'];
        }

        return ['amount'=>$total,'display'=>number_format($total,2,'.',',')];
        
    }
    
    //Compute Subsidy
    
    
    
}
