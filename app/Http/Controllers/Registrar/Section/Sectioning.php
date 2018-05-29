<?php

namespace App\Http\Controllers\Registrar\Section;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Sectioning extends Controller
{
    function viewSectioning(){
        $levels = \App\CtrLevel::all();
        $schoolyear = \App\CtrYear::where('type','schoolyear')->first()->year;
        
        return view('registrar.section.sectioning.sectioning',compact('levels','schoolyear'))->render();
    }
}
