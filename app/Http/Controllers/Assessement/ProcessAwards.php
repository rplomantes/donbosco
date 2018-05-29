<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProcessAwards extends Controller
{
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
    
    static function awards($idno,$onlyActive = false){
        $enrollmentyear = \App\CtrYear::where('type','enrollment_year')->first();
        $fund = \App\StudentAwardee::where('idno',$idno)->where('schoolyear','<=',$enrollmentyear->year)->get();
        
        if($onlyActive){
            $fund = $fund->where('status',1,false);
        }
        
        return $fund;
    }
    
    static function hasRemainingFund($idno,$onlyActive = false){
        $fund = self::awards($idno,$onlyActive);

        $remainingFund = $fund->sum('amount')-$fund->sum('used');
        
        if($remainingFund > 0){
            return true;
        }else{
            return false;
        }
    }
    
    static function getAvailableAwards($idno,$onlyActive = false){
        $types = self::awards($idno,$onlyActive)
                ->filter(function($items){
                    return $items->amount > $items->used;
                });
        return $types;
    }
}
