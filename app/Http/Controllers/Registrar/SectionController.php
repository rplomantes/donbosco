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
        
     function sectionk(){
         $levels = \App\CtrLevel::all();
         //return $levels;
         return view('registrar.sectionkpage',compact('levels'));
     }   
    function printsection($level, $section){
        $sy = \App\CtrRefSchoolyear::first();
        $schoolyear=$sy->schoolyear;
        $ad = \App\CtrSection::where('level',$level)->where('section',$section)->first();
          $adviser = $ad->adviser;
         $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section from statuses, users where statuses.idno = "
                        . "users.idno and statuses.level = '$level'  AND statuses.section = '$section' order by users.gender,users.lastname, users.firstname, users.middlename");
   
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper("Folio", "portrait");
        $pdf->loadView('print.printsection',compact('studentnames','adviser','level','section','schoolyear'));
        return $pdf->stream();

        //return $studentnames;
    }
    
      function printsection1($level, $section, $strand){
          $sy = \App\CtrRefSchoolyear::first();
        $schoolyear=$sy->schoolyear;
          $ad = \App\CtrSection::where('level',$level)->where('section',$section)->where('strand',$strand)->first();
          $adviser = $ad->adviser;
           $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname, "
                        . "users.firstname, users.middlename, statuses.section from statuses, users where statuses.idno = "
                        . "users.idno and statuses.level = '$level'  AND statuses.section = '$section' and strand = '$strand' order by users.gender, users.lastname, users.firstname, users.middlename");
           
           if (count($studentnames) == 0){
           $studentnames = DB::Select("select statuses.id, statuses.idno, users.lastname,users.gender, "
                        . "users.firstname, users.middlename, statuses.section from statuses, users where statuses.idno = "
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
}
