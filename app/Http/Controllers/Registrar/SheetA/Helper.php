<?php

namespace App\Http\Controllers\Registrar\SheetA;

use Illuminate\Http\Request;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class Helper extends Controller
{
    static function getSubjects($action = null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $semester = Input::get('semester');
        
        $allavailable = 1;
        
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        if($currSY == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        if($course != "null"){
            $course = "and s.strand='".$course."'";
        }else{
            $course = "";
        }
        
        $subjects = DB::Select("Select distinct subjectcode,subjectname from grades g join $table s on g.idno = s.idno AND g.schoolyear = s.schoolyear where s.level = '$level' $course and subjecttype IN(0,1,5,6) and subjectcode NOT LIKE 'ELE%' and isdisplaycard = 1 AND g.semester = $semester order by sortto");
        return view('ajax.selectsubjects',compact('subjects','action','allavailable'));
    }
    
    static function getSheetAList(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $semester = Input::get('semester');
        $subject = Input::get('subject');
        $section = Input::get('section');

        $students = RegistrarHelper::getSectionList($sy,$level,$course,$section);
        
        return view('ajax.sheetAGrade',compact('students','semester','subject','sy'));
    }
}
