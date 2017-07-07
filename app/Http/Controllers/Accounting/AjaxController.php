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

    function subAccountSummary(){
        $fromdate = Input::get('from');
        $todate = Input::get('to');
        $account = Input::get('account');
        $acct = Input::get('account');
        $subaccounts = \App\CtrOtherPayment::distinct('particular')->where('acctcode',$account)->orderBy('particular','ASC')->pluck('particular')->toArray();
        $accounts = SubAccountSummarryController::getaccounts($fromdate,$todate,$account);
        
        return view('ajax.subaccountsummary',compact('accounts','subaccounts','acct','fromdate','todate'));
        //return $subaccounts;
    }
    
}

