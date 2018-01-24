<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TvetSection extends Model
{
 use SoftDeletes;   
 
 public function tvetCourse(){
     return $this->belongsTo('\App\TvetCourse','course_id','course_id');
 }
}
