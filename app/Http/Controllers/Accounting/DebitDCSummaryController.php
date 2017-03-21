<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class DebitDCSummaryController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index($fromtran,$totran){
        $dms = DB::Select("Select distinct refno from dedits where entry_type=2 and isreverse = 0 and (transactiondate between '$fromtran' and '$totran') order by transactiondate ASC,refno ASC");
        
        return view("accounting.dmsummary",compact('fromtran','totran','dms'));
        
    }
    
    function printsummary($fromtran,$totran){
        $dms = DB::Select("Select distinct refno from dedits where entry_type=2 and isreverse = 0 and (transactiondate between '$fromtran' and '$totran') order by transactiondate ASC,refno ASC");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView("print.dmsummary",compact('fromtran','totran','dms'));
        return $pdf->stream(); 
        
    }
}
