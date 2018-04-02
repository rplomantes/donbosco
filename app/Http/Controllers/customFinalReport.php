<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class customFinalReport extends Controller
{
    function index($sy="",$level="",$section=""){
        $students = $this->getStudents($sy, $level, $section);
        $subjects = $this->getSubjects($sy, $level);
        
        return view('sys_admin.grades',compact('students','subjects','sy','level','strand'));
        
    }
    
    function getStudents($sy,$level,$section){
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        if($currSY == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        $students = DB::Select("Select * from  $table "
                . "where level = '$level' and strand IN('ABM','STEM') "
                . "AND status IN(2,3) "
                . "AND section ='$section'"
                . "ORDER BY class_no");
        
        return $students;
    }
    
    function getSubjects($sy,$level){
        if(true){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        $subjects = \App\CtrSubjects::where('level',$level)->whereIn('subjecttype',array(5,6))->where('strand','ABM')->orderBy('subjectname','ASC')->get()->unique('subjectcode');
        
        return $subjects;
    }
    
    
}
