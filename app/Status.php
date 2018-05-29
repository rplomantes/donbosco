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
        return $this->hasMany('\App\Grade','idno','idno');
        
    }
    
    public function CtrLevel(){
        return $this->hasOne('\App\CtrLevel','level','level');
    }
    
    public function ranking(){
        return $this->belongsTo('\App\Ranking','idno','idno');
    }
    
    public function ctrSection(){
        return $this->belongsTo('\App\CtrSection','level','level')->where('section',$this->section)->where('schoolyear',  $this->schoolyear);
    }
}
