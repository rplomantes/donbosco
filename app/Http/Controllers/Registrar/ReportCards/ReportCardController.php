<?php

namespace App\Http\Controllers\Registrar\ReportCards;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;

class ReportCardController extends Controller
{
    function sectionCards($sy){
        $levels = \App\CtrLevel::get();
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        
        return view('registrar.reportcard.index',compact('levels','sy','currSY'));
    }
    
    function printSectionCards($sy,$level,$course,$section,$quarter,$sem){
        
        $students = RegistrarHelper::getSectionList($sy, $level, $course, $section);
        
        return view('registrar.reportcard.all',compact('students','sy','quarter','sem'));
    }
    
}
