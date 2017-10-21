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
            case 5;
                $qrt = "final_grade";
           break;
            default:
                $qrt = "period";
                break;
        }
        return $qrt;
    }
    
    static function setAttendanceQuarter($semester,$quarter){
        $qtr = array($quarter);
        switch($semester){
            case 0;
                if($quarter == 5){
                    $qtr = array(1,2,3,4);
                }
            break;
            case 1;
                if($quarter == 5){
                    $qtr = array(1,2);
                }
            break;
            case 2;
                if($quarter == 1){
                    $qtr = array(3);
                }
                if($quarter == 2){
                    $qtr = array(4);
                }
                if($quarter == 5){
                    $qtr = array(3,4);
                }
            break;
        }
        
        return $qtr;
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
    
    static function getLevelSubjects($level,$strand,$sy,$semester){
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
        
        $subjects = DB::Select("Select subjectcode,subjectname,subjecttype from grades g join $table s "
                . "on g.idno = s.idno AND g.schoolyear = s.schoolyear "
                . "where s.level = '$level' $strand "
                . "and subjecttype IN(0,1,5,6) "
                . "and isdisplaycard = 1 "
                . "AND g.semester = $semester "
                . "and g.schoolyear = $sy "
                . "group by subjectcode "
                . "order by subjecttype,sortto");
        
        return $subjects;
    }
    
    static function quarterattendance($sy,$level,$quarter){
        $noOfDays = "";
        $attendance = \App\CtrAttendance::where('schoolyear',$sy)->where('level',$level)->where('quarter',$quarter)->first();
        
        if($attendance){
            $noOfDays = number_format($attendance->Jun+$attendance->Jul+$attendance->Aug+$attendance->Sept+$attendance->Oct+$attendance->Nov+$attendance->Dece+$attendance->Jan+$attendance->Feb+$attendance->Mar,1);
        }
        
        return $noOfDays;
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
    
    static function info($idno){
        $name = "";
        $sydate = date("Y-m-d");
        $infos = \App\StudentInfo::where('idno',$idno)->first();
        $student = \App\User::where('idno',$idno)->first();
        $currage = "0";
        
        if(count($infos)>0){
            
            $age_year = date_diff(date_create($infos->birthDate), date_create($sydate))->y;
            $age= $age_year+1;
            $currage = $age;
            
            $infos->age = $currage;
            $infos->gender = $student->gender;
            $infos->name = $student->lastname.", ".$student->firstname." ".$student->middlename;;
            
            
            return $infos;
        }else{
            return array();
        }
    }
    
    static function integerToRoman($integer){
     $integer = intval($integer);
     $result = '';

     $lookup = array('M' => 1000,
     'CM' => 900,
     'D' => 500,
     'CD' => 400,
     'C' => 100,
     'XC' => 90,
     'L' => 50,
     'XL' => 40,
     'X' => 10,
     'IX' => 9,
     'V' => 5,
     'IV' => 4,
     'I' => 1);

     foreach($lookup as $roman => $value){
      // Determine the number of matches
      $matches = intval($integer/$value);

      // Add the same number of characters to the string
      $result .= str_repeat($roman,$matches);

      // Set the integer to be the remainder of the integer and the value
      $integer = $integer % $value;
     }

     // The Roman numeral should be built, return it
     return $result;
    }
}
