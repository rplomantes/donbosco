<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Helper;

class GenLedgerController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function view($account,$from,$to){
        //$fiscalyear = Helper::getfiscalyear($to);
        
        $acct = \App\ChartOfAccount::get();
        $accountInfo = $acct->where('acctcode',$account)->first();
        $credits = \App\Credit::whereBetween('transactiondate',array($from,$to))->where('isreverse',0)->get();
        $debits = \App\Dedit::whereBetween('transactiondate',array($from,$to))->where('isreverse',0)->get();
        
        return view('accounting.GeneralLedger.view',compact('accountInfo','acct','credits','debits','from','to'));
    }
    
    function index($basic,$title,$fromdate,$todate){
        $fiscalyear = \App\CtrFiscalyear::first();

        $from = $fromdate;
        $to = $todate;
        $diff = 0;

//        $date1 = date_create(date("Y-m",strtotime($fromdate))."-01");
//        $date2 = date_create(date("Y-m",strtotime($todate))."-01");
//        $difference=date_diff($date1,$date2);
//        $diff =$difference->format("%m")+1;
        
        $date1 = strtotime($fromdate);
        $date2 = strtotime($todate);
        $min_date = min($date1, $date2);
        $max_date = max($date1, $date2);
        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $diff++;
        }
        $diff++;
        return view("accounting.generalledger",compact('from','to','diff','title','basic','fiscalyear'));
    }
    
    function printledger($basic,$title,$fromdate,$todate){
        $fiscalyear = \App\CtrFiscalyear::first();

        $from = $fromdate;
        $to = $todate;
        $diff = 0;

//        $date1 = date_create(date("Y-m",strtotime($fromdate))."-01");
//        $date2 = date_create(date("Y-m",strtotime($todate))."-01");
//        $difference=date_diff($date1,$date2);
//        $diff =$difference->format("%m")+1;
        
        $date1 = strtotime($fromdate);
        $date2 = strtotime($todate);
        $min_date = min($date1, $date2);
        $max_date = max($date1, $date2);
        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $diff++;
        }
        $diff++;


        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal');
        $pdf->loadView("print.printgeneralledger",compact('from','to','diff','title','basic','fiscalyear'));
        return $pdf->stream();
        
    }
}
