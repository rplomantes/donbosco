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
        $newuser = \App\User::where('idno',$idno)->whereNotNull('created_at')->exists();
        
        if($newuser){
            $history = \App\StatusHistory::where('idno',$idno)->where('schoolyear','<',$sy)->whereIn('status',array(2,3))->exists();
            
            if($history){
                return false;
            }else{
                return true;
            }
        }else{
            if($sy == 2016 && substr($idno, 0, 2) == '16'){
                return true;
            }else{
                return false;                
            }

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
    
    static function shortStrand($strand){
        $short = "";
        switch($strand){
            case "Mechanical Technology":
                $short = "MT";
                break;
            case "Industrial Drafting Technology":
                $short = "IDT";
                break;
            case "Electronics Technology":
                $short = "ELX";
                break;            
            case "Electrical Technology":
                $short = "ET";
                break;       
            case "Computer Technology":
                $short = "CT";
                break;
            case "Automotive Technology":
                $short = "AT";
                break;          
        }
        
        return $short;
    }
    
    static function getLevelSubjects($level,$strand,$sy){
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        if($strand != "null"){
            $strand = "and s.strand='".$strand."'";
        }else{
            $strand = "";
        }
        
        if($currSy == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        $subjects = DB::Select("Select * from grades form grades g join $table s "
                . "ON s.idno = g.idno AND s.schoolyear = g.schoolyear "
                . "WHERE s.schoolyear = '$sy' and s.status = 2 and s.level = '$level' "
                . "AND g.subjecttype IN(0,1,5,6) $strand group by subjectcode "
                . "order by subjecttype,sortto");
        
        return $subjects;
    }
}
