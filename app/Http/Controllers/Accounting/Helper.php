<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function getaccttotal($credit,$debit,$accountingcode){
        $total = 0;
        
        $accountingcode = substr($accountingcode,0,1);
        if($accountingcode == 1 || $accountingcode == 3 || $accountingcode == 5){
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
}
