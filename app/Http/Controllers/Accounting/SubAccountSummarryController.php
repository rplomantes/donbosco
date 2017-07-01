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
    
    function printAccount($from,$to,$account){
        if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')){
            $fromdate = $from;
            $todate = $to;
            $subaccounts = \App\CtrOtherPayment::distinct('particular')->where('acctcode',$account)->orderBy('particular','ASC')->pluck('particular')->toArray();
            $accounts = SubAccountSummarryController::getaccounts($fromdate,$todate,$account);

           return view('print.printsubaccount',compact('accounts','subaccounts','account','fromdate','todate'));
        }
    }

    
    static function getaccounts($fromdate,$todate,$account){
        $accounts = DB::Select("select accountingcode,description,refno,transactiondate,receiptno,entry_type,acct_department,sub_department,debit,credit from "
                . "(select c.accountingcode,c.description,c.refno,c.transactiondate,c.receiptno,0 as debit,sum(c.amount) as credit,c.entry_type,c.acct_department,c.sub_department "
                . "from credits c "
                . "where (c.transactiondate BETWEEN '$fromdate' AND '$todate') "
                . "AND c.accountingcode = '$account' and c. isreverse=0 "
                . "group by c.refno,c.description "
                . "UNION "
                . "select accountingcode,description,refno,transactiondate,receiptno,sum(amount)+sum(checkamount) as debit,0 as credit,entry_type,acct_department,sub_department "
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
    
    static function getEntrytype($entry){
        if($entry == 1){
            return "Cash Receipt";
        }elseif($entry == 2){
            return "Debit Memo";
        }elseif($entry == 3){
            return "General Journal";
        }elseif($entry == 4){
            return "Cash Disbursement";
        }else{
            return "";
        }
    }
    
    static function getaccttotal($credit,$debit,$accountingcode){
        $total = 0;
        
        $accountingcode = substr($accountingcode,0,1);
        if($accountingcode == 4 || $accountingcode == 2){
            $total = $credit - $debit;
        }else{
            $total = $debit - $credit;
        }
        
        return $total;
    }
}
