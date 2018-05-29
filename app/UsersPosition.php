<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersPosition extends Model
{
    public function positions(){
        return $this->hasOne('App\Position','id','position');
    }
    
    public function users(){
        return $this->belongsTo('\App\User','idno','idno');
    }
}
