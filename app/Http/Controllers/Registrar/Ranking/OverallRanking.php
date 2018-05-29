<?php

namespace App\Http\Controllers\Registrar\Ranking;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Registrar\Ranking\Helper as RankHelper;
use Illuminate\Support\Facades\Input;
use DB;

class OverallRanking extends Controller
{
    function index($selectedSY){
        $currSY = \App\ctrSchoolYear::first()->schoolyear;
        $levels = \App\CtrLevel::get();
        return view('registrar.overallrank.index',compact('selectedSY','currSY','levels'));
    }
    
    function getOARanking(){
        
        $level = Input::get('level');
        $sy = Input::get('sy');
        $semester = Input::get('semester');
        $quarter = Input::get('quarter');
        $strand = Input::get('course');
        $sort = Input::get('sort');
        
        $gradeQuarter = RegistrarHelper::setQuarter($semester, $quarter);
        $acad_field = RankHelper::rankingField($semester,$gradeQuarter,'acad_level_');
        $tech_field = RankHelper::rankingField($semester,$gradeQuarter,'tech_level_');
        $attendanceQtr = RegistrarHelper::setAttendanceQuarter($semester, $quarter);
        $gradeField = RegistrarHelper::getGradeQuarter($gradeQuarter);
        
        $students = self::getStudents($sy,$level,$strand,$acad_field);
        $subjects = RegistrarHelper::getLevelSubjects($level,$strand,$sy,$semester);
        
        if($sort == 'name'){
            $students = $students->sortBy('user.lastname');
        }
        if($sort == 'acad'){
            $students = $students->sortBy('ranking.'.$acad_field);
        }
        if($sort == 'tech'){
            $students = $students->sortBy('ranking.'.$tech_field);
        }
        
        return view('ajax.overallRank',compact('gradeQuarter','students','level','semester','subjects','sy','quarter','strand','attendanceQtr','gradeField','acad_field','tech_field','sort','gradeQuarter'))->render();
        
    }
        

    
    static function getStudents($sy,$level,$course){
        
        $status = \App\Status::with(['grade' =>function($query)use($sy){
                    $query->where('schoolyear',$sy)->where('isdisplaycard',1)->whereIn('subjecttype',array(0,1,5,6))->orderBy('sortto','ASC');
                },'ranking'=>function($query)use($sy){
                    $query->where('schoolyear',$sy);
                },'user'])->where('level',$level)->where('schoolyear',$sy)->whereIn('status',array(2,3))->get();
                
        $status_history = \App\StatusHistory::with(['grade' =>function($query)use($sy){
                    $query->where('schoolyear',$sy)->where('isdisplaycard',1)->whereIn('subjecttype',array(0,1,5,6))->orderBy('sortto','ASC');
                },'ranking'=>function($query)use($sy){
                    $query->where('schoolyear',$sy);
                }])->where('level',$level)->where('schoolyear',$sy)->whereIn('status',array(2,3))->get();
        
        $students = $status->union($status_history)->unique('idno')->sortBy('class_no');
        
        if($course == "All"){
            $students = $students->filter(function($item){
                return in_array($item->strand,array('STEM','ABM'));
            });
        }
        
        if($course != "null" && $course != "All"){
            $students = $students->where('strand',$course,false);
        }
        
        return $students->sortBy('ranking.acad_level_1');
    }
}
