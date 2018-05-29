<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrSection extends Model
{
    public function ctrLevel(){
        return $this->belongsTo('\App\CtrLevel','level','level');
    }
    
    public function students(){
        return $this->hasMany('\App\Status','level','level')->where('section',$this->section,false)->where('schoolyear',$this->schoolyear,false);
    }
}
