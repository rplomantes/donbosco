<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SectionController extends Controller
{
    //
     public function __construct()
	{
		$this->middleware('auth');
	}
        
     function sectioning($sy){
         $levels = \App\CtrLevel::all();
         return view('registrar.sectioning',compact('levels','sy'));   
     }
        
     function sectionk(){
         $levels = \App\CtrLevel::all();
         //return $levels;
         return view('registrar.sectionkpage',compact('levels'));
     }   
    function printsection($level, $section){
        $sy = \App\CtrSchoolYear::first();
        $schoolyear=$sy->schoolyear;
        $ad = \App\CtrSection::where('level',$level)->where('section',$section)->first();
          $adviser = $ad->adviser;
         $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section, isnew from statuses, users where statuses.idno = "
                        . "users.idno and statuses.level = '$level' AND schoolyear = '$schoolyear' AND statuses.section = '$section' order by users.gender,users.lastname, users.firstname, users.middlename");
   
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper("Folio", "portrait");
        $pdf->loadView('print.printsection',compact('studentnames','adviser','level','section','schoolyear'));
        return $pdf->stream();

        //return $studentnames;
    }
    
      function printsection1($level, $section, $strand){
          $sy = \App\CtrSchoolYear::first();
        $schoolyear=$sy->schoolyear;
          $ad = \App\CtrSection::where('level',$level)->where('section',$section)->where('strand',$strand)->first();
          $adviser = $ad->adviser;
           $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section,statuses.class_no,isnew from statuses, users where statuses.idno = "
                        . "users.idno and statuses.level = '$level' AND schoolyear = '$schoolyear' AND statuses.section = '$section' and strand = '$strand' order by users.gender, users.lastname, users.firstname, users.middlename");
           
           if (count($studentnames) == 0){
           $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname,users.gender, "
                        . "users.firstname, users.middlename, statuses.section,statuses.class_no,isnew from statuses, users where statuses.idno = "
                        . "users.idno and statuses.period = '$level'  AND statuses.section = '$section' and course = '$strand' order by users.gender, users.lastname, users.firstname, users.middlename");               
           if (count($studentnames) != 0){
           $level = "Batch ".$level;}
           }
   
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper("Legal", "portrait");
         $pdf->loadView('print.printsection',compact('studentnames','level','section','strand','adviser','schoolyear'));
        return $pdf->stream();

           //return $studentnames;
    }
    
    function assignClassNo(){
        $level = array("Kindergarten","Grade 1","Grade 2","Grade 3","Grade 4","Grade 5","Grade 6","Grade 7","Grade 8","Grade 9","Grade 10","Grade 11","Grade 12");
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        foreach($level as $level){
            $sections = \App\CtrSection::where('level',$level)->get();
            foreach($sections as $section){
                $students = DB::Select("select statuses.idno,statuses.section, isnew from statuses, users where statuses.idno = "
                                . "users.idno and statuses.level = '$level' AND schoolyear = '$schoolyear' AND statuses.section = '$section->section' order by class_no,users.gender,users.lastname, users.firstname, users.middlename");
                $class_no = 1;

                 foreach($students as $student){
                     $stud = \App\Status::where('idno',$student->idno)->where('schoolyear',$schoolyear)->first();

                     if($stud){
                         if($stud->class_no == 0){
                             $stud->class_no = $class_no;
                             $stud->save();
                             $class_no++;
                         }
                     }
                 }
                
            }
        }
    }
}
