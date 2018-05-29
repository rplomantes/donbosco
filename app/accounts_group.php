<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class accounts_group extends Model
{
    function ctraccountgroup(){
        return $this->belongsTo(CtrAccountsGroup::class,'group');
    }
    
    public function chartofaccount(){
        return $this->belongsTo(ChartOfAccount::class,'accountingcode','acctcode');
    }
}
