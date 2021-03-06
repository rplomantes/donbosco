<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AccountSummaryController extends Controller{
    
    function index($fromdate,$todate){
        if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')|| \Auth::user()->accesslevel==env('USER_ECONOMIC_ADMIN')){
            $acctcodes = DB::Select("select distinct coa.acctcode,coa.accountname "
                    . "from (select acctcode,accountingcode "
                    . "from credits "
                    . "where (transactiondate BETWEEN '$fromdate' AND '$todate') "
                    . "UNION  "
                    . "select acctcode,accountingcode "
                    . "from dedits "
                    . "where (transactiondate BETWEEN '$fromdate' AND '$todate')) c "
                    . "join chart_of_accounts coa "
                    . "on coa.acctcode = c.accountingcode order by coa.accountname");
            
            return view('accounting.subsidiary',compact('acctcodes','fromdate','todate'));
        }
    }
    
    function printaccountSummary($fromdate,$todate,$account){
        $accounts = $this->getaccounts($fromdate,$todate,$account,\Auth::user()->accesslevel);
        
        $pdf = \App::make('dompdf.wrapper');
	$pdf->setPaper([0,0,1008.00,612.00],'portrait');
        $pdf->loadView('print.accountsummary',compact('fromdate','todate','accounts','account'));
        return $pdf->stream();  
        
    }
    
    static function getaccounts($fromdate,$todate,$account,$access){
        if($access == env('USER_ACCOUNTING_HEAD')){
            $accounts = DB::Select("select idno,refno,transactiondate,receiptno,debit,credit,entry_type,acct_department,sub_department from "
                    . "(select c.idno,c.refno,c.transactiondate,c.receiptno,0 as debit,sum(c.amount) as credit,c.entry_type,c.acct_department,c.sub_department "
                    . "from credits c "
                    . "where (c.transactiondate BETWEEN '$fromdate' AND '$todate') "
                    . "AND c.accountingcode = '$account' and c. isreverse=0 "
                    . "group by c.refno,c.sub_department "
                    . "UNION "
                    . "select idno,refno,transactiondate,receiptno,sum(amount)+sum(checkamount) as debit,0 as credit,entry_type,acct_department,sub_department "
                    . "from dedits "
                    . "where (transactiondate BETWEEN '$fromdate' AND '$todate' ) "
                    . "AND accountingcode = '$account' and isreverse=0 "
                    . "group by refno,sub_department) c order by transactiondate,receiptno");            
        }elseif($access == env('USER_ACCOUNTING')){
            $accounts = DB::Select("select idno,refno,transactiondate,receiptno,debit,credit,entry_type,acct_department,sub_department from "
                    . "(select c.idno,c.refno,c.transactiondate,c.receiptno,0 as debit,sum(c.amount) as credit,c.entry_type,c.acct_department,c.sub_department "
                    . "from credits c "
                    . "where (c.transactiondate BETWEEN '$fromdate' AND '$todate') "
                    . "AND c.accountingcode = '$account' and c. isreverse=0 AND entry_type = 4 "
                    . "group by c.id "
                    . "UNION "
                    . "select idno,refno,transactiondate,receiptno,sum(amount)+sum(checkamount) as debit,0 as credit,entry_type,acct_department,sub_department "
                    . "from dedits "
                    . "where (transactiondate BETWEEN '$fromdate' AND '$todate' ) "
                    . "AND accountingcode = '$account' and isreverse=0 AND entry_type = 4 "
                    . "group by id) c order by transactiondate,receiptno");            
        }

        
        return $accounts;
    }
}
