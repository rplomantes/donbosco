<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GLController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    

}
