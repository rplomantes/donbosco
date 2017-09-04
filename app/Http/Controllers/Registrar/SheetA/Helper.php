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
    static function getQuarter($action = null){
        $level = Input::get('level');
        
        return view('ajax.selectquarter',compact('level','action'));
    }
    
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
        
        $subjects = DB::Select("Select distinct subjectcode,subjectname from grades g join $table s on g.idno = s.idno AND g.schoolyear = s.schoolyear where s.level = '$level' $course and subjecttype IN(0,1,5,6) and subjectcode NOT LIKE 'ELE%' and isdisplaycard = 1 AND g.semester = $semester and g.schoolyear = $sy order by subjecttype,sortto");
        return view('ajax.selectsubjects',compact('subjects','action','allavailable'));
    }
    
    static function gradeSheetAList(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $semester = Input::get('semester');
        $subject = Input::get('subject');
        $quarter = Input::get('quarter');
        $section = Input::get('section');

        $students = RegistrarHelper::getSectionList($sy,$level,$course,$section);
        if($subject == 2){
            $quarter = RegistrarHelper::setAttendanceQuarter($semester,$quarter);
            return view('ajax.sheetAAttendance',compact('students','semester','quarter','sy','level','quarter'));
        }else{
            return view('ajax.sheetAGrade',compact('students','semester','subject','sy'));
        }
    }
    
    static function getAdviser($sy,$level,$section,$subjectcode){
        $teacher = "";
        $name = array();
        $adviser = \App\CtrSubjectTeacher::where('schoolyear',$sy)->where('level',$level)->where('section',$section)->where('subjcode',$subjectcode)->first();
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        if(count($adviser)> 0){
            $name = \App\User::where('idno',$adviser->instructorid)->first();
        }
        if(in_array($subjectcode,array(3,2))){
            if($currSy == $sy){
                $adviser = \App\CtrSection::where('schoolyear',$sy)->where('section',$section)->where('level',$level)->first();
            }else{
                $adviser = DB::Select("select * from `ctr_sections_temp` where `schoolyear` = '$sy' and `section` = '$section' and `level` = '$level' limit 1");
            }
            
            $name = \App\User::where('idno',$adviser->adviserid)->first();
        }
        
        if(count($name)> 0){
            $teacher = ucwords(strtolower($name->title.". ".$name->firstname." ".substr($name->middlename,0,1).". ".$name->lastname));
        }
        
        return $teacher;
    }
    
    static function getSubject($level,$subjectcode){
        $subjectname= "";
        $subject= \App\CtrSubjects::where('level',$level)->where('subjectcode',$subjectcode)->where('isdisplaycard',1)->first();
        
        if(count($subject) > 0){
            $subjectname = ucwords(strtolower($subject->subjectname));
        }elseif($subjectcode == 2){
            $subjectname= "Attendance";
        }elseif($subjectcode == 3){
            $subjectname= "Good Manners and Right Conduct";
        }
        
        return $subjectname;
    }
    
}
