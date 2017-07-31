<?php

namespace App\Http\Controllers\Registrar\Elective;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Elective\Helper;
class SheetAController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrElectiveSection::groupBy('level')->get();
        
        return view('elective.sheeta_index',compact('selectedSY','currSY','levels'));
    }
    
    function printElective($section){
        $info = \App\CtrElectiveSection::find($section);
        $students = Helper::studentsectionlist($section);
        $adviser = Helper::electiveadviser($section);
        $sectionname = Helper::sectionName($section);
        $quarter = \App\CtrQuarter::first()->quarter;
        return view('elective.sheeta_print',compact('section','students','sem','adviser','sectionname','info','quarter'));
    }
    
}
