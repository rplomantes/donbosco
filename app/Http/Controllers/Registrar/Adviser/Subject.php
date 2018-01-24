<?php

namespace App\Http\Controllers\Registrar\Adviser;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Subject extends Controller
{
    function bySection($level,$section){
        
    }
    
    function byTeacher(){
        $teachers = \App\User::where('accesslevel',30)->orderBy('lastname')->orderBy('firstname')->get();
    }
}
