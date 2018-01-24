<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class JournalController extends \App\Http\Controllers\Cashier\CashierController
{

    function addEntry(){
        return view("vincent.accounting.addentry");
    }
    
    function listofentry(){
       return view('vincent.accounting.listofentry'); 
    }
    
    function accountingview($refno){
        return view('vincent.accounting.viewofentry',compact('refno'));
    }
    function editjournalentry($refno){
        $modifies = \App\Accounting::where('refno',$refno)->get();
        foreach($modifies as $modify){
            $modify->isfinal = 0;
            $modufy->update();
        }
    }
}
