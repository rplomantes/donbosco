<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class SectionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function sectionTvet(){
        //$levels = \App\CtrSchoolYear::where('department','TVET');
        $courses = DB::Select("select distinct course from ctr_subjects where department = 'TVET'");
        $levels = \App\ctrSchoolYear::where('department','TVET')->get();
        return view('vincent.registrar.sectiontvetpage',compact('levels','courses'));        
    }
}
