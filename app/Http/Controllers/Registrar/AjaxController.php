<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Controllers\Registrar\Elective\Helper;

class AjaxController extends Controller
{
    function displaygrade(){
        $sy = Input::get('sy');
        $idno = Input::get('idno');
        
        $grades = \App\Grade::where('idno',$idno)->where('sy',$sy)->get();
    }
    
    function getelectives($action = null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $electives = \App\CtrElectiveSection::where('level',$level)->where('schoolyear',$sy)->groupBy('elective')->get();
        return view('ajax.selectelective',compact('electives','action'));
    }
    
    function getelectivesection($action = null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $elective = Input::get('elective');
        $allavailable = 0;
        $sections = \App\CtrElectiveSection::where('level',$level)->where('schoolyear',$sy)->where('elecode',$elective)->get();
        return view('ajax.selectelectivesection',compact('sections','allavailable','action'));
    }
    
    function electivesheetAsection($action = null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $sections = \App\CtrElectiveSection::where('level',$level)->where('schoolyear',$sy)->get();
        return view('ajax.electivesectionsheetA',compact('sections','action'));
    }
    
    function sheetAelectivelist(){
        $section = Input::get('section');
        $sem = \App\CtrElectiveSection::find($section)->sem;
        $students = Helper::studentsectionlist($section);
        return view('ajax.electiveSheetA',compact('section','students','sem'));
    }
            
    function strandStudent($level){
        $strnd = Input::get('strand');
        if($strnd == 'null'){
            $strand = "";
        }elseif($strnd == 'All'){
            $strand = "AND s.strand != ''";
        }else{
            $strand = "AND s.strand = '".$strnd."'";
        }
        
        $action = "addtosection";
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $students = DB::Select("Select distinct u.idno,g.section as classify,s.strand from statuses s join users u on u.idno = s.idno join grades g on g.idno = s.idno and s.schoolyear = g.schoolyear where s.schoolyear = '$schoolyear' and s.level = '$level' $strand and s.status = 2 and subjectcode = 'ELE1' order by lastname,firstname");

        return view('ajax.studentlistelective',compact('students','action'));
    }
    
    function electiveadviser(){
        $section = Input::get('section');
        $adviser = \App\CtrElectiveSection::find($section);
        $advisername = "";
        $user = \App\User::where('idno',$adviser->adviser)->first();
        if($user){
            $advisername = $user->firstname." ".substr($user->middlename,0,1).". ".$user->lastname;
        }
        return view('ajax.getAdviser',compact('advisername'));
    }
    
    function electiveStudent(){
        $section = Input::get('section');
        $students = Helper::studentsectionlist($section);
        $action = "removetosection";
        return view('ajax.electivesectionlist',compact('students','action','section'));
    }
        
    function addtoelesection(){
        $idno = Input::get('idno');
        $sy = Input::get('sy');
        $section = Input::get('section');
        
        $student = \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->where('subjectcode','ELE1')->update(['section'=>$section]);
        
        return $idno;
    }
    
    function removetoelesection(){
        $idno = Input::get('idno');
        $sy = Input::get('sy');
        
        \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->where('subjectcode','ELE1')->update(['section'=>""]);
        
        
    }
    
    
    
    function levelStudent($level,$sy){
        $strnd = Input::get('strand');
        if($strnd == 'null'){
            $strand = "";
        }elseif($strnd == 'All'){
            $strand = "AND strand != ''";
        }else{
            $strand = "AND strand = '".$strnd."'";
        }
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        if($schoolyear == $sy){
            $students = DB::Select("Select distinct users.idno,section as classify,strand from statuses join users on users.idno = statuses.idno where schoolyear = '$sy' and level = '$level' $strand and statuses.status = 2 order by lastname,firstname");
        }else{
            $students = DB::Select("Select distinct users.idno,section as classify,strand from status_histories where schoolyear = '$sy' and level  = '$level' $strand and status_histories.status IN (2,3) order by lastname,firstname");
        }
        
        return view('ajax.studentlist',compact('students')); 
    }
    
    function getsectionstudents(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $strand = Input::get('strand');
        $section = Input::get('section');
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        
        if($strand == 'null'){
            $strand = '';
        }
        
        if($schoolyear == $sy){
            $students = DB::Select("Select distinct s.idno,section,strand,class_no from statuses s join users u on s.idno=u.idno where schoolyear = '$sy' and level = '$level' and strand = '$strand' and section = '$section' order by  class_no =0,class_no,gender,lastname,firstname");
        }else{
            $students = DB::Select("Select distinct s.idno,section,strand,class_no from status_histories s join users u on s.idno = u.idno where schoolyear = '$sy' and level  = '$level' and strand = '$strand' and section = '$section' order by class_no =0,class_no,gender, lastname,firstname");
        }
        
        return view('ajax.sectionlist',compact('students'));
        
    }
    
    function masterlist($level,$strand){
        $students = DB::Select("Select *,s.idno as studno from statuses s join users u on s.idno = u.idno where schoolyear = $sy and s.status = 2 and s.level = '$level' and strand = '$strand' order by lastname,firstname");
        
        return $students;
    }
    
    function viewmasterlist($level,$strand,$section){
        
        $students = $this->masterlist($level,$strand);
        
        return view("ajax.masterlistview",compact());
    }
    

    
    
    function getoverallrank(){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $quarter = Input::get('quarter');
        switch($quarter){
            case 1:
                $sort = "oa_acad_1";
                break;
            case 2:
                $sort = "oa_acad_2";
                break;
            case 3:
                $sort = "oa_acad_3";
                break;
            case 4:
                $sort = "oa_acad_4";
                break;
            default:
                $sort = "oa_acad_final";
                break;
        }
                    
        
        if($schoolyear == $sy){
            $students = DB::Select("Select s.idno,section,oa_acad_1,oa_acad_2,oa_acad_3,oa_acad_4,oa_acad_final,oa_tech_1,oa_tech_2,oa_tech_3,oa_tech_4,oa_tech_final from statuses s left join rankings r on s.idno = r.idno and s.schoolyear = r.schoolyear where s.schoolyear = '$sy' and s.level  = '$level' and strand = '$course' group by s.idno order by $sort ASC");
        }else{
            $students = DB::Select("Select s.idno,section,oa_acad_1,oa_acad_2,oa_acad_3,oa_acad_4,oa_acad_final,oa_tech_1,oa_tech_2,oa_tech_3,oa_tech_4,oa_tech_final from status_histories s left join rankings r on s.idno = r.idno and s.schoolyear = r.schoolyear where s.schoolyear = '$sy' and s.level  = '$level' and strand = '$course' group by s.idno  order by $sort ASC");
        }
        
        if($level == 'Grade 9' || $level == 'Grade 10' || $level == 'Grade 11' || $level == 'Grade 12'){
            if($level == 'Grade 11' || $level == 'Grade 12'){
                $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->where('strand',$course)->where('semester',1)->orderBy('sortto','ASC')->get();
            }else{
                $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
            }
        }else{
            $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
        }
        
        
        return view('ajax.overallrank',compact('students','subjects','level','course','sy','quarter'));
        
    }

    function autoSectioning($level,$strand='null'){
        if($strand == 'null'){
            $strand = '';
        }
        
        $sy = \App\ctrSchoolYear::first()->schoolyear;
        $prevSy = $sy-1;
        $topstudents = DB::Select("Select *,s.idno as studno from statuses s join rankings r on s.idno = r.idno where r.schoolyear = $prevSy and s.schoolyear = $sy and s.status = 2 and s.level = '$level' and s.strand = '$strand' order by oa_acad_final DESC LIMIT 0 , 20");
        $this->sectionStudents($sy,$level,$strand,$topstudents);
        
	$students = DB::Select("Select *,s.idno as studno from statuses s join users u on s.idno = u.idno where schoolyear = $sy and s.status = 2 and strand = '$strand' and level='$level' order by lastname,firstname");
        $this->sectionStudents($sy,$level,$strand,$students);
        
	return null;
    }
    
    
    function sectionStudents($sy,$level,$strand,$students){
        $sections = DB::Select("Select Distinct section as sec from ctr_sections where level = '$level' and strand = '$strand'");
        $indexCount = count($sections);
        $index = 0;
        
        foreach($students as $student){
            $stud = \App\Status::where('idno',$student->studno)->where('schoolyear',$sy)->first();
            if($index >= $indexCount){
                $index = 0;
            }
            if(($stud->section == "") && ($stud->status ==2)){
                $stud->section  = $sections[$index]->sec;
                $stud->save();
                $index++;
            }
        }
	return $sections;
        
    }
    
}
