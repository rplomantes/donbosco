<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Controllers\Registrar\AttendanceController;
use App\Http\Controllers\Registrar\GradeController;

class PermanentRecord extends Controller
{
    public function __construct(){
	$this->middleware('auth');
    }
    
    function index($idno,$sy){
        $section = "";
        $level = "";
        $class_no = "";
        $strand = "";
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        
        if($currSy == $sy){
            $status = \App\Status::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }else{
            $status = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }
        
        if($status){
            $section = $status->section;
            $level = $status->level;
            $class_no = $status->class_no;
            $strand = $status->strand;
        }

        if(in_array($level,array("Grade 11","Grade 12"))){
            return $this->seniorHighRec($idno,$section,$level,$class_no,$sy,$strand);
        }else{
            return null;
        }
    }
    
    function seniorHighRec($idno,$section,$level,$class_no,$sy,$strand){
        //Grade and Conduct
        $grades = \App\Grade::where('schoolyear',$sy)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
        $info = $this->info($idno,$sy);
        $jhschool = "";
        $jhsSy = "";
        $jhsaverage = "";
        
        $prevschoolrec = \App\PrevSchoolRec::where('level','Grade 10')->where('idno',$idno)->first();
        if(count($prevschoolrec)>0){
            $jhschool = $prevschoolrec->school;
            $jhsSy = $prevschoolrec->schoolyear;
            $jhsaverage = $prevschoolrec->finalrate;
        }else{
            $oldrec = \App\Grade::where('level','Grade 10')->where('idno',$idno)->whereIn('subjecttype',array(0,1))->get();
            
            if(count($oldrec)>0){
                $jhschool = "DON BOSCO TECHNICAL INSTITUTE";
                $jhsaverage = GradeController::gradeQuarterAve(array(0),array(0),5,$oldrec,'Grade 10');
                
                foreach($oldrec as $oldrec){
                    $jhsSy = $oldrec->schoolyear;
                }
            }
        }
        
        //Attendance
        $q1dayp = array();
        $q1daya = array();
        $q1dayt = array();
        $q2dayp = array();
        $q2daya = array();
        $q2dayt = array();        
        
        for($i=1; $i < 3 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level);
            $q1dayp[] = $attendance[0];
            $q1daya[] = $attendance[1];
            $q1dayt[] = $attendance[2];
        }
        
        for($i=3; $i < 5 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level);
            $q2dayp[] = $attendance[0];
            $q2daya[] = $attendance[1];
            $q2dayt[] = $attendance[2];
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,650.00,1008.00], 'portrait');
        $pdf->loadView("print.seniorPermanentRec",compact('idno','sy','grades','info','class_no','section','level','strand','q2dayp','q2daya','q2dayt','q1dayp','q1daya','q1dayt','jhschool','jhsSy','jhsaverage'));
        return $pdf->stream();
        //return view("print.seniorPermanentRec",compact('idno','sy','grades','info','class_no','section','level','strand'));
    }
    
    function internal($idno,$sy){
        $section = "";
        $level = "";
        $class_no = "";
        $strand = "";
        $currSy = \App\CtrSchoolYear::first()->schoolyear;
        
        if($currSy == $sy){
            $status = \App\Status::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }else{
            $status = \App\StatusHistory::where('idno',$idno)->where('schoolyear',$sy)->orderBy('id','DESC')->first();
        }
        
        if($status){
            $section = $status->section;
            $level = $status->level;
            $class_no = $status->class_no;
            $strand = $status->strand;
        }

        if(in_array($level,array("Grade 11","Grade 12"))){
            return $this->seniorHighRecInt($idno,$section,$level,$class_no,$sy,$strand);
        }else{
            return null;
        }
    }
    
    function seniorHighRecInt($idno,$section,$level,$class_no,$sy,$strand){
        //Grade and Conduct
        $grades = \App\Grade::where('schoolyear',$sy)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
        $info = $this->info($idno,$sy);
        $jhschool = "";
        $jhsSy = "";
        $jhsaverage = "";
        
        $prevschoolrec = \App\PrevSchoolRec::where('level','Grade 10')->where('idno',$idno)->first();
        if(count($prevschoolrec)>0){
            $jhschool = $prevschoolrec->school;
            $jhsSy = $prevschoolrec->schoolyear;
            $jhsaverage = $prevschoolrec->finalrate;
        }else{
            $oldrec = \App\Grade::where('level','Grade 10')->where('idno',$idno)->whereIn('subjecttype',array(0,1))->get();
            
            if(count($oldrec)>0){
                $jhschool = "DON BOSCO TECHNICAL INSTITUTE";
                $jhsaverage = GradeController::gradeQuarterAve(array(0),array(0),5,$oldrec,'Grade 10');
                
                foreach($oldrec as $oldrec){
                    $jhsSy = $oldrec->schoolyear;
                }
            }
        }
        
        //Attendance
        $q1dayp = array();
        $q1daya = array();
        $q1dayt = array();
        $q2dayp = array();
        $q2daya = array();
        $q2dayt = array();        
        
        for($i=1; $i < 3 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level);
            $q1dayp[] = $attendance[0];
            $q1daya[] = $attendance[1];
            $q1dayt[] = $attendance[2];
        }
        
        for($i=3; $i < 5 ;$i++){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level);
            $q2dayp[] = $attendance[0];
            $q2daya[] = $attendance[1];
            $q2dayt[] = $attendance[2];
        }
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,650.00,1008.00], 'portrait');
        $pdf->loadView("print.seniorPermanentRecInt",compact('idno','sy','grades','info','class_no','section','level','strand','q2dayp','q2daya','q2dayt','q1dayp','q1daya','q1dayt','jhschool','jhsSy','jhsaverage'));
        return $pdf->stream();
        //return view("print.seniorPermanentRec",compact('idno','sy','grades','info','class_no','section','level','strand'));
    }
    
    function info($idno,$sy){
        $name = "";
        $sydate = $sy."-01-01";
        $infos = \App\StudentInfo::where('idno',$idno)->first();
        $student = \App\User::where('idno',$idno)->first();
        $currage = "0";
        
        if(count($infos)>0){
            $name = $student->lastname.", ".$student->firstname." ".$student->middlename;
            $age_year = date_diff(date_create($infos->birthDate), date_create($sydate))->y;
            $age_month = date_diff(date_create($infos->birthDate), date_create('today'))->m;
            $age= $age_year+1;
            $currage = $age;
            
            $infos->age = $currage;
            $infos->gender = $student->gender;
            $infos->name = $name;
            
            
            return $infos;
        }else{
            return "Student information dont exist";
        }
    }
}
