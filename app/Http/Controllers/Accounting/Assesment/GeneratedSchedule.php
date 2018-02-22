<?php

namespace App\Http\Controllers\Accounting\Assesment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GeneratedSchedule extends Controller
{
    function view($schoolyear,$department,$level=""){
        if($department == "Elementary"){
            $accounts = \App\CtrPaymentSchedule::where('schoolyear',$schoolyear)->whereIn('department',array('Kindergarten','Elementary'))->get();
            $levels = \App\CtrLevel::whereIn('department',array('Kindergarten','Elementary'))->get();
        }else{
            $accounts = \App\CtrPaymentSchedule::where('schoolyear',$schoolyear)->where('department',$department)->get();
            $levels = \App\CtrLevel::where('department',$department)->get();
        }
        if($level !=""){
            $courses = \App\CtrSection::where('level',$level)->get()->unique('strand');
            return view('accounting.EnrollmentAssessment.generatedSched.GeneratedScheduleStrand',compact('accounts','level','courses'));
        }else{
            return view('accounting.EnrollmentAssessment.generatedSched.GeneratedSchedule',compact('accounts','levels'));
        }
        
    }
}
