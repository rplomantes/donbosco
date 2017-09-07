<?php

namespace App\Http\Controllers\Registrar\Elective;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Elective\Helper;

class SectionController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    
    function electiveSection(){
        $levels = \App\CtrElectiveSection::groupBy('level')->get();
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        return view('registrar.elective.section',compact('levels','schoolyear'));
    }
    
    function printSection($section){
        $students = Helper::studentsectionlist($section);
        $sectioninfo = \App\CtrElectiveSection::find($section);
        $advisername = "";
        
        $user = \App\User::where('idno',$sectioninfo->adviser)->first();
        if($user){
            $advisername = $user->firstname." ".substr($user->middlename,0,1).". ".$user->lastname;
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper("Legal", "portrait");
        $pdf->loadView('registrar.elective.section_print',compact('students','advisername','sectioninfo'));
        return $pdf->stream();
    }
}
