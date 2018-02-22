<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RptCashReceiptBookSundries extends Model
{
    public function RptCashreceiptBook(){
      return $this->belongsTo('\App\RptCashreceiptBook','refno','refno');
    }
}
