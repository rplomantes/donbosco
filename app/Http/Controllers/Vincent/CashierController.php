<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class CashierController extends \App\Http\Controllers\Cashier\CashierController
{
    public function __construct(){
        $this->middleware('auth');
    }

    function searchor(){
        if((\Auth::user()->accesslevel==env('USER_CASHIER'))||(\Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD'))){
            return view('vincent.cashier.searchor');
        }
    }
    
    function findor(request $request){
        if((\Auth::user()->accesslevel==env('USER_CASHIER'))||(\Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD'))){
            $search = \App\Dedit::where('receiptno',$request->or)->where('isreverse',0)->first();
            if(empty($search)){
                $noOR = $request->or;
                return view('vincent.cashier.searchor',compact('noOR'));
            }
            return $this->viewreceipt($search->refno,$search->idno);
        }
    }    
}
