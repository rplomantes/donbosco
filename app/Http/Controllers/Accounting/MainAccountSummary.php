<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MainAccountSummary extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function view($schoolyear){
        return view('accounting.AccountSummary.mainaccount',compact('schoolyear'));
    }
}
