<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MakeRecord extends Controller
{
    function createRecord($idno){
        return view('registrar.createRec',compact('idno'));
    }
}
