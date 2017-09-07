<?php

namespace App\Http\Controllers\Registrar\Ranking;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use DB;
class Helper extends Controller
{
    static function averageStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting){
        $gradefield = RegistrarHelper::getGradeQuarter($quarter);
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        if($currSy == $subjectsetting->schoolyear){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        $subjects = RegistrarHelper::getSubjectType($subjecttype);        
        $averages = DB::Select("SELECT g.idno, ROUND( SUM( $gradefield ) / count( g.idno ) , $subjectsetting->decimal ) AS average "
                . "FROM grades g left join $table s on s.idno = g.idno "
                . "WHERE g.subjecttype IN ($subjects) $section "
                . "AND g.level = '$subjectsetting->level' "
                . "AND g.schoolyear = '$subjectsetting->schoolyear' "
                . "AND g.isdisplaycar = 1 "
                . "AND g.semester = $semester "
                . "AND g.$gradefield >0 "
                . "$course "
                . "GROUP BY idno ORDER BY `average` DESC");
        
        return $averages;
    }
    
    static function weightedStudentGrades($subjecttype,$section,$course,$quarter,$semester,$subjectsetting){
        $gradefield = RegistrarHelper::getGradeQuarter($quarter);
        $currSy = \App\RegistrarSchoolyear::first()->schoolyear;
        if($currSy == $subjectsetting->schoolyear){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        $subjects = RegistrarHelper::getSubjectType($subjecttype);        
        $averages = DB::Select("SELECT g.idno, SUM( ROUND( $gradefield *(weighted/100),$subjectsetting->decimal) ) AS average "
                . "FROM grades g left join $table s on s.idno = g.idno "
                . "AND s.schoolyear = g.schoolyear "
                . "WHERE g.subjecttype IN ($subjects) $section "
                . "AND g.level = '$subjectsetting->level' "
                . "AND g.schoolyear = '$subjectsetting->schoolyear' "
                . "AND g.isdisplaycard = 1 "
                . "AND g.semester = $semester "
                . "$course "
                . "GROUP BY idno ORDER BY `average` DESC");
        
        return $averages;
    }
    
    static function setRanking($averages,$schoolyear,$rankingfield){
        $ranking = 0;
        $comparison = 0;
        $nextrank = 1;
        foreach($averages as $average){
            $check = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear)->get();
                
            if($comparison != $average->average){
                $ranking=$nextrank;
                $comparison = $average->average;
            }
            if($average->average <= 0){
                $ranking = 0;
            } 
            
            if ($check->isEmpty()) { 
                $rank = new \App\Ranking();
                $rank->idno = $average->idno;
                $rank->schoolyear =   $schoolyear;  
            }else{
                $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$schoolyear)->first();
            }
            $log = new \App\Log();
            $log->action = $rankingfield;
            $log->save();
            
            $rank->$rankingfield = $ranking;
            $rank->save();
            $nextrank++;
        }
    }
    
    static function rankingField($semester,$quarter,$subject){
        if($quarter == 5){
            if($semester == 0){
                $rankfield = $subject."final1";
            }else{
                $rankfield = $subject."final".$semester;
            }
        }else{
            $rankfield = $subject."".$quarter;
        }
        
        return $rankfield;
    }
}
