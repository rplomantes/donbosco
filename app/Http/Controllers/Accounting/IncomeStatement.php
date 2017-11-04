<?php
/*
 * |-----------------------------------------------------------------------------|
 * |Statement Income Report                                                      |
 * |-----------------------------------------------------------------------------|
 *  
 */
namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Accounting\Helper as AcctHelper;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class IncomeStatement extends Controller
{
    //Initialize the report. Range is set 
    function index($date){
        $fiscal = AcctHelper::getfiscalyear($date);
        $from = AcctHelper::fiscalBeginning($fiscal);
        $accountGroups = \App\CtrAccountsGroup::get();
        $incomeGroups = $accountGroups->where('type',4);
        $expenseGroups = $accountGroups->where('type',5);
        $otherGroups = $accountGroups->where('type',6);
        return view('accounting.incomeStatement.incomeStatement',compact('date','accountGroups','incomeGroups','expenseGroups','otherGroups','from'));
    }
    //Print Report
    function printview($date){
        $fiscal = AcctHelper::getfiscalyear($date);
        $from = AcctHelper::fiscalBeginning($fiscal);
        $accountGroups = \App\CtrAccountsGroup::get();
        $incomeGroups = $accountGroups->where('type',4);
        $expenseGroups = $accountGroups->where('type',5);
        $otherGroups = $accountGroups->where('type',6);
        
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper('letter','portrait');
       $pdf->loadView('accounting.incomeStatement.incomeStatementPDF',compact('date','accountGroups','incomeGroups','expenseGroups','otherGroups','from'));
       return $pdf->stream();
       //return view('accounting.incomeStatement.incomeStatementPDF',compact('date','accountGroups','incomeGroups','expenseGroups','otherGroups','from'));

    }
    
    /*Called function to view to give out the totol amount of the account not the best
     * yet but where gettting there.
     */
    static function accountTotal($account,$entry,$todate){
        $fiscalyear = Helper::getfiscalyear($todate);
        $fromdate  = Helper::fiscalBeginning($fiscalyear);
        
        $creditamount = self::creditAmount($account,$todate,$fromdate);
        $debitamount = self::debitAmount($account,$todate,$fromdate);
        
        return AcctHelper::getaccttotal($creditamount,$debitamount,$entry);
    }
    
    //Generate total of all the credit transactions of account used by accountTotal
    static function creditAmount($account,$todate,$fromdate){
        return \App\Credit::where('accountingcode',$account)->whereBetween('transactiondate',array($fromdate,$todate))->where('isreverse',0)->where('entry_type','!=',7)->sum('amount');
    }
    
    //Generate total of all the debit transactions of account used by accountTotal
    static function debitAmount($account,$todate,$fromdate){
        $total = 0;
        $debitamount =\App\Dedit::where('accountingcode',$account)->whereBetween('transactiondate',array($fromdate,$todate))->where('isreverse',0)->where('entry_type','!=',7)->orderBy('amount','ASC')->get();
        
        foreach($debitamount as $amount){
            $total = $total + $amount->amount + $amount->checkamount;
        }
        
        return $total;
    }
    
    //Return the total amount of accounts per group in Income groups
    static function incomeGroupTotal($credits,$debits,$lesses,$date){
        $totalcredits = 0;
        $totaldebits = 0;
        $totallesses = 0;
        
        foreach($credits as $credit){
           $totalcredits = $totalcredits + round(self::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2);
        }
        
        foreach($debits as $debit){
           $totaldebits = $totaldebits + round(self::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2);
        }
        
        foreach($lesses as $less){
            $totallesses = $totallesses + round(self::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date),2);
        }
        
        return $totalcredits - ($totaldebits+$totallesses);
    }
    
    //Return the total amount of accounts per group in Other groups including expense
    static function otherGroupTotal($debits,$date){
        $totaldebits = 0;
        
        foreach($debits as $debit){
           $totaldebits = $totaldebits + round(self::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2);
        }
        
        return $totaldebits;
    }
}
