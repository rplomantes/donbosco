<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class SubAccountSummarryController extends Controller
{
    public function __construct(){
		$this->middleware('auth');
    }
    
    function index($fromdate,$todate){
        if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')){
            $acctcodes = \App\ChartOfAccount::orderBy('accountname','ASC')->get();
            
            return view('accounting.subAccountSummary',compact('acctcodes','fromdate','todate'));
        }
    }
    
    static function getaccounts($fromdate,$todate,$account){
        $accounts = DB::Select("select description,refno,transactiondate,receiptno,totalamount,entry_type,acct_department,sub_department from "
                . "(select c.description,c.refno,c.transactiondate,c.receiptno,sum(c.amount) as totalamount,c.entry_type,c.acct_department,c.sub_department "
                . "from credits c "
                . "where (c.transactiondate BETWEEN '$fromdate' AND '$todate') "
                . "AND c.accountingcode = '$account' and c. isreverse=0 "
                . "group by c.refno,c.description "
                . "UNION "
                . "select description,refno,transactiondate,receiptno,sum(amount)+sum(checkamount) as totalamount,entry_type,acct_department,sub_department "
                . "from dedits "
                . "where (transactiondate BETWEEN '$fromdate' AND '$todate' ) "
                . "AND accountingcode = '$account' and isreverse=0 "
                . "group by refno,description) c order by transactiondate,receiptno");
        
        return $accounts;
    }
    
    static function isvisible($accounts,$subaccount){
        $counter = 0;
        
        foreach($accounts as $account){
            if(strcmp($account->description,$subaccount) == 0){
                $counter++;
            }
        }
        
        return $counter;
    }
    
    static function notinlist($accounts,$subaccounts){
        $accountsDescription = array();
        foreach($accounts as $account){
            array_push($accountsDescription,$account->description);
        }
        
        return count(array_diff($accountsDescription, $subaccounts));
    }
}
