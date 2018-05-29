<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectionEnrollment extends Model
{
    public static function get_Projection($schoolyear,$level,$strand=""){
        $projection = ProjectionEnrollment::where('schoolyear',$schoolyear)->where('level',$level)->where('strand_course',$strand)->first();
        
        if($projection && $projection->projected_count > 0){
            return ['value'=>$projection->projected_count,'display'=>$projection->projected_count];
        }else{
            return ['value'=>0,'display'=>'-'];
        }
    }
}
