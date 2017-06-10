<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AcademicController extends ReportController
{
    function get_level(){
        if(Auth::User()->accesslevel != env('USER_ACADEMIC_HS'))
        {
            $level = \App\CtrLevel::where('department','Senior High School')->orWhere('department','Junior High School')->get();
        }else if(Auth::User()->accesslevel != env('USER_ACADEMIC_ELEM'))
            {
            $level = \App\CtrLevel::where('department','Kindergarten')->orWhere('department','Elementary')->get();
        }else{
            $level = \App\CtrLevel::get();
        }
        
        return $level;
    }    
}
