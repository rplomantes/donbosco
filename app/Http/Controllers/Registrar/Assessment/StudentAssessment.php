<?php

namespace App\Http\Controllers\Registrar\Assessment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Assessment\StudentEnrollmentStatus as EnrollmentStatus;
use App\Http\Controllers\Registrar\Assessment\Helper;


class StudentAssessment extends Controller
{
    function viewAssessment($idno){
        $oldStudent = EnrollmentStatus::isoldStudent($idno);
        $enrollable = EnrollmentStatus::enrollmentStatus($idno);
        $enrollmentSy = Helper::get_enrollmentyear();
        
        $assessed = \App\Status::where('idno',$idno)->where('schoolyear',$enrollmentSy)->whereIn('status',array(1,2))->first();
        
        if($assessed){
            return view('registrar.assessment.assessed',compact('idno','enrollmentSy','assessed'));
        }
        
        if($oldStudent){
            if($enrollable){
                
            }else{
                
            }
        }else{
            return view('registrar.assessment.assessStudent',compact('idno','enrollmentSy'));
        }
        
    }
}
