<?php

namespace App\Http\Controllers\Registrar\Elective;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Elective\Helper;
use Excel;

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
        
        $users = \App\User::whereIn('idno',explode(';',$sectioninfo->adviser))->get();
        foreach($users as $user){
            $advisername = $advisername." ".$user->firstname." ".substr($user->middlename,0,1).". ".$user->lastname.", ";
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper("Legal", "portrait");
        $pdf->loadView('registrar.elective.section_print',compact('students','advisername','sectioninfo'));
        return $pdf->stream();
    }
    
    function downloadSection($section){
        $students = Helper::studentsectionlist($section);
        $sectioninfo = \App\CtrElectiveSection::find($section);
        
        $name = $sectioninfo->level."_".$sectioninfo->schoolyear."_".$sectioninfo->elective."_".$sectioninfo->section;
        Excel::create($name, function($excel) use($students) {
            $excel->sheet('Student List', function($sheet) use($students){
                    $sheet->loadView('elective.electiveSectionDownload')->with('students',$students)
                            ->cells('A1:A1048576', function($cells) {
                        $cells->setAlignment('right');
                      });
            });
        })->export('xlsx');

        return "Export Complete";
    }

}
