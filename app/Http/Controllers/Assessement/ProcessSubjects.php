<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper as MainHelper;
use Debugbar;

class ProcessSubjects extends Controller
{
    static function processSubjects($idno, $level, $strand){
        $sy = MainHelper::get_enrollmentSY();
        self::deleteSubjects($idno, $sy);
        self::createSubjects($idno, $level, $strand, $sy);
        
    }
    
    static function deleteSubjects($idno,$schoolyear){
        \App\Grade::where('idno',$idno)->where('schoolyear',$schoolyear)->delete();
    }
    
    static function createSubjects($idno, $level, $strand,$schoolyear){
        if(in_array($level,array('Grade 11','Grade 12'))){
            $ctrSubjects = \App\CtrSubjects::where('level',$level)->where('strand',$strand)->get();
        }else{
            $ctrSubjects = \App\CtrSubjects::where('level',$level)->get();
        }
        
        
        foreach($ctrSubjects as $subject){
            
            $record = new \App\Grade();
            $record->idno = $idno;
            $record->department = $subject->department;
            $record->level = $subject->level; 
            $record->strand = $strand;
            $record->semester = $subject->semester;
            $record->subjecttype = $subject->subjecttype;
            $record->subjectcode = $subject->subjectcode;
            $record->subjectname = $subject->subjectname;
            $record->points = $subject->points;
            $record->weighted = $subject->weighted;
            $record->sortto = $subject->sortto;
            $record->isdisplaycard = $subject->isdisplaycard;
            $record->schoolyear = $schoolyear;
            $record->save();
        }
    }
}
