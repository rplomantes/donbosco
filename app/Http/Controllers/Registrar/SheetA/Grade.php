<?php

namespace App\Http\Controllers\Registrar\SheetA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use DB;
class Grade extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        
        return view('registrar.sheetA.grade',compact('selectedSY','currSY','levels'));
    }
    
    function printSheetA($sy,$level,$semester,$section,$subject){
        $course = "";
        $setcourse = \App\CtrSection::where('level',$level)->where('section',$section)->where('schoolyear',$sy)->first();
        if(count($setcourse) > 0){
            $course = $setcourse->strand;

        }else{
            $setcourses = DB::Select("Select * from ctr_sections_temp where level = '$level' AND section = '$section' AND schoolyear = '$sy'");
            
            foreach($setcourses as $setcourse){
                $course = $setcourse->strand;
            }
        }
        $quarter = \App\CtrQuarter::first();
        $students = RegistrarHelper::getSectionList($sy,$level,$course,$section);
        
        return view('registrar.sheetA.printSheetA',compact('students','level','section','semester','subject','sy','quarter'));
    }
}
