<?php

namespace App\Http\Controllers;

class Helper extends Controller
{
    

    static function enrollment_prevSchool(){
        $enrollYear = self::get_enrollmentSY();
        
        return $enrollYear-1;
    }
    
    static function get_enrollmentSY(){
        return \App\CtrYear::where('type','enrollment_year')->first()->year;
    }
    
    static function get_levelDept($level){
        $department = "";
        $level_dept = \App\CtrLevel::where('level',$level)->first();
        if($level_dept){
            $department = $level_dept->department;
        }
        
        return $department;
    }
    
    static function get_propername($idno){
        $name = "";
        $user = \App\User::where('idno',$idno)->first();
        
        if($user){
            $name = $user->firstname." ".substr($user->middlename,0,1);
            if(strlen($user->middlename) > 0){
                $name = $name.". ";
            }
            $name = $name.$user->lastname;
            
            if($user->title !=""){
                $name = $user->title.$name;
            }
            
            if($user->extension !=""){
                $name = $name.$user->extension;
            }
        }
        
        return $name;
    }
    
    static function get_formalName($idno){
        $name = "";
        $student = \App\User::where('idno',$idno)->first();
        if($student){
            $name = $student->lastname.", ".$student->firstname." ".$student->middlename;
        }
        
        return trim(strtoupper($name));
    }
    
    //Student Class Information
    static function get_SyInfo($idno,$schoolyear){
        
        $status =  \App\Status::where('schoolyear',$schoolyear)->where('idno',$idno)->first();
        if(!$status){
            $status = \App\StatusHistory::where('schoolyear',$schoolyear)->where('idno',$idno)->first();
        }
        
        return $status;
    }
    
    static function get_level($idno,$schoolyear=2016){
        $level = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $level = $info->level;
        }
        
        return $level;
    }
    
    static function get_strand($idno,$schoolyear){
        $strand = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $strand = $info->section;
        }
        
        return $strand;
    }
    
    static function get_section($idno,$schoolyear=2016){
        $section = "";
        $info = self::get_SyInfo($idno, $schoolyear);
        if($info){
            $section = $info->section;
        }
        
        return $section;
    }
}
