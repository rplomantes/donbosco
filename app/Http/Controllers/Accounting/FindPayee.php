<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FindPayee extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function search(){
        return view('print.searchPayee');
    }
    
    function findpayee(Request $request){
        $payee = $request->payee;
        
    }
}
