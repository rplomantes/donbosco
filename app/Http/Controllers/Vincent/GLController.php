<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GLController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index(){
        $coa = \App\ChartOfAccount::pluck('accountname')->toArray();
        
        return view("vincent.accounting.generalLedger",compact('coa'));
    }
}
