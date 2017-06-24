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
        $syissued= DB::Select("select distinct schoolyear from grades where idno = '$idno'");
        $studentname = \App\User::where('idno',$idno)->first();
        return view('registrar.studentgrade',compact('syissued','idno','studentname'));
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
                if($grades->first_grading == 0){
                    $dividend++;
                }
                if($grades->second_grading == 0){
                    $dividend++;
                }
                if($grades->third_grading == 0){
                    $dividend++;
                }
                if($grades->fourth_grading == 0){
                    $dividend++;
                }
                $grade = ($grades->first_grading + $grades->second_grading +$grades->third_grading + $grades->fourth_grading)/4;
                break;
        }

        if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10"){
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
    
    static function gradeQuarterAve($insubjtype,$quarter,$subjects,$level){
        $total = 0;
        $grade = 0;
        foreach($subjects as $subject){
            if(in_array($subject->subjecttype,$insubjtype)){
                $total = $total + 1;
                
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
                    default:
                        if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10"){
                            $grade = $grade + round($subject->final_grade,0);
                        }else{
                            $grade = $grade + round($subject->final_grade,2);
                        }
                        break;
                }
            }
        }
        
        if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10"){
            $average = round($grade/$total,0);
            //$average =$total;
        }else{
            $average = round($grade/$total,2);
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
        
        return $grade;
    }
}

