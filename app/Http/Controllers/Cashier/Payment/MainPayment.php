<?php

namespace App\Http\Controllers\Cashier\Payment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StudentLedger\ViewController as Ledg;
use Carbon\Carbon;

class MainPayment extends Controller
{
    static function viewPayment($idno){
        $accounts = Ledg::accounts($idno);
        
        
        //Total Account Due
        $accountsdue = $accounts->filter(function($items){
            return $items->duedate <= Carbon::now() && self::singleaccountdue($items) > 0;
        });
        
        $accountdue = self::accountsdue($accountsdue);
        //END Total Account Due
        
        //main accounts
        $mainaccounts = $accountsdue->filter(function($items){
            return $items->categoryswitch < 7;
        });
        
        $maindue = self::accountsdue($mainaccounts);
        //END main accounts
        
        //Previous Balances
        $prevBalances = $accountsdue->filter(function($items){
            return $items->categoryswitch > 10;
        });
        $prevBalance = self::accountsdue($prevBalances);
        //END Previous Balances
        
        $otheraccounts = $accountsdue->where('categoryswitch',7,false);
        
        return view('cashier.payment.mainpayment',compact('idno','accountdue','maindue','prevBalance','otheraccounts'));
    }
    
    static function accountsdue($accounts){        
        return $accounts->sum('amount') - $accounts->sum('payment') - $accounts->sum('debitmemo') - $accounts->sum('plandiscount') - $accounts->sum('otherdiscount');
    }
    
    static function singleaccountdue($account){
        return $account->amount - ($account->payment - $account->debitmemo - $account->otherdiscount);
    }
}
