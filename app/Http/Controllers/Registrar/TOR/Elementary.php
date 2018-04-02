<?php

namespace App\Http\Controllers\Registrar\TOR;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Elementary extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function index($idno){
        return view('registrar.TOR.elementary.index',compact('idno'));
    }
}
