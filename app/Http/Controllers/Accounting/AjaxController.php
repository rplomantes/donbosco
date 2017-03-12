<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class AjaxController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function filterList(){
        
    }
}
