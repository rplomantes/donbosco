<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TvetAttitudeResult extends Model
{
    
    public function TvetAttitude(){
        return $this->belongsTo('\App\TvetAttitude','attitude_id');
    }
}
