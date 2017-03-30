<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class DMReportController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index($trandate){
        $records = DB::Select("SELECT dm.*,(sum(d.amount)+sum(d.checkamount)) as 'debits',d.receivefrom,d.isreverse as 'stat' FROM `dmcredits` dm join dedits as d on dm.refno = d.refno and d.transactiondate = '$trandate' group by d.refno ORDER BY (sum(d.amount)+sum(d.checkamount))");
        
        return view("accounting.dmreport",compact('records','trandate'));
    }
    
    function printreport($trandate){
        $records = DB::Select("SELECT dm.*,(sum(d.amount)+sum(d.checkamount)) as 'debits',d.receivefrom,d.isreverse as 'stat' FROM `dmcredits` dm join dedits as d on dm.refno = d.refno and d.transactiondate = '$trandate' group by d.refno ORDER BY (sum(d.amount)+sum(d.checkamount))");
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView("print.printdmreport",compact('records','trandate'));
        return $pdf->stream();
        
    }
}
