<?php

namespace App\Http\Controllers\Registrar\Ranking;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use Illuminate\Support\Facades\Input;
use DB;

class OverallRanking extends Controller
{
    function index($sy){
        $levels = \App\CtrLevel::all();
        return view('registrar.overallrank',compact('levels','sy'));
    }
    
    function getOARanking(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $quarter = Input::get('quarter');
        $semester = Input::get('semester');
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        $acad_field = $this->rankingField($semester,$quarter,'acad_level_');
        $tech_field = $this->rankingField($semester,$quarter,'tech_level_');
        
        if($course != "NULL" || $course != "All"){
            $course = "AND s.strand LIKE '".$course."'" ;
        } else{
            $course = "";
        }
        
        $gradefield = RegistrarHelper::getGradeQuarter($quarter);
        $students = $this->getStudents($sy,$currSy,$acad_field,$tech_field,$level,$course);
        $subjects = $this->getSubjects($sy,$currSy,$level,$course,$semester);

        return view('ajax.overallrank',compact('students','subjects','gradefield','level','sy','semester','quarter'));
        
    }
    
    function rankingField($semester,$quarter,$subject){
        if($quarter == 5){
            if($semester == 0){
                $rankfield = $subject."final1";
            }else{
                $rankfield = $subject."final".$semester;
            }
        }else{
            $rankfield = $subject."".$quarter;
        }
        
        return $rankfield;
    }
    
    function getStudents($sy,$currSy,$acad_field,$tech_field,$level,$course){
        if($currSy == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        $students = DB::Select("Select s.idno,section,$acad_field as acad,$tech_field as tech from $table s "
                . "left join rankings r on s.idno = r.idno and s.schoolyear = r.schoolyear "
                . "where s.schoolyear = '$sy' "
                . "and s.level  = '$level' "
                . "AND s.status IN(2,3)"
                . "$course "
                . "group by s.idno order by $acad_field = 0, $acad_field ASC");
        
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
