<?php

namespace App\Http\Controllers\Registrar\Grade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubmittedGradeReport extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function view(){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        $levels = \App\CtrLevel::all();
        
        return view('grade.submittedReport',compact('quarter','levels'));
    }
    
    function get_gradeReports($level){
    }
    
    function get_subjects($level){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $subjects = \App\Status::where('schoolyear',$schoolyear)->where('level',$level)->first()->grade();
    }
}
