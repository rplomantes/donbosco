<?php

namespace App\Http\Controllers\Registrar\ReportCards;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class TVETCards extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except'=>['get_section','get_classList']]);
    }
    //Display view
    function view(){
        $batches = \App\CtrSchoolYear::where('department','TVET')->orderBy('period','ASC')->get();
        $courses = \App\TvetCourse::all();
        
        return view('registrar.TVET.ReportCard.index',compact('batches','courses'));
        
    }
    
    //Individual student record
    function TVETStudentCard($batch,$idno,$sem){
        return self::viewCard($batch,$idno,$sem);
    }
    
    //Display card of whole section
    function TVETClassCard($batch,$sectionId,$sem){
        
    }
    
    //This just to display the card
    public static function viewCard($batch,$idno,$sem){
        $student_info = \App\Status::where('idno',$idno)->where('period',$batch)->first();
        
        $grade = \App\TvetGrade::where('batch',$batch)->where('idno',$idno)->where('sem',$sem)->orderBy('order','ASC')->get();
        $attitude = \App\TvetAttitudeResult::with(['tvetAttitude'=>function($query){
            $query->orderBy('order');
        }])->where('batch',$batch)->where('idno',$idno)->where('semester',$sem)->get();
        
        $attendance = \App\TvetAttendance::where('batch',$batch)->where('idno',$idno)->where('sem',$sem)->orderBy('order','ASC')->get();
        $daysOfSchool = \App\TvetSchoolDay::where('batch',$batch)->where('course_id',$student_info->tvetCourse()->course_id)->get();

        if(self::detectBrowser() == "Chrome"){
            return view('registrar.TVET.ReportCard.formats.chrome',compact('student_info','sem'));
        }else{
            return $attitude;
        }
        
        
    }
    
    /*-----------Underlying Scripts---------*/
    
    //Please dont repeate the same query again, and again, and again
    function sectionList($section){
        $students = \App\Status::join('users', 'users.idno', '=', 'statuses.idno')->where('section',$section->section)->where('course',$section->TvetCourse->course)->where('period',$section->batch)->whereIn('statuses.status',array(2,3))->orderBy('users.lastname')->orderBy('users.firstname')->get();
        return $students;
    }
    
    //Print with the right browse, would YA!!!!
    public static function detectBrowser(){
        $agent = new Agent();
        return $agent->browser();
    }
    
    /*-------------Ajax Fields-------------*/

    function get_section(Request $request){
        $batch = $request->batch;
        $courseId = $request->course;
        
        $sections = \App\TvetSection::where('batch',$batch)->where('course_id',$courseId)->orderBy('section')->get();
        
        return view('registrar.TVET.ReportCard.ajax.tvetSection',compact('sections'));
        
    }
    function get_classList(Request $request){
        $sectionId = $request->section;
        $semester = $request->semester;
        
        $section = \App\TvetSection::find($sectionId);
        
        $students = $this->sectionList($section);
        
        return view('registrar.TVET.ReportCard.ajax.classList',compact('students','semester'));
    }
    
    /*-------------EEND Ajax Fields-------------*/
}
