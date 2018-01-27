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
        
        if($course == "NULL" || $course == "null"){
            $getcourse = "";
        } else{
            if($getcourse == "All"){
                $getcourse = "AND s.strand IN('ABM','STEM')" ;
            }else{
                $getcourse = "AND s.strand LIKE '".$course."'" ;
            }
            
        }
        
        $this->setRankingAcad($level,$section,$getcourse,$sy,$quarter,$semester,$course);
        $this->setRankingTech($level,$section,$getcourse,$sy,$quarter,$semester,$course);
        
        return 'me';
    }
    
    function setRankingAcad($level,$section,$course,$sy,$quarter,$semester,$strand){
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
        
        
        if($section == ""){
            if($strand == "All"){
                $rankfield = RankHelper::rankingField($semester,$quarter,'acad_oa_');
            }else{
                //This includes course
                $rankfield = RankHelper::rankingField($semester,$quarter,'acad_level_');
            }
        }else{
            $rankfield = RankHelper::rankingField($semester,$quarter,'acad_');
        }
        
        RankHelper::setRanking($studentAverages, $sy, $rankfield);
        return $studentAverages;
    }
    
    function setRankingTech($level,$section,$course,$sy,$quarter,$semester,$strand){
        $subjecttype = array(1);
        
        $subjectsetting = \App\GradesSetting::where('level',$level)->where('schoolyear',$sy)->whereIn('subjecttype',$subjecttype)->first();
        
        
        if($subjectsetting){
            if($subjectsetting->calculation == "A"){
                $studentAverages = RankHelper::averageStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting);
            }elseif($subjectsetting->calculation == "W"){
                $studentAverages = RankHelper::weightedStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting);
            }

        if($section == ""){
            if($strand == "All"){
                $rankfield = RankHelper::rankingField($semester,$quarter,'tech_oa_');
            }else{
                //This includes course
                $rankfield = RankHelper::rankingField($semester,$quarter,'tech_level_');
            }
            
        }else{
            $rankfield = RankHelper::rankingField($semester,$quarter,'tech_');
        }
            
        $log = new \App\Log();
        $log->action = $rankfield;
        $log->save();

        RankHelper::setRanking($studentAverages, $sy, $rankfield);
        return $studentAverages;
        }
    }
}
