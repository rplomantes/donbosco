<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function getGradeQuarter($quarter){
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
            default:
                $qrt = "final_grade";
           break; 
        }
        return $qrt;
    }
    
    static function getSubjectType($subjecttypes){
        if(count($subjecttypes)>1){
            $subjects = implode(',', $subjecttypes);
        }else{
            $subjects = implode('', $subjecttypes);
        }
        
        return $subjects;
    }
}
