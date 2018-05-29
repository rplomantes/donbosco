<?php

namespace App\Http\Controllers\Registrar\Grade;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;

class ChangeGrade extends Controller
{
    function index($idno,$sy){
        $level = "";
        $section = "";
        $student = \App\User::where('idno',$idno)->first();
        $currSY = \App\CtrSchoolYear::first()->schoolyear;
        
        if($currSY == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        $status = DB::Select("Select * from $table where idno = $idno and schoolyear = $sy");
        
        foreach($status as $status){
            $level = $status->level;
            $section = $status->section;
        }

        
        return view('registrar.grade.changeGrade',compact('student','level','section','sy'));
        
    }
    
    function getForm($type){
        $sy  = Input::get('sy');
        $idno  = Input::get('idno');
        
        switch($type){
            case 'grade';
                $subjects = \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->whereIn('subjecttype',array(0,1,5,6))->where('isdisplaycard',1)->orderBy('sortto')->get();
                return view('ajax.changeGrade',compact('subjects','sy','idno','type'));
                break;
            case 'conduct';
                $subjects = \App\Grade::where('idno',$idno)->where('schoolyear',$sy)->where('subjecttype',3)->get();
                return view('ajax.changeGrade',compact('subjects','sy','idno','type'));
                break;
            case 'attendance';
                $subjects = \App\Attendance::where('idno',$idno)->where('schoolyear',$sy)->get();
                break;
        }
    }
    
    function updateGrade(Request $request){
        $find = \App\Grade::find($request->student);
        if($find){
            switch($request->quarter){
                case 1:
                    $quarter = "first_grading";
                    break;
                case 2:
                    $quarter = "second_grading";
                    break;
                case 3:
                    $quarter = "third_grading";
                    break;                
                case 4:
                    $quarter = "fourth_grading";
                    break;                
                default:
                    $quarter = "";
                    break;
            }
            
            $log = new \App\Log();
            $log->action = "info";
            $log->message = "Student ".$find->idno." has a grade change for ".$find->subjectname." quarter ".$request->quarter.", for schooyear ".$find->schoolyear.". Change from ".$find->$quarter." to ".$request->grade;
            $log->save();
            
            $find->$quarter = $request->grade;
            $find->save();
        }
        return $find->id;
    }
}
