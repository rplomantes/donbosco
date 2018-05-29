<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

use App\Http\Controllers\Registrar\GradeComputation;
use App\Http\Controllers\Registrar\Ranking\OverallRanking;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Registrar\Ranking\Helper as RankHelper;



class OverallRankController extends Controller
{
    
    function index($sy){
        $levels = \App\CtrLevel::all();
        return view('registrar.overallrank',compact('levels','sy'));
        
    }
    
    function setOARank(){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $quarter = Input::get('quarter');
        $semester = Input::get('semester');
        
        $gradeQuarter = RegistrarHelper::setQuarter($semester, $quarter);
        
        
        
        if(in_array($level,array('Grade 7','Grade 8','Grade 9','Grade 10'))){
            $this->setOARankingTech($level,$sy,$course,$gradeQuarter,$semester);
        }
        return $this->setOARankingAcad($level,$sy,$course,$gradeQuarter,$semester);
    }
    
    function setOARankingAcad($level,$sy,$course,$gradeQuarter,$sem){
        $students = OverallRanking::getStudents($sy, $level, $course);
        $acad_field = RankHelper::rankingField($sem,$gradeQuarter,'acad_level_');
        
        $average = array();
        foreach($students as $student){
            $quarterAverage = GradeComputation::computeQuarterAverage($sy,$level,array(0,5,6),$sem,$gradeQuarter,$student->grade);
            if($quarterAverage <= 0){
                $quarterAverage = 0;
            }
            $average[] = (object)['idno'=>$student->idno,'average'=>(float)$quarterAverage];
        }
        
        $averagelist = collect((object)$average);
        
        $this->updateRanking($averagelist->sortByDesc('average')->values()->all(),$acad_field,$sy);
    }
    
    function setOARankingTech($level,$sy,$course,$gradeQuarter,$sem){
        $students = OverallRanking::getStudents($sy, $level, $course);
        $tech_field = RankHelper::rankingField($sem,$gradeQuarter,'tech_level_');
        
        $average = array();
        foreach($students as $student){
            $quarterAverage = GradeComputation::computeQuarterAverage($sy,$level,array(1),$sem,$gradeQuarter,$student->grade);
            if($quarterAverage <= 0){
                $quarterAverage = 0;
            }
            $average[] = (object)['idno'=>$student->idno,'average'=>(float)$quarterAverage];
        }
        
        $averagelist = collect((object)$average);

        $this->updateRanking($averagelist->sortByDesc('average'),$tech_field,$sy);
    }
    
    function updateRanking($averagelist,$field,$sy){
        $ranking = 0;
        $comparison = 0;
        
        $nextrank = 1;
        foreach($averagelist as $average){

            $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$sy)->first();
            
            if($comparison != $average->average){
                $ranking = $nextrank;
                
                $comparison = $average->average;
            }
            elseif($average->average == 0){
                $ranking = 0;
            } 
            
            if(!$rank){
                $rank = new \App\Ranking();
                $rank->schoolyear = $sy;
                $rank->idno = $average->idno;
            }
            $rank->$field = $ranking;  
            $rank->save();
            $nextrank++;
        }
    }
}

