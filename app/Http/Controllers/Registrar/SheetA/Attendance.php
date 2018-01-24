<?php

namespace App\Http\Controllers\Registrar\SheetA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Registrar\SheetA\Helper as SheetAHelper;

class Attendance extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        
        return view('registrar.sheetA.attendance',compact('selectedSY','currSY','levels'));
    }
    
    function printSheetA($sy,$level,$course,$section,$semester,$qtr){
        $students = RegistrarHelper::getSectionList($sy,$level,$course,$section);
        $quarter = RegistrarHelper::setAttendanceQuarter($semester,$qtr);
        return view('registrar.sheetA.printAttendance',compact('students','section','semester','quarter','sy','level','quarter','qtr'));
    }
}
