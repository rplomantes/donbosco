<?php

namespace App\Http\Controllers\Registrar\Discount;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DiscountHelper extends Controller
{
    static function confirm_isGrantee($idno){
        $schooyear = \App\CtrYear::getYear('schoolyear');
        $grant = \App\DiscountGrant::where('schoolyear',$schooyear)->where('idno',$idno)->first();
        
        return $grant;
    }
    
    static function get_discountGrantCode($accountcode,$type){
        $discountAcct = \App\DiscountGroupAccounts::where('type',$type)->where('appliedto',$accountcode)->first();
        
        if(!$discountAcct){
            $discountAcct = \App\DiscountGroupAccounts::where('type',$type)->where('appliedto','others')->first();
        }
        
        if($discountAcct){
            return $discountAcct->discount_id;
        }else{
            return "";
        }
        
    }
}
