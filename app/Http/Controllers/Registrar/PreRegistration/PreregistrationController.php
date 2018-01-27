<?php

namespace App\Http\Controllers\Registrar\PreRegistration;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PreregistrationController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    
    function preregForm(){
        return view('registrar.preRegistration.pre_regForm');
    }
}
