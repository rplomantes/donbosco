<?php

namespace App\Http\Controllers\Roy;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AssessmentController extends Controller
{
   public function __construct()
	{
		$this->middleware('auth');
	}
        
        
}
