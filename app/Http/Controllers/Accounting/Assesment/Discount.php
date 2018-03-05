<?php

namespace App\Http\Controllers\Accounting\Assesment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Discount extends Controller
{
    function module_info(){
        return ['menu'=>3,'title'=>'Discounts'];
    }
    
    function view(){
        $discounts = $this->get_discounts();
        $module_info = $this->module_info();
        return view('accounting.EnrollmentAssessment.discounts.selectDiscount',compact('module_info','discounts'));
    }
    
    function get_discounts(){
        return \App\CtrDiscount::get();
    }
}
