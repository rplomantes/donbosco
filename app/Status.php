<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function user(){
        return $this->belongsTo('\App\User','idno','idno');
    }
    
    public function tvetCourse(){
        return TvetCourse::where('course',  $this->course)->first();
    }
    
    public function grade(){
        return Grade::where('idno',$this->idno)->where('schoolyear',  $this->schoolyear)->get();
    }
}
