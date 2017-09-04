<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Registrar\Ranking\Helper as RankHelper;
class SheetBController extends Controller
{
    
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        
        return view('registrar.sheetB.index',compact('selectedSY','currSY','levels'));   
    }
    
    function finalSheetB($quarter,$level,$section,$strand = null){
        $sy = \App\CtrRefSchoolyear::first();
        $strands = Input::get('strand');
        if($strands == null || $strands == '' || $level == 'Grade 7' || $level == 'Grade 8' || $level == 'Grade 9' || $level == 'Grade 10'){
            $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\StatusHistory::where('level',$level)->where('section',$section)->where('schoolyear','2016')->orderBy('class_no','ASC')->get();
        }else{
            $subjects = \App\CtrSubjects::where('level',$level)->where('strand',$strands)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            $students = \App\StatusHistory::where('level',$level)->where('section',$section)->where('strand',$strands)->where('schoolyear','2016')->orderBy('class_no','ASC')->get();
        }
        
        return view("ajax.finalSheetB",compact('quarter','students','subjects','level','section'));
        //return $subjects;
    }
    
    static function gradeSheetBList(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $semester = Input::get('semester');
        $quarter = Input::get('quarter');
        $section = Input::get('section');
        $strand = Input::get('course');
        $gradeQuarter = self::setQuarter($semester, $quarter);
        $acad_field = RankHelper::rankingField($semester,$quarter,'acad_');
        $tech_field = RankHelper::rankingField($semester,$quarter,'tech_');
        $attendanceQtr = RegistrarHelper::setAttendanceQuarter($semester, $quarter);
        $gradeField = RegistrarHelper::getGradeQuarter($gradeQuarter);
        
        $students = RegistrarHelper::getSectionList($sy,$level,$course,$section);
        $subjects = RegistrarHelper::getLevelSubjects($level,$strand,$sy,$semester);
        
        return view('ajax.sheetBTable',compact('students','level','section','semester','subjects','sy','quarter','strand','attendanceQtr','gradeField','quarter','acad_field','tech_field'));
        //return $quarter;
    }
    
    static function setQuarter($semester,$quarter){
        $qtr = $quarter;
        switch($semester){
            case 2;
                if($quarter == 1){
                    $qtr = 3;
                }
                if($quarter == 2){
                    $qtr = 4;
                }
            break;
        }
        
        return $qtr;
    }
}
