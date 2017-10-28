<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrAccountsGroup extends Model
{
    function accountgroup(){
        return $this->hasMany(accounts_group::class,'group');
    }
}
