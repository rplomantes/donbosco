<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    function Dedit(){
        return $this->hasMany("\App\Dedit","refno","refno");
    }
}
