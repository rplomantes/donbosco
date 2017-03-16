<?php

namespace App\Http\Controllers\Miscellaneous;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AjaxController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function getsearchbookstore($student){
        $students = DB::Select("Select u.idno,lastname,firstname,extensionname,middlename from statuses s join users u on s.idno = u.idno where s.status IN (2,3) and (firstname LIKE '%$student%' OR middlename LIKE '%$student%' OR s.idno LIKE '%$student%' OR lastname LIKE '%$student%')");
        
        return view("ajax.bookStudentSearch",compact('students'));
    }
}

                