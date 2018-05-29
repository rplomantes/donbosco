<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    static function get_plan($plan,$level,$strand){

        $selected =  \App\CtrPaymentSchedule::where('level',$level)->where('plan',$plan)->get();
        if($strand != ""){
            $selected = $selected->where('strand',$strand);
        }
        
        return $selected;
    }
    
    static function isPlanValid($plan,$level,$strand){
        $planSched = self::get_plan($plan, $level, $strand);
        
        if(count($planSched)> 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
