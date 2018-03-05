<?php

namespace App\Http\Controllers\Accounting\Student;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StudentInformation extends Controller
{
    static function get_level($idno,$schoolyear=2016){
        $level = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $level = $info->level;
        }
        
        return $level;
    }
    
    static function get_plan($idno,$schoolyear){
        $plan = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $plan = $info->plan;
        }
        
        return $plan;
    }
    
    static function get_department($idno,$schoolyear){
        $department = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $department = $info->department;
        }
        
        return $department;
    }
    
    static function get_section($idno,$schoolyear=2016){
        $section = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $section = $info->section;
        }
        
        return $section;
    }
    
    static function get_strand($idno,$schoolyear){
        $strand = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $section = $info->section;
        }
        
        return $strand;
    }
    
    static function get_status($idno,$schoolyear){
        $status = 0;
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $status = $info->status;
        }
        
        return $status;
    }
    
    static function get_statusWord($idno,$schoolyear){
        $status = "";
        $info = self::get_status($idno, $schoolyear);
        if($info != ""){
            switch($info){
                case 0:
                    $status = 'Registered';
                    break;
                case 1;
                    $status = 'Assessed';
                    break;
                case 2:
                    $status = 'Enrolled';
                    break;
                case 3:
                    $status = 'Dropped';
                    break;
            }
        }else{
            $status = 'Registered';
        }
        
        return $status;
    }
    
    static function get_name($idno){
        $name = "";
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $name = $student->lastname.", ".$student->firstname." ".$student->middlename;
        }
        
        return $name;
    }
    
    static function get_propername($idno){
        $name = "";
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $name = $student->firstname." ".$student->middlename." ".$student->lastname;
        }
        
        return $name;
    }
    
    static function get_namedividedWInitial($idno){
        $name = array();
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $name ['firstname']= $student->firstname;
            $name ['lastname']= $student->lastname;
            $name ['middleinit']= substr($student->middlename,0,1);
        }else{
            $name ['firstname']= "";
            $name ['lastname']= "";
            $name ['middleinit']= "";
        }
        
        return $name;
    }
    
    static function get_gender($idno){
        $gender = "";
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $gender = $student->gender;
        }
        
        return $gender;
    }
    
    static function get_SyInfo($idno,$schoolyear){
        $currentschoolyear = \App\CtrSchoolYear::first()->schoolyear;
        if($schoolyear == $currentschoolyear){
            return \App\Status::where('schoolyear',$schoolyear)->where('idno',$idno)->first();
        }else{
            return \App\StatusHistory::where('schoolyear',$schoolyear)->where('idno',$idno)->first();
        }
    }
    
    static function get_studentLedger($idno,$schoolyear){
        return \App\Ledger::where('idno',$idno)->where('schoolyear',$schoolyear)->get();
    }
    
}
