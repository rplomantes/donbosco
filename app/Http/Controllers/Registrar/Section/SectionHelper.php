<?php

namespace App\Http\Controllers\Registrar\Section;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SectionHelper extends \App\Http\Controllers\Helper
{
    static function get_name($idno){
        $name = "";
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $name = $student->title.". ".$student->firstname." ".$student->middlename." ".$student->lastname;
        }
        
        return $name;
    }
    
    static function get_StudentList($schoolyear,$level,$strand=null,$section=null){
        $students = \App\Status::selectRaw('idno,class_no,section,strand')->whereIn('status',array(2,3))
                ->where('schoolyear',$schoolyear)->where('level',$level)->get();
        
        if(count($students) > 0 && $strand != null){
            $students = $students->where('strand',$strand,false);
        }
        
        if(count($students) > 0 && $section != null){
            $students = $students->where('section',$section,false);
        }
        
        $names = array();

        foreach($students as $student){
            $studentInfo = \App\User::where('idno',$student->idno)->first();
            $names[] = (object)['idno'=>$student->idno,'lastname'=>$studentInfo->lastname,'firstname'=>$studentInfo->firstname,
                'middlename'=>$studentInfo->middlename,'name'=>SectionHelper::get_formalName($student->idno),
                'section'=>$student->section];
        }
        $studentList = collect($names)->sort(function($first,$second){
             if ($first->lastname == $second->lastname) {
                return $first->firstname < $second->firstname ? 1 : -1 ;
             }
            return $first->lastname < $second->lastname ? -1 : 1 ;
        });
        
        
        return $studentList;
    }
    
    static function set_studentSection($idno,$section){
        $schoolyear = \App\CtrYear::getYear('schoolyear');
        $student = \App\Status::where('status',2)->where('schoolyear',$schoolyear)->where('idno',$idno)->first();
        
        if($student){
            $student->section = $section;
            $student->save();
        }
    }
}
