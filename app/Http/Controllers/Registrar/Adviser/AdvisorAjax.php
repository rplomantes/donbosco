<?php

namespace App\Http\Controllers\Registrar\Adviser;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdvisorAjax extends Controller
{
    function modal_createAdviser(){
        return view('registrar.adviser.modals.makeAdviser')->render();
    }
}
