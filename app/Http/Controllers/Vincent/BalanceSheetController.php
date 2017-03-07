<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BalanceSheetController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index(){
        return view('vincent.accounting.balanceSheet');
    }
}
