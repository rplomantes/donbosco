<?php

namespace App\Http\Controllers\Registrar\Grade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubmittedGradeReport extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function view(){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $sectionlist = \App\CtrSection::with('CtrLevel')->where('schoolyear',$schoolyear)
                ->whereHas('CtrLevel',function($section){
                    $section->where('department', '!=', 'TVET');
                })->get();
        
        return view('registrar.grade.submittedReport',compact('sectionlist'));
    }
    
    function showSubmittedSubjects($level,$section){
        
    }
    
    static function get_subjects($level,$section){
        
        $shssem = self::getSem();
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $course = self::classCourse($level, $section,$schoolyear);
        
        $subject = \App\Grade::with('Status')->whereIn('subjecttype',array(0,1,4,5,6))->where('schoolyear',$currSY)->where('level',$level)
                ->whereHas('Status',function($status)use($course){
                    $status->where('strand', 'LIKE', $course);
                })->whereIn('semester',array(0,$shssem))->get();
        
        return $subject;
    }
    
    static function getSem(){
        $quarter = \App\CtrQuarter::first()->qtrperiod;
        if(in_array($quarter,array(1,2))){
            return 1;
        }else{
            return 2;
        }
        
    }
    
    static function classCourse($level,$section,$currSy){
        $course = "";
        
        $record = \App\CtrSection::where('level',$level)->where('section',$section)->where('schoolyear',$currSy)->first();
        if($record){
            $course = $record->strand;
        }
        
        return $course;
    }
}
