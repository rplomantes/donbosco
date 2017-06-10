<?php

namespace App\Http\Controllers\Roy;


use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class AcadController extends Controller
{
    //
     public function __construct(){
            $this->middleware('auth');
    }
    
 public function conduct(){
     if(\Auth::user()->accesslevel==env('USER_HS_PRINCIPAL')|| \Auth::user()->accesslevel==env('USER_HS_ASST_PRINCIPAL')){
       $levels = DB::Select("Select level from ctr_levels where department = 'Junior High School' OR department='Senior High School'");  
     }else{
       $levels = DB::Select("Select level from ctr_levels where department = 'Elementary' OR department='Kindergarten'");
     }
     
     return view('roy.conduct',compact('levels'));
 }   
    
}
