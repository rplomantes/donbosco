<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TvetAttitude extends Model
{

    
    public function tvetAttitudeResult(){
        return $this->hasMany('\App\TvetAttitudeResult','attitude_id');
    }
}
