<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Accounting\Disbursement\Helper as DisbHelper;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

class Credit extends Model
{
    function Dedit(){
        return $this->hasMany("\App\Dedit","refno","refno");
    }
    
    
}
