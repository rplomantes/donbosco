<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserProfile extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function index(){
        $user = Auth::user();
        
        return view('user.profile');
    }
}
