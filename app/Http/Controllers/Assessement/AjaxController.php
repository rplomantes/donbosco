<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Assessement\Helper as AssHelper;
use App\Http\Controllers\Helper as MainHelper;
use App\Http\Controllers\StudentInfo as Info;
use Illuminate\Support\Facades\Input;

class AjaxController extends Controller
{
    function getPlanView($idno,$plan,$strand = ""){
        $discountcode = Input::get('discount');
        $lastEnrollmentYear = MainHelper::enrollment_prevSchool();
        $lastYearAStudent = Info::get_StudentSyInfo($idno, $lastEnrollmentYear);
        
        if($lastYearAStudent){
            $level = AssHelper::level_up($idno,$lastYearAStudent->level);
            $log = new \App\Log();
            $log->action = "ajax";
            $log->message = $idno.",".$plan.",".$strand.', '.$level;
            $log->save();
            if($strand ==""){
                $strand = AssHelper::get_hasStrand($level,$idno,$lastEnrollmentYear);
            }
            
        }else{
            $returning = \App\StatusHistory::where('idno',$idno)->where('schoolyear','>',$lastEnrollmentYear)->whereIn('status',array(2,3))->orderBy('schoolyear','DESC')->first();
            if($returning){
                $level = AssHelper::level_up($idno,$returning->level);
                if($strand ==""){
                    $strand = AssHelper::get_hasStrand($level,$idno,$returning->schoolyear);
                }
                
            }else{
                $level = "";
            }
        }
        return AssHelper::get_assessmentBreak($idno,$plan,$level,$strand);
    }
    
    function getStrandView(Request $request){
        $level = $request->level;
        $idno = $request->idno;
        $schoolyear = MainHelper::get_enrollmentSY();
        if($request->ajax()){
            return AssHelper::get_viewStrand($level,$idno,$schoolyear);
        }
        
    }
}
