<?php

namespace App\Http\Controllers\Accounting\Disbursement;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function get_payee($refno){
        $payee = "";
        $disbursement = self::disbursement('refno',$refno);
        
        if($disbursement){
            $payee = $disbursement->payee;
        }
        
        return $payee;
    }
    
    static function get_remarks($refno){
        $remarks = "";
        $disbursement = self::disbursement('refno',$refno);
        
        if($disbursement){
            $remarks = $disbursement->remarks;
        }
        
        return $remarks;
    }
    
    static function disbursement($field,$refno){
        return \App\Disbursement::where($field,$refno)->first();
    }
}
