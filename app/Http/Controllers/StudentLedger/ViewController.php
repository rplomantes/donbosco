<?php

namespace App\Http\Controllers\StudentLedger;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class ViewController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function mainview($idno){
        return view('cashier.studentledger.index',compact('idno'));
    }
    
    static function studentInfo($idno){
            $currSy = \App\CtrSchoolYear::first()->schoolyear;
            return view('cashier.studentledger.studentInfo',compact('currSy','idno'));
    }
    
    static function prevBalance($idno){
            $accounts = self::accounts($idno)->filter(function($item){
                return $item->categoryswitch > 10;
            })->sortBy('schoolyear');
            if($accounts->sum('amount')-($accounts->sum('plandiscount')+$accounts->sum('otherdiscount')+$accounts->sum('debitmemo')+$accounts->sum('payment'))>0){
                return view('cashier.studentledger.prevBal',compact('accounts'));
            }
            
    }
    
    static function viewledger($idno){
            $accounts = self::accounts($idno)->filter(function($item){
                return $item->categoryswitch <=10;
            });
            return view('cashier.studentledger.ledger',compact('accounts'));
    }
    
    static function paymentHistory($idno){
            $transactions = self::transactions($idno)->where('entry_type',1);

            return view('cashier.studentledger.paymentHistory',compact('transactions','idno'));
    }
    
    static function debitMemo($idno){
            $transactions = self::transactions($idno)->where('entry_type',2);

            return view('cashier.studentledger.debitmemo',compact('transactions','idno'));
    }
    
    static function paymentSched($idno){
            $accounts = self::accounts($idno)->filter(function($item){
                return $item->categoryswitch <=6;
            })->sortBy('duedate');
            return view('cashier.studentledger.paymentSched',compact('accounts','idno'));
    }
    
    static function otherAccts($idno){
            $accounts = self::accounts($idno)->filter(function($item){
                return $item->categoryswitch == 7;
            })->sortBy('id');
            return view('cashier.studentledger.otherAccts',compact('accounts','idno'));
    }
    
    static function accounts($idno){
        return \App\Ledger::where('idno',$idno)->orderBy('categoryswitch')->get();
    }
    
    static function transactions($idno){
        return \App\Dedit::where('idno',$idno)->orderBy('transactiondate','ASC')->orderBy('id','ASC')->get();
    }
    
    static function accountsdue($accounts){        
        return $accounts->sum('amount') - $accounts->sum('payment') - $accounts->sum('debitmemo') - $accounts->sum('plandiscount') - $accounts->sum('otherdiscount');
    }
}
