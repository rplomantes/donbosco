<?php

namespace App\Http\Controllers\Registrar\Ranking;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Registrar\Ranking\Helper as RankHelper;
use Illuminate\Support\Facades\Input;
use DB;

class OverallRanking extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        return view('registrar.overallrank.index',compact('selectedSY','currSY','levels'));
    }
    
    function getOARanking(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $semester = Input::get('semester');
        $quarter = Input::get('quarter');
        $section = Input::get('section');
        $strand = Input::get('course');
        $sort = Input::get('sort');
        
        $gradeQuarter = RegistrarHelper::setQuarter($semester, $quarter);
        $acad_field = RankHelper::rankingField($semester,$quarter,'acad_level_');
        $tech_field = RankHelper::rankingField($semester,$quarter,'tech_level_');
        $attendanceQtr = RegistrarHelper::setAttendanceQuarter($semester, $quarter);
        $gradeField = RegistrarHelper::getGradeQuarter($gradeQuarter);
        
        //$students = RegistrarHelper::getSectionList($sy,$level,$strand,$section);
        $students = $this->getStudents($sy,$level,$strand);
        $subjects = RegistrarHelper::getLevelSubjects($level,$strand,$sy,$semester);
        
        return view('ajax.overallRank',compact('students','level','section','semester','subjects','sy','quarter','strand','attendanceQtr','gradeField','acad_field','tech_field','sort'))->render();
        
    }
    

    
    function getStudents($sy,$level,$course){
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        if($currSY == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        if($course != "null"){
            $course = "and strand='".$course."'";
        }else{
            $course = "";
        }
        $students = DB::Select("Select * from  $table "
                . "where level = '$level' $course "
                . "AND status IN(2,3) "
                . "AND section !=''"
                . "ORDER BY class_no");
        
        return $students;
    }
    
    function getSubjects($sy,$currSy,$level,$course,$semester){
        if($currSy == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        $subjects = DB::Select("Select * from $table s "
                . "left join grades g on s.idno = g.idno and s.schoolyear = g.schoolyear "
                . "where s.schoolyear = '$sy' "
                . "and s.level  = '$level' "
                . "AND g.isdisplaycard = 1 "
                . "AND g.semester =$semester "
                . "$course "
                . "group by subjectcode order by sortto ASC");
        
        return $subjects;
    }
}
