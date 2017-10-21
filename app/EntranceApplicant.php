<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntranceApplicant extends Model
{
    public function user(){
        return $this->belongsTo('App\User','idno','idno');
    }
}
