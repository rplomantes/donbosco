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
    
    function updatetvet(){
        $statuses = \App\Status::where('period','86')->get();
        
        foreach($statuses as $status){
            //$credits = \App\Credit::where('accountingcode','440000')->where('idno',$status->idno)->get();
            $credits = DB::Select("select * from old_receipts where idno = '$status->idno'");
            $amounted = 0;
            
            foreach($credits as $credit){
                if(count($credits) > 0){
                    $amounted = $amounted + $credit->amount;
                }
            }
            $exist = \App\Ledger::where('idno',$status->idno)->where('period','86')->exists();
            if($exist){
                $ledger = \App\Ledger::where('idno',$status->idno)->where('period','86')->first();
                //$ledger->payment = $amounted;
                $ledger->payment = $ledger->payment + $amounted;
                $ledger->save();                
            }

        }
    }
}
