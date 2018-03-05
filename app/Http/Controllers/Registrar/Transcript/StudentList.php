<?php

namespace App\Http\Controllers\Registrar\Transcript;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StudentList extends Controller
{
    function view($schoolyear,$grade = ""){
        $levels = \App\CtrLevel::all();
        $students = $this->levelList($grade, $schoolyear);
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        
        return view('registrar.transcript.list.viewlist',compact('students','schoolyear','grade','currSY','levels'));
    }
    
    function levelList($level,$schoolyear){
        return \App\Status::where('level',$level)->where('schoolyear',$schoolyear)->whereIn('status',array(2,3))->orderBy('class_no')->get();
    }
}
