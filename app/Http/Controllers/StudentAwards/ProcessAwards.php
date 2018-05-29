<?php

namespace App\Http\Controllers\StudentAwards;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
use Carbon\Carbon;


class ProcessAwards extends AwardsController
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    static function changeAwardStatus($idno,$status){
        $funds = self::awards($idno)
                ->filter(function($item)use($status){
                    return $item->status != $status;
                });
                
        foreach($funds as $fund){
            $fund->status = $status;
            $fund->save();
        }
    }
    
    static function processPaymentAward($refno, $orno,$idno,$debittype,$amount,$awardname){
        $schoolyear = \App\CtrYear::where('type','schoolyear')->first();
        $fiscalyear = \App\CtrFiscalyear::first()->fiscalyear;
                
        $debitaccount = new \App\Dedit;
        $debitaccount->idno = $idno;
        $debitaccount->fiscalyear=$fiscalyear;
        $debitaccount->transactiondate = Carbon::now();
        $debitaccount->accountingcode = 120102;
        $debitaccount->acctcode = 'Accounts Receivable - Others';
        $debitaccount->description = $awardname;
        $debitaccount->refno = $refno;
        $debitaccount->entry_type = '1';
        $debitaccount->receiptno = $orno;
        $debitaccount->paymenttype = $debittype;
        $debitaccount->receivefrom = Info::get_name($idno);
        $debitaccount->amount = $amount;
        $debitaccount->acct_department = Info::get_department($idno, $schoolyear);
        $debitaccount->sub_department = Info::get_department($idno, $schoolyear);
        $debitaccount->postedby = \Auth::user()->idno;
        $debitaccount->save();
    }
    
    static function reduce_award($amount,$idno,$type){
        $awards = \App\StudentAwardee::where('status',1)->where('idno',$idno)->where('type',$type)->where('amount','>','used')->orderBy('id','ASC')->get();
        
        foreach($awards as $award){
            $remainingAmount = $award->amount - $award->used;

            if($remainingAmount > $amount){
                $award->used = $remainingAmount + $amount;
                $award->save();

                $amount = 0;
            }else{
                $award->used = $award->amount;
                $award->save();

                $amount = $amount - $remainingAmount;
            }
        }
    }
    
    static function reverse_award($amount,$idno,$type){
        $awards = \App\StudentAwardee::where('status',1)->where('idno',$idno)->where('type',$type)->orderBy('id','DESC')->first();
        $awards->used = $awards->used - $amount;
        $awards->save();
    }
}
