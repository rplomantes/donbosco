<?php

namespace App\Http\Controllers\Economer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class DisbursementSummary extends Controller
{
    function index(){
        $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
        $disbursements  = DB::Select("SELECT * "
                . "FROM disbursements where transactiondate >= '$fiscalyear-05-01' "
                . "group by EXTRACT(MONTH from transactiondate)");
        
        return view('economic.disbursementReport',compact('disbursements'));
    }
}
