<?php

namespace App\Http\Controllers\Registrar\Statistics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StatisticsHelper extends Controller
{
    static function get_neededRemaining($enrolledCount,$required){
        $needed = $required - $enrolledCount;
        
        if($needed >= 0){
            return $needed;
        }else{
            return "<b color:'red'>Exceeded!<b>  ".abs($needed);
        }
    }
}
