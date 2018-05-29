<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    public function user(){
        return $this->belongsTo('\App\User','idno','idno');
    }
    
    public function tvetCourse(){
        return TvetCourse::where('course',  $this->course)->first();
    }
    
    public function grade(){
        return $this->hasMany('\App\Grade','idno','idno') ;
        
    }
    
    public function CtrLevel(){
        return $this->hasOne('\App\CtrLevel','level','level');
    }
    
    public function ranking(){
        return $this->belongsTo('\App\Ranking','idno','idno');
    }
}
