<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DBFixer extends Controller
{
    function addOldStudent(){
        return view('vincent.tools.addOldStudent');
    }
}
