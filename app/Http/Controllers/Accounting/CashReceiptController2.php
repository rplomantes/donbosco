<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CashReceiptController2 extends Controller
{
    function get_debit($from,$to){
        return \App\Dedit::whereBetween('transactiondate', array($from."-01",$to))->where('entry_type',1)->get();
    }
    
    function get_PrevDate($date){
        return date ( 'Y-m-j' ,strtotime ( '-1 day' , strtotime ( $date ) ));
    }
    
    function get_RangeMonth($date){
        return date("Y-m",strtotime($date));
    }
}
