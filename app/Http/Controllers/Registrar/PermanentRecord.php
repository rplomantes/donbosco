<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Registrar\AttendanceController;
use App\Http\Controllers\Registrar\GradeController;
use View;
use App\Http\Controllers\Registrar\GradeComputation;
use App\Http\Controllers\Registrar\AttendanceController as Attendance;
use App\Http\Controllers\Registrar\Helper as MainHelper;

class PermanentRecord extends Controller
{
    public function __construct(){
	$this->middleware('auth');
    }
    
    function viewjuniorPermanentRec($idno,Request $request){
        $header = 0;
        $grade7 = 0;
        $grade8 = 0;
        $grade9 = 0;
        $grade10 = 0;
        
        if(Input::get('header') != null){
            $header = 1;
        }
        if(Input::get('grade7') != null){
            $grade7 = 1;
        }
        if(Input::get('grade8') != null){
            $grade8 = 1;
        }
        if(Input::get('grade9') != null){
            $grade9 = 1;
        }
        if(Input::get('grade10') != null){
            $grade10 = 1;
        }
        
        $oldrec = self::prevSchoolRec('Grade 6',$idno);
        
        $new = \App\Grade::where('idno',$idno)->where('level','Grade 7')->where('schoolyear','2016')->exists();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,602.00,1008.00], 'portrait');

        if($new){
            $pdf->loadView("registrar.permanentRecord.jhsPermanentRecord",compact('idno','header','grade7','grade8','grade9','grade10','oldrec'));
        }else{
            $pdf->loadView("print.juniorOldPermanentRec",compact('idno','header','grade7','grade8','grade9','grade10'));
        }
        
        return $pdf->stream();
        //return view("print.juniorOldPermanentRec",compact('idno','header','grade7','grade8','grade9','grade10'));
    }
    
    function viewelemPermanentRec($idno,Request $request){
        $header = 0;
        $grade1 = 0;
        $grade2 = 0;
        $grade3 = 0;
        $grade4 = 0;
        $grade5 = 0;
        $grade6 = 0;
        
        if(Input::get('header') != null){
            $header = 1;
        }
        if(Input::get('grade1') != null){
            $grade1 = 1;
        }
        if(Input::get('grade2') != null){
            $grade2 = 1;
        }
        if(Input::get('grade3') != null){
            $grade3 = 1;
        }
        if(Input::get('grade4') != null){
            $grade4 = 1;
        }
        if(Input::get('grade5') != null){
            $grade5 = 1;
        }
        if(Input::get('grade6') != null){
            $grade6 = 1;
        }
        
        $oldrec = self::prevSchoolRec('Kindergarten',$idno);
        $new = \App\Grade::where('idno',$idno)->where('schoolyear','2016')->exists();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,602.00,1008.00], 'portrait');
        
        if($new || substr($idno,0,2) == '16'){
            $pdf->loadView("registrar.permanentRecord.elemPermanentRecord",compact('idno','header','grade1','grade2','grade3','grade4','grade5','grade6','oldrec'));
        }else{
            $pdf->loadView("registrar.permanentRecord.elemPermanentRecordOld",compact('idno','header','grade1','grade2','grade3','grade4','grade5','grade6','oldrec'));
        }        
        
        
        return $pdf->stream();
    }
    
    static function hsGradeTemp($idno,$level){
        return view("registrar.permanentRecord.jhsLevelLayout",compact('idno','level'))->render();
    }

    static function elemGradeTemp($idno,$level){
        return view("registrar.permanentRecord.elemLevelLayout",compact('idno','level'))->render();
    }

    static function elemGradeTempOld($idno,$level){
        return view("registrar.permanentRecord.elemLevelLayoutOld",compact('idno','level'))->render();
    }
    
    static function syInfo($idno,$level){
        $info = \App\Status::where('idno',$idno)->whereIn('status',array(2,3))->where('level',$level)->orderBy('id','DESC')->first();
        if(count($info) == 0){
            $info = \App\StatusHistory::where('idno',$idno)->whereIn('status',array(2,3))->where('level',$level)->orderBy('id','DESC')->first();
        }
        return $info;
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
        }elseif(in_array($level,array("Grade 11","Grade 12"))){
            
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
        $pdf->setPaper([0,0,612.00,1008.00], 'portrait');
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
        $pdf->setPaper([0,0,612.00,1008.00], 'portrait');
        $pdf->loadView("print.seniorPermanentRecInt",compact('idno','sy','grades','info','class_no','section','level','strand','q2dayp','q2daya','q2dayt','q1dayp','q1daya','q1dayt','jhschool','jhsSy','jhsaverage'));
        return $pdf->stream();
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
    
    static function prevSchoolRec($level,$idno){
        $school = "";
        $sy = 0;
        $average = "";
        $entered = "";
        $left = "";
        $att = "";
        $action = "";
        
        $prevschoolrec = \App\PrevSchoolRec::where('level',$level)->where('idno',$idno)->first();
        if(count($prevschoolrec)>0){
            $school = $prevschoolrec->school;
            $sy = $prevschoolrec->schoolyear;
            $average = $prevschoolrec->finalrate;
            $entered = $prevschoolrec->dateEntered;
            $left = $prevschoolrec->dateLeft;
            $att = $prevschoolrec->dayp;
            $action = $prevschoolrec->status;
            
        }else{
            $oldrecs = \App\Grade::where('level',$level)->where('idno',$idno)->where('subjecttype',0)->get();
            
            if(count($oldrecs)>0){
                $school = "DON BOSCO TECHNICAL INSTITUTE";
                //$average = GradeController::gradeQuarterAve(array(0),array(0),5,$oldrec,$level);
                
                foreach($oldrecs as $oldrec){
                    $sy = $oldrec->schoolyear;
                }
                
                $average = GradeComputation::computeQuarterAverage($sy, $level, array(0), 0, 5, $oldrecs);
                $entered = "JUNE ".$sy;
                $left = "MARCH ".($sy+1);
                $dayp = array();
                $daya = array();
                $dayt = array();
                for($i=1; $i < 5 ;$i++){
                    if($sy == 2016){
                        $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,$i,$level); 
                    }elseif($sy < 2016){
                        $field = MainHelper::getGradeQuarter($i);
                        $getattendances  = DB::Select("Select * from grades where schoolyear='$sy' and subjectcode= 'dayp'");
                        $getdayp = 0;
                        foreach($getattendances as $getattendance){
                            $getdayp = $getattendance->$field;
                        }
                        $attendance = array($getdayp);
                    }else{
                        $attendance  = AttendanceController::studentQuarterAttendance($idno,$sy,array($i),$level); 
                    }

                    $dayp [] = $attendance[0];
                }
                
                $att = $dayp[0]+$dayp[1]+$dayp[2]+$dayp[3];
                

            }
        }
        
        return array($school,$sy,$average,$att,$entered,$left,$level,$action);
    }    

}
