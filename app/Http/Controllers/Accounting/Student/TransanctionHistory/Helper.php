<?php

namespace App\Http\Controllers\Accounting\Student\TransanctionHistory;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends \App\Http\Controllers\Accounting\Student\StudentInformation
{
    static function studentCredits($idno,$schoolyear){
        return \App\Credit::where('idno',$idno)->where('schoolyear',$schoolyear)->where('isreverse',0)->orderBy('id')->get();
        
    }
    
    static function studentDebits($idno,$schoolyear){
        $alldebits =  \App\Dedit::where('idno',$idno)->where('isreverse',0)->get();
        if($schoolyear >= 2016){
            return $alldebits->filter(function($item)use($schoolyear){
                if($item->Credit->first()->schoolyear == $schoolyear){
                    return true;
                }
            });
        }else{
            return $alldebits->where('schoolyear',$schoolyear,false);
        }

        
    }
    
    static function paymenttype($transaction){
        $type = array();
        
        if($transaction->where('paymenttype',1,false)->sum('amount')>0){
            $type[] = "CASH";
        }
        
        if($transaction->where('paymenttype',1,false)->sum('checkamount')>0){
            $type[] = "CHECK";
        }
        
        return implode("/",$type);
    }
    

}
