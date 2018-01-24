<?php

namespace App\Http\Controllers\Miscellaneous;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel;
use Carbon\Carbon;

class DisburstmentReportController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function index($voucherno=null){
	if($voucherno == null){
	    $voucherno = \App\Disbursement::where('isreverse',0)->orderBy('id','DESC')->first()->voucherno;
	}
//        if($voucherno == null){
//            $vouchers = \App\Disbursement::where('isreverse',0)->orderBy('id','DESC')->get();
//        }else{
            $vouchers = \App\Disbursement::where('isreverse',0)->where('voucherno','>=',$voucherno)->orderBy('id','DESC')->get();
//        }
        
        
        
        return view('accounting.disbursementReport',compact('vouchers','voucherno'));
    }
    
    function export($voucherno=null){
        Excel::create('MIS 03-02 Form', function($excel) {

            $excel->sheet('Sheet1', function($sheet){
                $sheet->loadView('vincent.export.enrollmentReport');
                
                $sheet->mergeCells('AL1:AM2');
                $sheet->setBorder('A1:AT4', 'double');
                
            });
        })->export('xls');
    }
}
