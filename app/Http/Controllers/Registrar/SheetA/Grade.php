<?php

namespace App\Http\Controllers\Registrar\SheetA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Grade extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        
        return view('registrar.sheetA.grade',compact('selectedSY','currSY','levels'));
    }
    
    function printSheetA($sy,$level,$course,$semester,$section,$subject){
        $students = RegistrarHelper::getSectionList($sy,$level,$course,$section);
        
        return view('ajax.sheetAGrade',compact('students','semester','subject','sy'));
    }
}
