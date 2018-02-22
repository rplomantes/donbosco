<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dedit extends Model
{
 
    function user(){
        return $this->belongsTo("\App\User");
        
    }
    
    function Credit(){
        return $this->hasMany("\App\Credit","refno","refno");
    }
}
