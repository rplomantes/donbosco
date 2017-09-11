<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GenLedgerController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index($basic,$title,$fromdate,$todate){
        $fiscalyear = \App\CtrFiscalyear::first();

        $from = $fromdate;
        $to = $todate;

        $date1 = date_create(date("Y-m",strtotime($fromdate))."-01");
        $date2 = date_create(date("Y-m",strtotime($todate))."-01");
        $difference=date_diff($date1,$date2);
        $diff =$difference->format("%m")+1;

        return view("accounting.generalledger",compact('from','to','diff','title','basic','fiscalyear'));
    }
    
    function printledger($basic,$title,$fromdate,$todate){
        $fiscalyear = \App\CtrFiscalyear::first();

        $from = $fromdate;
        if($todate == null){
        $to = date("Y-m-d",strtotime(\Carbon\Carbon::now()));
        }else{
            $to = $todate;
        }

        $date1 = date_create('2011-05-01');
        $date2 = date_create($to);
        $difference=date_diff($date1,$date2);
        $diff =$difference->format("%m")+1;


        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView("print.printgeneralledger",compact('from','to','diff','title','basic','fiscalyear'));
        return $pdf->stream();
        
    }
}
