<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class SheetAController extends Controller
{
    function index(){
        $sys = DB::Select("Select distinct schoolyear from (SELECT DISTINCT schoolyear FROM  status_histories UNION SELECT schoolyear FROM ctr_school_years)a order by schoolyear DESC");
        $currsy = \App\CtrSchoolYear::first();
        $levels = \App\CtrLevel::get();
        return view('registrar.sheetA',compact('sys','levels','currsy'));
        
    }
}
