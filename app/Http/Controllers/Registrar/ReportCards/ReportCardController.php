<?php

namespace App\Http\Controllers\Registrar\ReportCards;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;

class ReportCardController extends Controller
{
    function sectionCards(){
        $levels = \App\CtrLevel::get();
        $sy= \App\ctrSchoolYear::first()->schoolyear;
        
        return view('registrar.reportcard.index',compact('levels','sy'));
    }
    
    function printSectionCards($level,$course,$section,$quarter,$sem){
        $sy = \App\CtrSchoolYear::first()->schoolyear;
        $students = RegistrarHelper::getSectionList($sy, $level, $course, $section);
        
        return view('registrar.reportcard.all',compact('students','sy','quarter','sem'));
    }
    
}
