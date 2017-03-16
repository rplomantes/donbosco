<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

class DBFixer extends Controller
{
    function addOldStudent(){
        return view('vincent.tools.addOldStudent');
    }
    
    function gensubjects(){
        $subjs = DB::connection('dbti2test')->select("Select * from subject");
        return view('vincent.tools.gensubj',compact('subjs'));
    }
    
    function updateentrytype(){
        $accounts = \App\Dedit::distinct('refno')->where('paymenttype','1')->get();
        
        foreach($accounts as $account){
            \App\Dedit::where('refno',$account->refno)->update(['entry_type'=>1]);
            //echo $account->refno;
        }
        return null;
    }
    
    
    
}
