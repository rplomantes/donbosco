<?php

namespace App\Http\Controllers\Registrar\Ranking;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Ranking\Helper as RankHelper;
use Illuminate\Support\Facades\Input;

class RankController extends Controller
{
    function setRank($section =null){
        $level = Input::get('level');
        $sy = Input::get('sy');
        $course = Input::get('course');
        $quarter = Input::get('quarter');
        $semester = Input::get('semester');
        
        if($section !== null){
            $section = "AND s.section LIKE '".$section."'" ;
        } else{
            $section = "";
        }
        
        if($course != "NULL"){
            $course = "AND s.strand LIKE '".$course."'" ;
        } else{
            $course = "";
        }
        
        $this->setRankingAcad($level,$section,$course,$sy,$quarter,$semester);
        $this->setRankingTech($level,$section,$course,$sy,$quarter,$semester);
    }
    
    function setRankingAcad($level,$section,$course,$sy,$quarter,$semester){
        if(in_array($level,array('Grade 11','Grade 12'))){
            $subjecttype = array(5,6);
        }else{
            $subjecttype = array(0);
        }
        
        $subjectsetting = \App\GradesSetting::where('level',$level)->where('schoolyear',$sy)->whereIn('subjecttype',$subjecttype)->first();
        
        if($subjectsetting->calculation == "A"){
            $studentAverages = RankHelper::averageStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting);
        }elseif($subjectsetting->calculation == "W"){
            $studentAverages = RankHelper::weightedStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting);
        }
        
        if($quarter == 5){
            if($semester == 0){
                $rankfield = "acad_level_final1";
            }else{
                $rankfield = "acad_level_final".$semester;
            }
        }else{
            $rankfield = "acad_level_".$quarter;
        }
        
        RankHelper::setRanking($studentAverages, $sy, $rankfield);
        return $studentAverages;
    }
    
    function setRankingTech($level,$section,$course,$sy,$quarter,$semester){
        $subjecttype = array(1);
        
        $subjectsetting = \App\GradesSetting::where('level',$level)->where('schoolyear',$sy)->whereIn('subjecttype',$subjecttype)->first();
        
            if($subjectsetting->calculation == "A"){
                $studentAverages = RankHelper::averageStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting);
            }elseif($subjectsetting->calculation == "W"){
                $studentAverages = RankHelper::weightedStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting);
            }

            if($quarter == 5){
                if($semester == 0){
                    $rankfield = "tech_level_final1";
                }else{
                    $rankfield = "tech_level_final".$semester;
                }
            }else{
                $rankfield = "tech_level_".$quarter;
            }
            
            $log = new \App\Log();
            $log->action = $rankfield;
            $log->save();
            
            RankHelper::setRanking($studentAverages, $sy, $rankfield);
            return $studentAverages;
        
    }
}
