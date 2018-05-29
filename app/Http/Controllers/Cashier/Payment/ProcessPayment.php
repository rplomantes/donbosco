<?php

namespace App\Http\Controllers\Cashier\Payment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProcessPayment extends Controller
{   
    function mainpayment(Request $request, $idno){
        $orno = $this->getOR();
        $refno = $this->getRefno();
        $discount = 0;
        $plandiscount = 0;
        $otherdiscount = array();
        $change = 0;
        
        $this->reset_or();
    }
    
    static function getRefno(){
         $newref= \Auth::user()->id . \Auth::user()->reference_number;
         return $newref;
    }
    
    static function getOR(){
        //$user = \App\User::where('idno', Auth::user()->idno )->first();
        $receiptno = \Auth::user()->receiptno;
        return $receiptno;
    }
    
    
    static function reset_or(){
        $resetor = \App\User::where('idno', \Auth::user()->idno)->first();
        $leading_zero = strlen($resetor->receiptno) - strlen(ltrim($resetor->receiptno, '0'));
        
        $orginal_len = strlen($resetor->receiptno);
        
        $new_receipt = $resetor->receiptno + 1;
        $new_len = strlen($new_receipt);
        
        if($orginal_len <= $new_len){
            $new_receipt = $new_receipt;
        }else{
            $addedZero = 0;
            do{
                $new_receipt = "0".$new_receipt;
                $addedZero++;
            }while($addedZero < $leading_zero);
        }
        $resetor->receiptno = $new_receipt;
        $resetor->reference_number = $resetor->reference_number + 1;
        $resetor->save();
    }
    
    function processMainPayment(Request $request){
        $orno = $this->getOR();
        $refno = $this->getRefno();
        $discount = 0;
        $plandiscount = 0;
        $otherdiscount = array();
        $change = 0;
        
        $mainaccounts = \App\Ledger::where('idno',$request->idno)->where('categoryswitch','<=','6')->orderBy('duedate','categorySwitch')->get();
        
        if($request->totaldue > 0 ){
            $this->processDiscount($mainaccounts);
        }
    }
    
    function processDiscount($accounts){
        $discounts = $accounts->where('status',0,false)->filter(function($query){
                        return $query->plandiscount > 0 || $query->otherdiscount > 0;
                    });
                    
        $plandiscount = $discounts->sum('plandiscount');
    }
    
    function ProcessDebit($accountingcode,$description){
        
    }
    
    
    
    
    
}
