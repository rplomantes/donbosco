<?php

namespace App\Http\Controllers\Accounting\Closing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClosingFiscalRequest;

use App\Http\Controllers\Accounting\Closing\CreateClosingAccounts;

class ClosingController extends Controller
{
    function index(){
        $fiscalyear = \App\CtrFiscalyear::fiscalyear();
        
        return view('accounting.Control.closeFiscal',compact('fiscalyear'));
    }
    
    function close_fisacalyear(ClosingFiscalRequest $request){
        
        
        $fiscalyear = \App\CtrFiscalyear::fiscalyear();
        $closing_ref = "c".$fiscalyear."f";
        
        CreateClosingAccounts::reverseEntries($fiscalyear,$closing_ref);
    }
}
