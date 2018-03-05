<?php

namespace App\Http\Controllers\Registrar\Transcript;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\GradeController;
use App\Http\Controllers\Registrar\AttendanceController;

class SeniorTranscript extends Controller
{
    function index($idno){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('folio', 'portrait');
        $pdf->loadView('registrar.transcript.SHS.body',compact('idno'));
        return $pdf->stream();
    }
    
    static function previousRecord($idno){
        $school = "";
        $schoolyear = "";
        $grade = "";
        $prevRec = \App\PrevSchoolRec::where('level','Grade 10')->where('idno',$idno)->first();
        
        if($prevRec){
            $school = $prevRec->school;
            $schoolyear = $prevRec->schoolyear;
            $grade = $prevRec->finalrate;
        }else{
            $prevRec = \App\Grade::where('level','Grade 10')->where('idno',$idno)->whereIn('subjecttype',array(0,1))->get();
            $school = "DON BOSCO TECHNICAL INSTITUTE";
            $schoolyear = $prevRec->schoolyear;
            $grade = GradeController::gradeQuarterAve(array(0),array(0),5,$prevRec,'Grade 10');
        }
        
        return view('registrar.transcript.SHS.juniorRecord',compact('school','schoolyear','grade'))->render();
    }
    
    static function levelRecord($idno,$level){
        $status = \App\Status::where('idno',$idno)->where('level',$level)->whereIn('status',array(2,3))->orderBy('schoolyear','DESC')->orderBy('status','ASC')->first();
        if(!$status){
            $status = \App\StatusHistory::where('idno',$idno)->where('level',$level)->whereIn('status',array(2,3))->orderBy('schoolyear','DESC')->orderBy('status','ASC')->first();
        }

        return self::viewRecord($status);
    }
    
    static function viewRecord($status){
        $grades = \App\Grade::where('schoolyear',$status->schoolyear)->where('idno',$status->idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
        
        //Attendance
        $q1dayp = array();
        $q1daya = array();
        $q1dayt = array();
        $q2dayp = array();
        $q2daya = array();
        $q2dayt = array();        
        
        for($i=1; $i < 3 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($status->idno,$status->schoolyear,$i,$status->level);
            $q1dayp[] = $attendance[0];
            $q1daya[] = $attendance[1];
            $q1dayt[] = $attendance[2];
        }
        
        for($i=3; $i < 5 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($status->idno,$status->schoolyear,$i,$status->level);
            $q2dayp[] = $attendance[0];
            $q2daya[] = $attendance[1];
            $q2dayt[] = $attendance[2];
        }
        return view('registrar.transcript.SHS.grades',compact('status','grades','q1dayp','q1daya','q1dayt','q2dayp','q2daya','q2dayt'))->render();
        
    }
}
