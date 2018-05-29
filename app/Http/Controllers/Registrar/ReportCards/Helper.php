<?php

namespace App\Http\Controllers\Registrar\ReportCards;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Registrar\GradeComputation;

class Helper extends Controller
{
    static function studentPassedHS($grades){
        $subject = $grades->filter(function($item){
            return in_array($item->subjecttype,array(5,6,0,1));
        });
        foreach($subject as $grade){
            if($grade->final_grade < 75){
                return false;
            }
        }
        return true;
    }
    
    static function studentPassedElem($sy,$level,$grades){
        $average = GradeComputation::computeQuarterAverage($sy,$level,array(0),0,5,$grades);
        if($average != "" && $average >=75){
            return true;
        }else{
            return false;
        }
        
    }
    
    static function promoted($level){
        switch($level){
           case "Kindergarten":
               return "Grade 1";
           case "Grade 1":
               return "Grade 2";
           case "Grade 2":
               return "Grade 3";
           case "Grade 3":
               return "Grade 4";
           case "Grade 4":
               return "Grade 5";
           case "Grade 5":
               return "Grade 6";
           case "Grade 6":
               return "Grade 7";
           case "Grade 7":
               return "Grade 8";
           case "Grade 8":
               return "Grade 9";
           case "Grade 9":
               return "Grade 10";
           case "Grade 10":
               return "Grade 11";
           case "Grade 11":
               return "Grade 12";
           case "Grade 12":
               return "College";
           default:
               return "";
               
        }
    }
    static function issueDate($level){
        switch($level){
           case "Kindergarten":
               return "April 10, 2018";
           case "Grade 1":
               return "April 10, 2018";
           case "Grade 2":
               return "April 10, 2018";
           case "Grade 3":
               return "April 10, 2018";
           case "Grade 4":
               return "April 10, 2018";
           case "Grade 5":
               return "April 10, 2018";
           case "Grade 6":
               return "April 10, 2018";
           case "Grade 7":
               return "April 10, 2018";
           case "Grade 8":
               return "April 10, 2018";
           case "Grade 9":
               return "April 10, 2018";
           case "Grade 10":
               return "April 10, 2018";
           case "Grade 11":
               return "April 11, 2018";
           case "Grade 12":
               return "April 10, 2018";
           default:
               return "";
               
        }
    }
}
