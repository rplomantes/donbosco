<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }     
    
    function sheetA(){
        if(Auth::User()->accesslevel != env('USER_REGISTRAR')){
            return redirect('/');
        }
        $levels = $this->get_level();
        return view('vincent.registrar.sheetA', compact('levels'));
    }

    function printDate(){
        $print=new Carbon();
        $print::setToStringFormat('F j, Y g:i:s a');
        $print::today(); 
        
        return "".$print."";
    }    
    
    function printSheetAElem($level,$section,$subject){
        if(Auth::User()->accesslevel != env('USER_REGISTRAR')){
            return redirect('/');
        }        
        $today = date("F d, Y");
        
        $print =      $this->printDate();  

        
        $schoolyear = \App\CtrRefSchoolyear::first();
        $quarters = \App\CtrQuarter::first();
        $quarter = ''.$quarters->qtrperiod;
        if(strpos($subject, ':') !== false){
        $subject = str_replace(":","/",$subject);
        }
        if($subject == "All"){
            if($level == 'Kindergarten'){
                $subjects = \App\CtrSubjects::where('level',$level)->whereIn('subjecttype',array(0,1))->where('isdisplaycard',1)->get();
            }else{
                $subjects = \App\CtrSubjects::where('level',$level)->whereIn('subjecttype',array(0,1))->get();
            }
            
            
        }else{
            $subjects = \App\CtrSubjects::where('subjectcode',$subject)->where('level',$level)->whereIn('subjecttype',array(0,1))->get();
             
        }
        
        $students = DB::Select("SELECT statuses.idno as idno,class_no,lastname, firstname, middlename, extensionname,statuses.status as stat FROM users JOIN statuses ON statuses.idno = users.idno WHERE statuses.status IN(2,3) AND schoolyear = '$schoolyear->schoolyear' AND level ='$level'  AND section = '$section' ORDER BY class_no ASC");

        return view('vincent.registrar.sheetAprint',compact('students','subjects','today','print','schoolyear','level','section','quarter'));
    }
    
    function printSheetASHS($level,$strand,$section,$subject,$sem){
        if(Auth::User()->accesslevel != env('USER_REGISTRAR')){
            return redirect('/');
        }        
        $today = date("F d, Y");
        
        $print =      $this->printDate();  

        $schoolyear = \App\CtrRefSchoolyear::first();
        $quarters = \App\CtrQuarter::first();
        $quarter = ''.$quarters->qtrperiod;
        
        if($subject == "All"){
                $subjects = \App\Grade::select(DB::raw('DISTINCT(subjectname),subjectcode'))->where('strand',$strand)->whereIn('subjecttype',array(5,6))->where('semester',$sem)->get();
                if($level != "Grade 11"){
                    $subjects = \App\CtrSubjects::where('level',$level)->whereIn('subjecttype',array(0,1))->get();
                }
        }else{
                $subjects = \App\Grade::select(DB::raw('DISTINCT(subjectname),subjectcode'))->where('strand',$strand)->whereIn('subjecttype',array(5,6))->where('subjectcode',$subject)->where('semester',$sem)->get();
                if($level != "Grade 11"){
                    $subjects = \App\CtrSubjects::where('subjectcode',$subject)->where('level',$level)->whereIn('subjecttype',array(0,1))->get();
                }
        }
        if($level == "Grade 11" || $level == "Grade 12"){
            return view('vincent.registrar.sheetAprintSHS',compact('subjects','today','print','schoolyear','level','section','quarter','sem'));
        }else{
            return view('vincent.registrar.sheetAprintHS',compact('subjects','today','print','schoolyear','level','section','quarter'));
        }
        //return $subjects;
    }        
    
    function conduct(){
     if(Auth::User()->accesslevel != env('USER_REGISTRAR')){
        return redirect('/');
     }

       $levels = $this->get_level();

     
     return view('vincent.registrar.conduct',compact('levels'));
 }
 
    function printSheetAConduct($level,$section,$quarter){
        $today = date("F d, Y");
        $print = $this->printDate();  

             
        $schoolyear = \App\CtrRefSchoolyear::first();
        $students = DB::Select("SELECT first,second,third,fourth,statuses.idno as idno,class_no,lastname, firstname, middlename, extensionname,statuses.status as stat FROM users left join (SELECT idno,sum(first_grading) as first,sum(second_grading) as second,sum(third_grading) as third,sum(fourth_grading) as fourth FROM `grades` where subjecttype=3 and schoolyear = '$schoolyear->schoolyear' GROUP BY idno)conduct on conduct.idno = users.idno JOIN statuses ON statuses.idno = users.idno WHERE statuses.status IN (2,3) AND schoolyear = '$schoolyear->schoolyear' AND level ='$level'  AND section = '$section' ORDER BY class_no ASC");
        $adviser = DB::table('ctr_sections')->where('level',$level)->where('section',$section)->first();
        
        return view('vincent.registrar.sheetAConduct',compact('students','today','print','schoolyear','level','section','quarter','adviser'));
        
    }
    
    function printSheetaAttendance($level,$section,$quarter){
        $today = date("F d, Y");
        
        $print =      $this->printDate();  
             
        $schoolyear = \App\CtrRefSchoolyear::first();
        $subjects = DB::select("Select distinct subjectname as subjectname from ctr_subjects where level='$level' AND subjecttype = 2 order by sortto ASC");
        
        $students = DB::Select("SELECT statuses.idno as idno,class_no,lastname, firstname, middlename, extensionname,statuses.status as stat FROM users JOIN statuses ON statuses.idno = users.idno WHERE statuses.status IN (2,3) AND schoolyear = '$schoolyear->schoolyear' AND level ='$level'  AND section = '$section' ORDER BY class_no ASC");
        $adviser = DB::table('ctr_sections')->where('level',$level)->where('section',$section)->first();
        
        return view('vincent.registrar.sheetaAttendance',compact('students','today','print','schoolyear','level','section','quarter','adviser','subjects'));
    }    
    
    function attendance(){
     
        $levels = $this->get_level();

        return view('vincent.registrar.attendance',compact('levels'));
 }    
 
    function sheetB(){
        $today = date("F d, Y");
        
        $print =      $this->printDate();  
        
        $schoolyear = \App\CtrRefSchoolyear::first();

        //$levels = DB::Select("Select distinct level,department from ctr_levels order by id asc");
        $levels = $this->get_level();
        $tvet = DB::Select("SELECT distinct course as courses FROM `ctr_subjects` WHERE `department` LIKE 'TVET'");
        return view('vincent.registrar.sheetB',compact('levels','tvet','today','print','schoolyear')) ;
            
    }
 
    function get_level(){
        if(Auth::User()->accesslevel == env('USER_ACADEMIC_HS'))
        {
            $level = \App\CtrLevel::where('department','Senior High School')->orWhere('department','Junior High School')->get();
        }else if(Auth::User()->accesslevel == env('USER_ACADEMIC_ELEM'))
            {
            $level = \App\CtrLevel::where('department','Kindergarten')->orWhere('department','Elementary')->get();
        }else{
            $level = \App\CtrLevel::get();
        }
        
        return $level;
    } 
    
    
    function finalreport(){
        $today = date("F d, Y");
        
        $print =      $this->printDate();  
        
        $schoolyear = \App\CtrRefSchoolyear::first();

        $levels = $this->get_level();
        $tvet = DB::Select("SELECT distinct course as courses FROM `ctr_subjects` WHERE `department` LIKE 'TVET'");
        return view('vincent.registrar.FinalReport',compact('levels','tvet','today','print','schoolyear')) ;
            
    }    
 
}
