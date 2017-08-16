<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class Helper extends Controller
{
    static function getSectionList($sy,$level,$course,$section){
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
                . "AND section = '$section' "
                . "AND status IN(2,3) "
                . "ORDER BY class_no");
        
        return $students;
    }
    static function getGradeQuarter($quarter){
        switch ($quarter){
            case 1; 
                $qrt = "first_grading";
            break;
            case 2;
                $qrt = "second_grading";
            break;                
           case 3;
                $qrt = "third_grading";
            break;
            case 4;
                $qrt = "fourth_grading";
           break; 
            default:
                $qrt = "final_grade";
           break; 
        }
        return $qrt;
    }
    
    static function getSubjectType($subjecttypes){
        if(count($subjecttypes)>1){
            $subjects = implode(',', $subjecttypes);
        }else{
            $subjects = implode('', $subjecttypes);
        }
        
        return $subjects;
    }
    
    static function isNewStudent($idno,$sy){
        $newuser = \App\User::where('idno',$idno)->whereNotNull('created_at')->first();
        
        if(count($newuser)>0){
            $history = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$sy-1)->whereIn('status',array(2,3))->first();
            
            if($history){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    
    static function getNumericSection($sy,$level,$section){
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        $condition = ['schoolyear'=>$sy,'level'=>$level,'section'=>$section];
        if($currSy == $sy){
            $numSecs = \App\CtrSection::where($condition)->get();
        }else{
            $numSecs = DB::Select("Select * from ctr_sections_temp where section = '$section' AND schoolyear = '$sy' AND level = '$level'");
        }
        
        $sec= "";
        foreach($numSecs as $numSec){
            $sec = $numSec->sortto;
        }
        
        return $sec;
    }
}
