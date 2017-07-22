<?php

namespace App\Http\Controllers\Miscellaneous;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StudentInfoController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function index($idno){
        $users = \App\User::where('idno',$idno)->first();
        $info = \App\User::where('idno',$idno)->first();
        return view('misc.studentinfo',compact('users','info'));
    }
}
