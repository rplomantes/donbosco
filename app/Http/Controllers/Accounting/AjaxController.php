<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Controllers\Accounting\AccountSummaryController;

class AjaxController extends Controller
{

    
    function individualAccount(){
        $fromdate = Input::get('from');
        $todate = Input::get('to');
        $account = Input::get('account');
        $accounts = AccountSummaryController::getaccounts($fromdate,$todate,$account);
        
        return view('ajax.individualAccount',compact('accounts'));
    }
    
}
