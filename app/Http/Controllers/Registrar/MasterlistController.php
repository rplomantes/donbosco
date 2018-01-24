<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MasterlistController extends Controller
{
    function index(){
        $level = \App\CtrLevel::get();
        
        return view('registrar.masterlist',compact('level'));
    }
}
