<?php

namespace App\Http\Controllers\Update;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class UpdateController2 extends Controller
{
    function view($level){
            $excempt = array('Saint Callisto Caravario');
            $sections = \App\CtrSection::where('level',$level)->groupBy('id')->get();
            
            foreach($sections as $section){
                if(!in_array($section->section,$excempt)){
                    $this->students($level,$section->section);
                }
            }
    }
    
    function students($level,$section){
        $statuses = \App\Status::where('level',$level)->where('section',$section)->where('schoolyear',2017)->groupBy('class_no')->get();
        foreach($statuses as $status){
            echo $status->idno;
            $this->studentConduct($status);
        }
    }
    
    function studentConduct($status){
        $conducts  = DB::connection('conduct')->select("Select * from conduct where SCODE='$status->idno' and SY_EFFECTIVE='2017' and QTR=3");
        
        foreach($conducts as $conduct){
            $this->writeConduct($conduct->SCODE, 'OSR', $conduct->COM1B, 2017, 'third_grading');
            $this->writeConduct($conduct->SCODE, 'DPT', $conduct->COM2B, 2017, 'third_grading');
            $this->writeConduct($conduct->SCODE, 'PTY', $conduct->COM3B, 2017, 'third_grading');
            $this->writeConduct($conduct->SCODE, 'DI', $conduct->COM4B, 2017, 'third_grading');
            $this->writeConduct($conduct->SCODE, 'PG', $conduct->COM5B, 2017, 'third_grading');
            $this->writeConduct($conduct->SCODE, 'SIS', $conduct->COM6B, 2017, 'third_grading');
        }
    }
    
    function writeConduct($idno,$subjectcode,$value,$schoolyear,$field){
        $grade = \App\Grade::where('idno',$idno)->where('subjectcode',$subjectcode)->where('schoolyear',$schoolyear)->first();
        if($grade){
            $grade->$field = $value;
            $grade->save();
        }
    }
}
    