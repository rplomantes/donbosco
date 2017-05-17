<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxController extends Controller
{
    function levelStudent($level,$sy){
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        if($schoolyear == $sy){
            $students = DB::Select("Select distinct idno,section,strand from statuses where schoolyear = '$sy' and level  = '$level'");
        }else{
            $students = DB::Select("Select distinct idno,section,strand from status_histories where schoolyear = '$sy' and level  = '$level'");
        }
        
        return view('ajax.studentlist',compact('students'));
        
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
                $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->where('strand',$course)->where('quarter',$quarter)->orderBy('sortto','ASC')->get();
            }else{
                $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->where('strand',$course)->orderBy('sortto','ASC')->get();
            }
        }else{
            $subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
        }
        
        
        return view('ajax.overallrank',compact('students','subjects','level','course','sy','quarter'));
        
    }
    
}
