<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class DeptIncomeController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index($accountcode,$fromtran,$totran){
                $accounts = \App\ChartOfAccount::where('acctcode','LIKE',$accountcode.'%')->orderBy('acctcode','ASC')->get();
                return view("accounting.deptIncome",compact('accounts','fromtran','totran','accountcode'));
        
    }
    
    function printreport($accountcode,$fromtran,$totran){
        $accounts = \App\ChartOfAccount::where('acctcode','LIKE',$accountcode.'%')->orderBy('acctcode','ASC')->get();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView("print.printconsolidateddept",compact('accounts','fromtran','totran','accountcode'));
        return $pdf->stream();
        
        
    }
    
    static function returnzero($amount){
        if($amount != 0){
            return number_format($amount,2,'.',',');
        }else{
            return " ";
        }
    }
}
