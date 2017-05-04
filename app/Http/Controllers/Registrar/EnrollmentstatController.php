<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnrollmentstatController extends Controller
{
    
public function enrollmentstat()
{ 
    $sy = \App\CtrSchoolYear::first()->schoolyear;
    $stats=DB::Select("select count(id) as count, department, level, strand, course from statuses where status = '2' and schoolyear = '$sy'"
        . " group by level, strand, department, course");
        
    return view('registrar/enrollmentstat', compact('stats'));
}

}