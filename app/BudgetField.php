<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetField extends Model
{
    public function budgetFieldAccounts(){
        return $this->hasMany(BudgetFieldAccounts::class,'field','entry_code');
    }
}
