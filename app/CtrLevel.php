<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrLevel extends Model
{
    public function ctrLevelStrands(){
        return $this->hasMany(CtrLevelsStrand::class,'level','level');
    }
}
