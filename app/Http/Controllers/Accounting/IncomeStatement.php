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
        $accountGroups = \App\CtrAccountsGroup::get();
        return view('accounting.incomeStatement',compact('date','accountGroups'));
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
    
    //Generate total of all the credit transactions of account
    static function creditAmount($account,$todate,$fromdate){
        return \App\Credit::where('accountingcode',$account)->whereBetween('transactiondate',array($fromdate,$todate))->where('isreverse',0)->sum('amount');
    }
    
    //Generate total of all the debit transactions of account
    static function debitAmount($account,$todate,$fromdate){
        $total = 0;
        $debitamount =\App\Dedit::where('accountingcode',$account)->whereBetween('transactiondate',array($fromdate,$todate))->where('isreverse',0)->orderBy('amount','ASC')->get();
        
        foreach($debitamount as $amount){
            $total = $total + $amount->amount + $amount->checkamount;
        }
        
        return $total;
    }
}
