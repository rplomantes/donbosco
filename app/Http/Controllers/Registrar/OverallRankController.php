<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

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
        
        $this->setOARankingAcad($level,$sy,$course,$quarter);
        
        return "go";
    }
    
    function setOARankingAcad($level,$sy,$course,$quarter){
        switch ($quarter){
            case 1;
                $qrt = "first_grading";
            break;
            case 2;
                $qrt = "second_grading";
            break;                
           case 3;
                $qrt = "third_grading";
            break;
            case 4;
                $qrt = "fourth_grading";
           break; 
        }
        
        $schoolyear = \App\RegistrarSchoolyear::first()->schoolyear;
        if($schoolyear == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
        
        
        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10" || $level == "Grade 11" || $level == "Grade 12"){
            
            if($quarter == 5){
                $averages = DB::Select("SELECT grades.idno,ROUND((ROUND( SUM( first_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( second_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( third_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( fourth_grading ) / COUNT( grades.idno ) , 0 ) ) /4, 0 ) as average "
                        . "FROM `grades` "
                        . "left join statuses on statuses.idno = grades.idno "
                        . "WHERE subjecttype IN (0,5,6) "
                        . "AND grades.level = '$level' "
                        . "AND grades.schoolyear = '$sy' "
                        . "AND statuses.strand = '$course' "
                        . "AND isdisplaycard = 1 "
                        . "GROUP BY idno ORDER BY `average` DESC");
            }
            else{
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( $qrt ) / count( grades.idno ) ,0) AS average "
                        . "FROM `grades` "
                        . "left join $table s"
                        . "on s.idno = grades.idno "
                        . "and s.schoolyear = grades.schoolyear "
                        . "WHERE subjecttype IN (0,5,6) "
                        . "AND grades.level = '$level' "
                        . "AND grades.schoolyear = '$sy' "
                        . "AND statuses.strand = '$course' "
                        . "AND isdisplaycard = 1 "
                        . "GROUP BY idno ORDER BY `average` and  DESC");
            }
        }else{
            if($quarter == 5){
                $averages = DB::Select("SELECT grades.idno,ROUND((ROUND( SUM( first_grading ) / COUNT( grades.idno ) , 2 ) + ROUND( SUM( second_grading ) / COUNT( grades.idno ) , 2 ) + ROUND( SUM( third_grading ) / COUNT( grades.idno ) , 2 ) + ROUND( SUM( fourth_grading ) / COUNT( grades.idno ) , 2 ) ) /4, 2 ) as average "
                        . "FROM `grades` "
                        . "left join statuses on statuses.idno = grades.idno "
                        . "WHERE subjecttype IN (0,5,6) "
                        . "AND grades.level = '$level' "
                        . "AND grades.schoolyear = '$sy' "
                        . "AND isdisplaycard = 1 "
                        . "GROUP BY idno ORDER BY `average` DESC");
            }else{
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( $qrt ) / count( grades.idno ) , 2) AS average "
                        . "FROM `grades` "
                        . "left join $table s "
                        . "on s.idno = grades.idno "
                        . "and s.schoolyear = grades.schoolyear"
                        . "WHERE subjecttype IN (0,5,6) "
                        . "AND grades.level = '$level' "
                        . "AND grades.schoolyear = '$sy' "
                        . "AND isdisplaycard = 1 "
                        . "GROUP BY idno "
                        . "ORDER BY `average` DESC");
            }            
        }

        
        
        $ranking = 0;
        $comparison = 0;
        
        $nextrank = 1;
        foreach($averages as $average){
            
            
            $check = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$sy)->get();
            
            if($comparison != $average->average){
                $ranking = $nextrank;
                
                $comparison = $average->average;
            }
            elseif($average->average == 0){
                $ranking = 0;
            } 
            
            
            
            if ($check->isEmpty()) { 
                $rank = new \App\Ranking();
            }else{
                $rank = \App\Ranking::where('idno',$average->idno)->where('schoolyear',$sy)->first();
            }
            
            if($check->isEmpty()){
                $rank->idno = $average->idno;
            }
                switch ($quarter){
                  case 1;
                        $rank->oa_acad_1 = $ranking;
                break;
                    case 2;
                        $rank->oa_acad_2 = $ranking;
                    break;                
                   case 3;
                        $rank->oa_acad_3 = $ranking;
                    break;
                    case 4;
                        $rank->oa_acad_4 = $ranking;
                   break;
                    case 5;
                        $rank->oa_acad_final = $ranking;
                   break;
               
                }
            $rank->schoolyear =   $sy;  
            $rank->save();
            $nextrank++;
        }
        
        return $level;
    }
    
    function setOARankingTech($level,$sy,$course,$quarter){
        switch ($quarter){
            case 1;
                $qrt = "first_grading";
            break;
            case 2;
                $qrt = "second_grading";
            break;                
           case 3;
                $qrt = "third_grading";
            break;
            case 4;
                $qrt = "fourth_grading";
           break; 
        }
        
        $schoolyear = \App\RegistrarSchoolyear::first()->schoolyear;
        if($schoolyear == $sy){
            $table = 'statuses';
        }
        else{
            $table = 'status_histories';
        }
    }
}
