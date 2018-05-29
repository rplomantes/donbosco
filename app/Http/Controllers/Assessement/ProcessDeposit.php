<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessDeposit extends Controller
{
    static function activateDeposit($idno){
        \App\StudentDeposit::where('idno',$idno)->where('status','0')->update(['status'=>1]);
    }
    
    static function hasDeposit($idno,$status){
        return \App\StudentDeposit::where('idno',$idno)->where('status',$status)->exists();
    }
    
    static function remainingDeposit($idno,$status){
        return \App\StudentDeposit::where('idno',$idno)->where('status',$status)->get()->sum('amount');
    }
}
