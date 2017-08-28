<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;

class GradeComputation extends Controller
{
    static function computeSubjectAverage($sy,$level,$grades){
        
        
        foreach($grades as $grade){
            $gradeCondition = \App\GradesSetting::where('schoolyear',$sy)->where('level',$level)->where('subjecttype',$grades->subjecttype)->first();
            
            $finalgrade = \App\Grade::where('idno',$grade->idno)->where('subjectcode',$grade->subjectcode)->where('schoolyear',$grade->schoolyear)->first();
            
            if($grade->semester == 0 && $grade->fourth_grading != 0){
                $finalgrade->final_grade = round($finalgrade->first_grading+$finalgrade->second_grading+$finalgrade->third_grading+$finalgrade->fourth_grading,$gradeCondition->decimal);
            }elseif($grade->semester == 1 && $grade->second_grading != 0){
                $finalgrade->final_grade = round($finalgrade->first_grading+$finalgrade->second_grading,$gradeCondition->decimal);
            }elseif($grade->semester == 2 && $grade->fourth_grading != 0){
                $finalgrade->final_grade = round($finalgrade->third_grading+$finalgrade->fourth_grading,$gradeCondition->decimal);
            }else{
                $finalgrade->final_grade = 0;
            }
            $finalgrade->save();
        }
    }
    
    static function computeQuarterAverage($sy,$level,$subjecttype,$sem,$quarter,$grades){
        $total = 0;
        $average = 0;
        $gradeCondition = \App\GradesSetting::where('schoolyear',$sy)->where('level',$level)->whereIn('subjecttype',$subjecttype)->first();
        $field = RegistrarHelper::getGradeQuarter($quarter);
        
        foreach($grades as $grade){
            if(in_array($grade->subjecttype,$subjecttype) && $grade->semester == $sem && $grade->$field != 0){
                $total = $total + 1;
                $average = $average + $grade->$field;
            }
        }
        if($total == 0 || $average == 0){
            return "";
        }
        $average = round($average/$total,$gradeCondition->decimal);
        
        return $average;
        
    }
}
