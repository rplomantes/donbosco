<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    //
    function seegrade($idno){
    if(\Auth::user()->accesslevel==env('USER_REGISTRAR')){
        $syissued= DB::Select("select schoolyear from (select distinct schoolyear from grades where idno = '$idno' "
                . "UNION "
                . "select distinct schoolyear from prev_school_recs where idno ='$idno') v order by schoolyear ASC");
        $studentname = \App\User::where('idno',$idno)->first();
        $shspermanentRec = \App\StatusHistory::where('idno',$idno)->where('department',"Senior High School")->first();
        return view('registrar.studentgrade',compact('syissued','idno','studentname','shspermanentRec'));
    } 
    }
    
    function printreportcard(){
        $levels = \App\CtrLevel::get();
        return view('registrar.printreportcard', compact('levels'));
    }
    
    static function acadstudentAverage($sy,$quarter,$level,$idno){
        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10"){
        switch ($quarter){
            case 1;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( first_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
            break;
            case 2;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( second_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
            break;                
           case 3;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( third_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
            break;
            case 4;
                $averages = DB::Select("SELECT grades.idno,ROUND( SUM( fourth_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
           break; 
            case 5;
                $averages = DB::Select("SELECT grades.idno,ROUND((ROUND( SUM( first_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( second_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( third_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( fourth_grading ) / COUNT( grades.idno ) , 0 ) ) /4, 0 ) as average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND schoolyear = '$sy' and idno = $idno AND isdisplaycard = 1");
           break; 
        }
        }elseif($level == "Grade 11" || $level == "Grade 12"){
            switch ($quarter){
                case 1;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( first_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and semester = 1 and idno = $idno");
                break;
                case 2;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( second_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and semester = 1 and idno = $idno");
                break;                
               case 3;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( third_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and semester = 2 and idno = $idno");
                break;
                case 4;
                    $averages = DB::Select("SELECT grades.idno,ROUND( SUM( fourth_grading ) / count( grades.idno ) ,0) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND grades.level = '$level' AND statuses.section LIKE '$section' AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and semester = 2 and idno = $idno");
               break; 
                case 5;
                    $averages = DB::Select("SELECT grades.idno,ROUND((ROUND( SUM( first_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( second_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( third_grading ) / COUNT( grades.idno ) , 0 ) + ROUND( SUM( fourth_grading ) / COUNT( grades.idno ) , 0 ) ) /4, 0 ) AS average FROM `grades` left join statuses on statuses.idno = grades.idno WHERE subjecttype IN (0,5,6) AND schoolyear = '$sy' AND isdisplaycard = 1 and semester = 2 and idno = $idno");
               break; 
            }
        }
        else{
            switch ($quarter){
                case 1;
                    $averages = DB::Select("SELECT idno,ROUND( SUM( first_grading ) / count( grades.idno ) ,2) AS average FROM `grades` WHERE subjecttype IN (0,5,6) AND schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
                break;
                case 2;
                    $averages = DB::Select("SELECT idno,ROUND( SUM( second_grading ) / count( grades.idno ) ,2) AS average FROM `grades` WHERE subjecttype IN (0,5,6) AND schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
                break;                
               case 3;
                    $averages = DB::Select("SELECT idno,ROUND( SUM( third_grading ) / count( grades.idno ) ,2) AS average FROM `grades` WHERE subjecttype IN (0,5,6) AND schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
                break;
                case 4;
                    $averages = DB::Select("SELECT idno,ROUND( SUM( fourth_grading ) / count( grades.idno ) ,2) AS average FROM `grades` WHERE subjecttype IN (0,5,6)  AND schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
                break; 
                case 5;
                    $averages = DB::Select("SELECT grades.idno, ROUND((ROUND( SUM( first_grading ) / COUNT( grades.idno ) , 2 ) + ROUND( SUM( second_grading ) / COUNT( grades.idno ) , 2 ) + ROUND( SUM( third_grading ) / COUNT( grades.idno ) , 2 ) + ROUND( SUM( fourth_grading ) / COUNT( grades.idno ) , 2 ) ) /4, 2 )  AS average "
                            . "FROM `grades` WHERE subjecttype IN (0,5,6) AND grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno");
               break;
            }
        }
        
        $ave = 0;
        
        foreach($averages as $average){
            $ave = $average->average;
        }
        
        return $ave;

    }
    
    static function gradeSubjectAve($quarter,$grades,$level){
        $grade = 0;
        $dividend = 0;
        switch($quarter){
            case 1:       
                $grade = $grades->first_grading;
                break;
            case 2:
                $grade = $grades->second_grading;
                break;
            case 3:
                $grade = $grades->third_grading;
                break;
            case 4:
                $grade = $grades->fourth_grading;
                break;
            default:
                $grade = $grades->final_grade;
                break;
        }

        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10" || $level == "Grade 11" || $level == "Grade 12"){
            $grade = ROUND($grade,0);
        }else{
            if($grade < 100 && $quarter == 5){
                $grade = number_format(ROUND($grade,2),2);
            }else{
                $grade = ROUND($grade,0);
            }
            
        }
        
        return $grade;
    }
    
    static function gradeQuarterAve($insubjtype,$sem,$quarter,$subjects,$level){
        $total = 0;
        $grade = 0;
        foreach($subjects as $subject){
            if(in_array($subject->subjecttype,$insubjtype)){
                
                if(in_array($subject->semester, $sem)){
                    $total = $total + 1;
                    switch($quarter){
                        case 1:       
                            $grade = $grade +$subject->first_grading;
                            break;
                        case 2:
                            $grade = $grade+$subject->second_grading;
                            break;
                        case 3:
                            $grade = $grade + $subject->third_grading;
                            break;
                        case 4:
                            $grade = $grade + $subject->fourth_grading;
                            break;
                        default:
                            if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10" | $level == "Grade 11" | $level == "Grade 12"){
                                $grade = $grade + round($subject->final_grade,0);
                            }else{
                                $grade = $grade + round($subject->final_grade,2);
                            }                            
                            break;
                    }   
                }
            }
        }
        if($total == 0){
            return "";
        }
        
        if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10" | $level == "Grade 11" | $level == "Grade 12"){
            $average = ROUND($grade/$total,0);
        }else{
            $average = ROUND($grade/$total,2);
        }
        
        if($average == 0){
            $average="";
        }
        
        return $average;
    }
    
    static function weightedgradeQuarterAve($insubjtype,$sem,$quarter,$subjects,$level){
        $total = 0;
        $grade = 0;
        $q1 = 0;
        $q2 = 0;
        $q3 = 0;
        $q4 = 0;
        $final = 0;
        foreach($subjects as $subject){
            if(in_array($subject->subjecttype,$insubjtype)){
                
                if(in_array($subject->semester, $sem)){
                    $total = $total + 1;
                    switch($quarter){
                        case 1:       
                            $grade = $grade + $subject->first_grading * ($subject->weighted/100);
                            break;
                        case 2:
                            $grade = $grade+$subject->second_grading*($subject->weighted/100);
                            break;
                        case 3:
                            $grade = $grade + $subject->third_grading*($subject->weighted/100);
                            break;
                        case 4:
                            $grade = $grade + $subject->fourth_grading*($subject->weighted/100);
                            break;
                        default:
                                $q1 = $q1 + $subject->first_grading * ($subject->weighted/100);
                                $q2 = $q2 + $subject->second_grading*($subject->weighted/100);
                                $q3 = $q3 + $subject->third_grading*($subject->weighted/100);
                                $q4 = $q4 + $subject->fourth_grading*($subject->weighted/100);
                            break;
                    }   
                }
            }
        }
        if($quarter >= 5){
            if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10" | $level == "Grade 11" | $level == "Grade 12"){
                $grade = (round($q1,0)+round($q2,0)+round($q3,0)+round($q4,0))/4;
            }else{
                $grade = (round($q1,2)+round($q2,2)+round($q3,2)+round($q4,2))/4;
            }

        }
        
        if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10" | $level == "Grade 11" | $level == "Grade 12"){
            $average = ROUND($grade,0);
            //$average =$grade;
        }else{
            $average = ROUND($grade,2);
        }
        return $average;
    }
    
    static function conductQuarterAve($subjtype,$quarter,$subjects){
        $grade = 0;
        
        foreach($subjects as $subject){
            if($subject->subjecttype == $subjtype){
                switch($quarter){
                    case 1:       
                        $grade = $grade + $subject->first_grading;
                        break;
                    case 2:
                        $grade = $grade + $subject->second_grading;
                        break;
                    case 3:
                        $grade = $grade + $subject->third_grading;
                        break;
                    case 4:
                        $grade = $grade + $subject->fourth_grading;
                        break;
                }
            }
        }

        if($grade == 0){
            $grade = "";
        }
        return $grade;
    }
    
    static function conductTotalAve($grades,$sem){
        $grade = 0;
        if($sem ==0){
            $first =  GradeController::conductQuarterAve(3,1,$grades);
            $second = GradeController::conductQuarterAve(3,2,$grades);
            $third =  GradeController::conductQuarterAve(3,3,$grades);
            $fourth = GradeController::conductQuarterAve(3,4,$grades);
            
            $grade = ($first+$second+$third+$fourth)/4;
        }elseif($sem ==1){
            $first =  GradeController::conductQuarterAve(3,1,$grades);
            $second = GradeController::conductQuarterAve(3,2,$grades);

            
            $grade = ($first+$second)/2;
            
        }elseif($sem ==2){
            $third =  GradeController::conductQuarterAve(3,3,$grades);
            $fourth = GradeController::conductQuarterAve(3,4,$grades);
            
            $grade = ($third+$fourth)/2;
        }
        if($grade == 0){
            $grade = "";
        }        
        return $grade;
    }
}


