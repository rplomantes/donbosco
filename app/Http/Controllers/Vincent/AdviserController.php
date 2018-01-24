<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdviserController extends Controller
{
    function addedtecher(){
        return view('vincent.adviser.addteacher');
    }
}
