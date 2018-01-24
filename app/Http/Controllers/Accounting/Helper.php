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
        $date = date_create(date("Y-M-d",strtotime($date)));
        
        if(((int)$date->format("m")) <= 4){
            $fiscalyear = $date->format("Y")-1;
            
        }else{
            $fiscalyear = $date->format("Y");
        }
        
        return $fiscalyear;
    }
    
    static function fiscalBeginning($fiscalyear){
        return $fiscalyear."-05-01";
    }
}
