<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProcessOtherDiscount extends Controller
{
    static function get_totalDiscount($accounts,$discountcode){
        $discount = \App\CtrDiscount::where('discountcode','LIKE',$discountcode.'%')->get();
        
        $tuitionfee_disc = $discount->sum('tuitionfee');
        $registration_disc = $discount->sum('registrationfee');
        $misc_disc = $discount->sum('miscelaneousfee');
        $elearning_disc = $discount->sum('elearningfee');
        $department_disc = $discount->sum('departmentfee');
        
        self::get_acctDiscount($accounts, $discountPercent)
    }
    
    static function get_acctDiscount($accounts,$discountPercent){
        
    }
}
