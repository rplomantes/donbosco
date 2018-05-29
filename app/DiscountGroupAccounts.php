<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountGroupAccounts extends Model
{
    function discountGroup(){
        return $this->belongsTo(DiscountGroup::class,'type','type');
    }
}
