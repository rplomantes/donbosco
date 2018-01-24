<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrevBalance extends Model
{
    public function ledger(){
        return $this->belongsTo('App\Ledger','reference_id');
    }
}
