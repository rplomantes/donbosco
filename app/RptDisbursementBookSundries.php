<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RptDisbursementBookSundries extends Model
{
    public function RptDisbursementBook(){
      return $this->belongsTo('\App\RptDisbursementBook','refno','refno');
    }
}
