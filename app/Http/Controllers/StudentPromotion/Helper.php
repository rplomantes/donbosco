<?php

namespace App\Http\Controllers\StudentPromotion;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Helper extends Controller
{
    static function selected($student_conduct,$student_academic,$student_technical,$probation_type,$probation_code){
        if($probation_type == 'conduct'){
            if($probation_code == $student_conduct){
                return true;
            }else{
                return false;
            }
        }elseif($probation_type == 'acad'){
            if($probation_code == $student_academic){
                return true;
            }else{
                return false;
            }
        }elseif($probation_type == 'tech'){
            if($probation_code == $student_technical){
                return true;
            }else{
                return false;
            }
        }
    }
}
