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

    $stats = DB::Select("select count(statuses.id) as count, statuses.department, statuses.level, strand, course from statuses join ctr_levels on statuses.level=ctr_levels.level "
            . "where status = '2' and schoolyear = '$sy' group by ctr_levels.id ASC, strand, department, course");    
    
    $tvets = DB::Select("select count(id) as count, period, department, level, strand, course from statuses where status = '2' and department='TVET'"
            . " group by period");
    return view('registrar/enrollmentstat', compact('stats','tvets'));
}

}
