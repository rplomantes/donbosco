<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AccountSummaryController extends Controller{
    
    function index($fromdate,$todate){
        if(\Auth::user()->accesslevel==env('USER_ACCOUNTING')|| \Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD')){
            $acctcodes = DB::Select("select distinct coa.acctcode,coa.accountname "
                    . "from (select acctcode,accountingcode "
                    . "from credits "
                    . "where (transactiondate BETWEEN '$fromdate' AND '$todate') "
                    . "UNION  "
                    . "select acctcode,accountingcode "
                    . "from dedits "
                    . "where (transactiondate BETWEEN '$fromdate' AND '$todate')) c "
                    . "join chart_of_accounts coa "
                    . "on coa.acctcode = c.accountingcode order by coa.acctcode");
            
            return view('accounting.subsidiary',compact('acctcodes','fromdate','todate'));
        }
    }
}
