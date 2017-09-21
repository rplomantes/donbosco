<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function getaccttotal($credit,$debit,$entry){
        $total = 0;
        
        
        if($entry == 'debit'){
            $total = $debit - $credit;
        }else{
            $total = $credit - $debit;
        }
        
        return $total;
    }
    
    static function allaccttotal($accounts){
        $creditaccts = 0;
        $debitaccts = 0;
        foreach($accounts as $account){
            $accountingcode = substr($account->accountingcode,0,1);

                if($accountingcode == 1 || $accountingcode == 3 || $accountingcode == 5){
                    $amount = self::getaccttotal($account->credits,$account->debit,$account->accountingcode);
                    $debitaccts = $debitaccts + $amount;
                }else{
                    $amount = self::getaccttotal($account->credits,$account->debit,$account->accountingcode);
                    $creditaccts = $creditaccts + $amount;
                }
        }
            
            return $creditaccts - $debitaccts;
    }
    
    static function getfiscalyear($date){
        $month = date_create(date("M",strtotime($date)));
        $year = date_create(date("Y",strtotime($date)));
        
        if(((int)$month->format("m")) <= 4){
            $fiscalyear = $year->format("Y")-1;
        }else{
            $fiscalyear = $year->format("Y");
        }
        
        return $fiscalyear;
    }
}
