<?php

namespace App\Http\Controllers\Accounting\Student\TransanctionHistory;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Student\TransanctionHistory\Helper;

class ViewController extends Controller
{
    function view($idno,$schoolyear,$report){
        switch($report){
            case 'receiptSummary':
                return $this->receiptSummary($idno, $schoolyear);
                break;
            case 'byAcount':
                return $this->byAccount($idno, $schoolyear);
                break;
        }
        
    }
    
    function receiptSummary($idno,$schoolyear){
        $receipts = Helper::studentDebits($idno, $schoolyear)->where('entry_type',1,false)->where('paymenttype',1,false);
        
        return view('accounting.Student.TransactionHistory.receiptSummary',compact('receipts','idno','schoolyear'));
    }
    
    function byAccount($idno,$schoolyear){
        $receipts = Helper::studentCredits($idno, $schoolyear)->where('entry_type',1,false);
        $ledger = Helper::get_studentLedger($idno, $schoolyear);
        
        return view('accounting.Student.TransactionHistory.perMainAccount',compact('receipts','idno','schoolyear','ledger'));
    }
    

}
