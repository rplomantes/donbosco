<?php

namespace App\Http\Controllers\Assessement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessReservation extends Controller
{
    static function hasReservation($idno,$status){
        return \App\AdvancePayment::where('idno',$idno)->where('status',$status)->exists();
    }
    
    static function remainingReservation($idno,$status){
        return \App\AdvancePayment::where('idno',$idno)->where('status',$status)->get()->sum('amount');
    }
    
    static function activateReservation($idno){
        \App\AdvancePayment::where('idno',$idno)->where('status','2')->update(['status'=>0]);
    }
    
    static function deactivateReservation($idno){
        \App\AdvancePayment::where('idno',$idno)->where('status','0')->update(['status'=>2]);
    }    
    
}
