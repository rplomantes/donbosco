<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function usersPosition(){
        return $this->belongsTo('App\UsersPosition','id','position');
    }
}
