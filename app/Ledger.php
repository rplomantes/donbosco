<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    public function CtrLevel(){
        return $this->hasOne('\App\CtrLevel','level','level');
    }
}
