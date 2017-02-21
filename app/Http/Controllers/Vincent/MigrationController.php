<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class MigrationController extends Controller
{
    function grademigration(){
        $prevgrades = DB::connection('dbti2test')->Select("select * from grade_report;");
        
        return $prevgrades;
    }
}
