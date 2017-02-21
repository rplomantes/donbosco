<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    //
    function seegrade($idno){
    if(\Auth::user()->accesslevel==env('USER_REGISTRAR')){
        $syissued= DB::Select("select distinct schoolyear from grades where idno = '$idno'");
        $studentname = \App\User::where('idno',$idno)->first();
        return view('registrar.studentgrade',compact('syissued','idno','studentname'));
    } 
    }
    
    function printreportcard(){
        $levels = \App\CtrLevel::get();
        return view('registrar.printreportcard', compact('levels'));
    }
    
}
