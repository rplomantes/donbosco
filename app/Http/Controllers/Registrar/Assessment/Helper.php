<?php

namespace App\Http\Controllers\Registrar\Assessment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function get_enrollmentyear(){
        return \App\CtrYear::where('type','enrollment_year')->first()->year;
    }
    
}
