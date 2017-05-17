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
    
    static function studentSubjectTotalAve($sy,$quarter,$level,$idno,$subjcode){
        if($level == "Grade 7" || $level == "Grade 8" || $level == "Grade 9" || $level == "Grade 10" || $level == "Grade 11" || $level == "Grade 12"){
            if($level == "Grade 11" || $level == "Grade 12"){
                if($quarter == 1 ||$quarter == 2){
                    $averages = DB::Select("SELECT grades.idno,ROUND((first_grading+second_grading)/2,0) AS average FROM `grades` WHERE schoolyear = '$sy' and idno = $idno and subjectcode = '$subjcode'");
                }else{
                    $averages = DB::Select("SELECT grades.idno,ROUND((third_grading+fourth_grading)/2,0) AS average FROM `grades` WHERE schoolyear = '$sy' and idno = $idno and subjectcode = '$subjcode'");    
                }
            }else{
                $averages = DB::Select("SELECT grades.idno,ROUND((first_grading+second_grading+third_grading+fourth_grading)/4,0) AS average FROM `grades` WHERE grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno and subjectcode = '$subjcode'");    
            }
        }else{
            $averages = DB::Select("SELECT grades.idno,ROUND((first_grading+second_grading+third_grading+fourth_grading)/4,2) AS average FROM `grades` WHERE grades.schoolyear = '$sy' AND isdisplaycard = 1 and idno = $idno and subjectcode = '$subjcode'");
        }

        $ave = 3;
        
        foreach($averages as $average){
            $ave = $average->average;
        }
        
        return $ave;
    }
    
    static gradeSubjectGrade($quarter,$grade){
        $acad = 0;
                    switch($quarter){
                        case 1:       
                            $acad = $grade->first_grading;
                            break;
                        case 2:
                            $acad = $grade->second_grading;
                            break;
                        case 3:
                            $acad = $grade->third_grading;
                            break;
                        case 4:
                            $acad = $grade->fourth_grading;
                            break;
                        default:
                            $acad = $grade->first_grading + $grade->second_grading +$grade->third_grading + $grade->fourth_grading;
                            break;
                    }
    }
}
