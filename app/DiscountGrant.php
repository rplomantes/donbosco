<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountGrant extends Model
{
    function discountGroup(){
        return $this->belongsTo(DiscountGroup::class,'discount_type','type');
    }
    
    function discountGrantAccount(){
        return $this->hasMany(DiscountGroupAccounts::class,'discount_type','type');
    }
}
