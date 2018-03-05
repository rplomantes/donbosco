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
        
        $accountsdue = $accounts->filter(function($items){
            return $items->duedate <= Carbon::now() && ($items->payment + $items->debitmemo + $items->plandiscount +$items->otherdiscount) < $items->amount;
        });
        
        $accountdue = self::accountsdue($accounts);
        
        $mainaccounts = $accountsdue->filter(function($items){
            return $items->categoryswitch < 7;
        });
        
        $maindue = self::accountsdue($mainaccounts);
        
        $prevBalance = $accounts->filter(function($items){
            return $items->categoryswitch > 10;
        });
        
        $otheraccounts = $accounts->where('categorytype',7,false);
        
        return view('cashier.payment.mainpayment',compact('accountdue','maindue','prevBalance','otheraccounts'));
    }
    
    static function accountsdue($accounts){        
        return $accounts->sum('amount') - $accounts->sum('payment') - $accounts->sum('debitmemo') - $accounts->sum('plandiscount') - $accounts->sum('otherdiscount');
    }
}
