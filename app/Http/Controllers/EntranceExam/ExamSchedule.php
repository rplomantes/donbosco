<?php

namespace App\Http\Controllers\EntranceExam;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExamSchedule extends Controller
{
     function __construct(){
        $this->middleware('auth');
    }
    
    function view($schoolyear){
        $schedules = \App\EntranceSchedule::where('schoolyear',$schoolyear);
        
        return view('viewSchedule',compact('schedules'));
    }
    
    function create(){
        $levels = \App\CtrLevel::all();
        return view('EntranceExam.createSchedule',compact('levels'));
        
        
    }
}
