<?php

namespace App\Http\Controllers;

class StudentInfo extends Controller
{
    static function get_StudentUser($idno){
        return \App\User::where('idno',$idno)->first();
    }
    
    static function get_StudentSyInfo($idno,$schoolyear,$withAssessed = FALSE){
        if($withAssessed){
            $statuses = array(1,2,3);
        }else{
            $statuses = array(2,3);
        }
        
        $status = \App\Status::where('idno',$idno)->where('schoolyear',$schoolyear)->whereIn('status',$statuses)->first();
        if(!$status){
            $status = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$schoolyear)->whereIn('status',$statuses)->first();
        }
        
        return $status;
    }
    
    static function get_StudentInfo($idno){
        return StudentInfo::where('idno',$idno)->first();
    }
    
    static function get_StudentSibling($idno){
        return \App\Sibling::where('idno',$idno)->first();
    }
    
    static function get_nameFormal($idno){
        $name = "";
        $person = self::get_StudentUser($idno);
        
        if($person){
            $name = $person->lastname.", ".$person->firstname." ".$person->middlename;
        }
        
        return strtoupper($name);
    }
    
    static function get_nameInformal($idno){
        $name = "";
        $person = self::get_StudentUser($idno);
        
        if($person){
            $name = $person->firstname." ".$person->middlename." ".$person->lastname;
        }
        
        return $name;
    }
    
    static function get_level($idno,$schoolyear,$withAssessed = FALSE){
        $level = "";
        $status = self::get_StudentSyInfo($idno,$schoolyear,$withAssessed);
        
        if($status){
            $level = $status->level;
        }
        
        return $level;
    }
    
    static function get_section($idno,$schoolyear){
        $section = "";
        $status = self::get_StudentSyInfo($idno,$schoolyear);
        
        if($status){
            $section = $status->section;
        }
        
        return $section;
    }
        
    static function get_classNo($idno,$schoolyear){
        $class_no = "";
        $status = self::get_StudentSyInfo($idno,$schoolyear);
        
        if($status){
            $class_no = $status->class_no;
        }
        
        return $class_no;
    }
    
    static function get_strand($idno,$schoolyear,$withAssessed = FALSE){
        $strand = "";
        $status = self::get_StudentSyInfo($idno,$schoolyear,$withAssessed);
        
        if($status){
            $strand = $status->strand;
        }
        
        return $strand;
    }
    
    static function get_status($idno,$schoolyear){
        $currStatus = 0;
        $status = self::get_StudentSyInfo($idno,$schoolyear,true);
        if($status){
            $currStatus = $status->status;
        }
        
        return $currStatus;
    }
    
    static function get_statusWord($idno,$schoolyear){
        $status = self::get_status($idno, $schoolyear);
        
        switch($status){
            case 0:
            case 1:
                return "Registered";
            case 2:
                return "Enrolled";
            case 3:
                return "Dropped";
        }
    }
    
    static function selected_plan($idno,$schoolyear){
        $plan = "";
        $status = self::get_StudentSyInfo($idno,$schoolyear,true);
        
        if($status){
            $plan = $status->plan;
        }
        
        return $plan;
    }
    
    
}
