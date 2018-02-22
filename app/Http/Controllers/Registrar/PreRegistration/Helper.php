<?php

namespace App\Http\Controllers\Registrar\PreRegistration;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class Helper extends Controller
{
    function findHits(Request $request){
        $lastname = strtolower(str_replace(" ","",$request->lastname));
        $firstname = strtolower(str_replace(" ","",$request->firstname));
        
        $filteredName = strtolower(str_replace(" ","",$lastname.$firstname));
        
        //$hits = DB::Select("SELECT * FROM users WHERE LOWER(REPLACE(CONCAT(lastname,firstname),' ','')) LIKE '%$filteredName%'");
        $hits = DB::Select("SELECT * FROM users WHERE LOWER(REPLACE(lastname,' ','')) LIKE '%$lastname%' AND LOWER(REPLACE(firstname,' ','')) LIKE '%$firstname%'");
        
        return view('registrar.preRegistration.ajax.namehits',compact('hits','filteredName'));
    }
}
