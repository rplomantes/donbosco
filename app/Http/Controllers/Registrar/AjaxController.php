<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AjaxController extends Controller
{
    function levelStudent($level,$sy){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        if($schoolyear == $sy){
            $students = DB::Select("Select distinct idno,section,strand from statuses where schoolyear = '$sy' and level  = '$level'");
        }else{
            $students = DB::Select("Select distinct idno,section,strand from status_histories where schoolyear = '$sy' and level  = '$level'");
        }
        
        return view('ajax.studentlist',compact('students'));
        
    }
}
