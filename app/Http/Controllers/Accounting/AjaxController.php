<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxController extends Controller
{

    
    function individualAccount(){
        $fromdate = Input::get('from');
        $todate = Input::get('to');
        $account = Input::get('account');
        $accounts = DB::Select("select refno,transactiondate,receiptno,debit,credit,entry_type,remarks from "
                . "(select c.refno,c.transactiondate,c.receiptno,0 as debit,sum(c.amount) as credit,c.entry_type,remarks "
                . "from credits c "
                . "join dedits d "
                . "ON c.refno = d.refno "
                . "where (c.transactiondate BETWEEN '$fromdate' AND '$todate') "
                . "AND c.accountingcode = '$account' and c. isreverse=0 "
                . "group by c.refno "
                . "UNION "
                . "select refno,transactiondate,receiptno,sum(amount)+sum(checkamount) as debit,0 as credit,entry_type,description "
                . "from dedits "
                . "where (transactiondate BETWEEN '$fromdate' AND '$todate' ) "
                . "AND accountingcode = '$account' and isreverse=0 "
                . "group by refno) c");
        
        return view('ajax.individualAccount',compact('accounts'));
    }
    
}
