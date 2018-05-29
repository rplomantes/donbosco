<?php

namespace App\Http\Controllers\Registrar\Adviser;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdvisorHelper extends Controller
{
    static function get_advisorList(){
        $get_position = \App\Position::where('position','Adviser')->first();
        $position = 0;
        if($get_position){
            $position = $get_position->id;
        }
        
        $advisers = \App\UsersPosition::with(['users'=>function($query){
            $query->where('accesslevel','!=',0);
        }])->where('position',$position)->get();
        
        return $advisers;
    }
}
