<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StudentlistController extends Controller
{
    
public function studentlist()
{ 
$lists=DB::Select("SELECT level from ctr_levels ORDER BY id");
        
    return view('registrar/studentlist', compact('lists'));
  
    
   
}

public function printinfo(){
    $schoolyear = \App\CtrRefSchoolyear::first();
        $sy=$schoolyear->schoolyear;
        $levels = \App\CtrLevel::all();
    return view('registrar.printinfo',compact('sy','levels'));
}

}