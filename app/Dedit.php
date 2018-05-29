<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Accounting\Disbursement\Helper as DisbHelper;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

class Dedit extends Model
{
 
    function user(){
        return $this->belongsTo("\App\User");
        
    }
    
    function Credit(){
        return $this->hasMany("\App\Credit","refno","refno");
    }
   
}
