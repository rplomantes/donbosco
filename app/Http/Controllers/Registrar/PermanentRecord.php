<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Controllers\Registrar\AttendanceController;

class PermanentRecord extends Controller
{
    public function __construct(){
	$this->middleware('auth');
    }
    
    function index($idno,$sy){
        $name = "";
        $lrn = "";
        $section = "";
        $level = "";
        $adviser = "";
        $class_no = "";
        $birthdate = "0000-00-00";
        $infos = DB::Select("Select * from users u join student_infos s on u.idno = s.idno where u.idno = '$idno'");
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        $currage = "0";
        
        foreach($infos as $info){
            $name = $info->lastname.", ".$info->firstname." ".substr($info->middlename,0,1);
            $lrn = $info->lrn;
            $birthdate = $info->birthDate;
        }
        
        $age_year = date_diff(date_create($birthdate), date_create('today'))->y;
        $age_month = date_diff(date_create($birthdate), date_create('today'))->m;
        $age= $age_year.".".$age_month;
        $currage = $age;
        
        if($currSy == $sy){
            $status = \App\Status::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }else{
            $status = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }
        
        if($status){
            $section = $status->section;
            $level = $status->level;
            $class_no = $status->class_no;
        }

        if(in_array($level,array("Grade 11","Grade 12"))){
            return $this->seniorHighRec($idno,$section,$level,$class_no,$currage,$sy);
        }else{
            return null;
        }
//        $pdf = \App::make('dompdf.wrapper');
//        $pdf->setPaper('legal', 'portrait');
//        $pdf->loadView("print.seniorPermanentRec",compact('idno','sy','name','lrn','adviser','section','level','grades','totalage','class_no','ctr_attendances','attendances'));
//        return $pdf->stream();

    }
    
    function seniorHighRec($idno,$section,$level,$class_no,$age,$sy){
        $firstsem = \App\Grade::where('schoolyear',$sy)->where('idno',$idno)->where('semester',1)->where('isdisplaycard',1)->get();
        $secondsem = \App\Grade::where('schoolyear',$sy)->where('idno',$idno)->where('semester',2)->where('isdisplaycard',1)->get();
        
        $q1dayp = 0;
        $q1daya = 0;
        $q1dayt = 0;
        $q2dayp = 0;
        $q2daya = 0;
        $q2dayt = 0;        
        
        for($i=1; $i < 3 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level);
            $q1dayp = $q1dayp + $attendance[0];
            $q1daya = $q1daya + $attendance[1];
            $q1dayt = $q1dayt + $attendance[2];
        }
        
        for($i=1; $i < 5 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level);
            $q2dayp = $q2dayp + $attendance[0];
            $q2daya = $q2daya + $attendance[1];
            $q2dayt = $q2dayt + $attendance[2];
        }
        
        return $q1dayp." - ".$q1daya." - ".$q1dayt;
    }
}
