<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper as MainHelper;
use App\Http\Controllers\Assessement\EnrollmentStatus;
use Carbon\Carbon;

class ProcessStatus extends Controller
{
    static function process_Status($idno,$level,$strand,$plan){
        $sy = MainHelper::get_enrollmentSY();
        self::moveOldStatus($idno,$sy);
        self::deleteStatus($idno,$sy);
        self::createStatus($idno, $level, $strand, $plan, $sy);
        
    }
    
    static function moveOldStatus($idno,$currentSy){
        $statuses = \App\Status::where('idno',$idno)->where('schoolyear','!=',$currentSy)->get();
        
        foreach($statuses as $status){
            self::addStatusHistory($status);
            self::deleteStatus($idno,$status->schoolyear);
            
        }
    }
    
    static function addStatusHistory($student){
        $archive = new \App\StatusHistory();
        $archive->idno = $student->idno;
        $archive->date_registered = $student->date_registered;
        $archive->date_enrolled = $student->date_enrolled;
        $archive->status = $student->status;
        $archive->dropdate = $student->dropdate;
        $archive->department = $student->department;
        $archive->level = $student->level;
        $archive->track = $student->track;
        $archive->strand = $student->strand;
        $archive->course = $student->course;
        $archive->section = $student->section;
        $archive->class_no = $student->class_no;
        $archive->plan = $student->plan;
        $archive->schoolyear = $student->schoolyear;
        $archive->isnew = $student->isnew;
        $archive->isesc = $student->isesc;
        $archive->save();
    }
    
    static function deleteStatus($idno,$sy){
        \App\Status::where('idno',$idno)->where('schoolyear',$sy)->delete();
    }
    
    static function createStatus($idno,$level,$strand,$plan,$sy){
        $status = new \App\Status();
        $status->idno = $idno;
        $status->date_registered = Carbon::now();
        $status->status = "1";
        $status->strand = $strand;
        $status->department = MainHelper::get_levelDept($level);
        $status->level = $level;
        $status->plan= $plan;
        $status->schoolyear=  $sy;
        
        $returning = \App\StatusHistory::where('idno',$idno)->where('schoolyear','<',$sy-1)->whereIn('status',array(2,3))->orderBy('schoolyear','DESC')->first();
        if($returning){
            $status->isnew=  '0';
        }
        $status->save();
    }
    
}
