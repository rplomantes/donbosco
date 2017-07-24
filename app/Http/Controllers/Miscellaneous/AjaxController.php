<?php

namespace App\Http\Controllers\Miscellaneous;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AjaxController extends Controller
{

    
    function findstudent($accesslevel,$search){
            $find = strtolower($search);
            
            if($accesslevel == env("USER_ELEM")){
                $students = DB::Select("Select * From users u join statuses s on s.idno = u.idno where u.accesslevel = '0' AND (lastname like '$search%' OR lcase(lastname) like '$find%' OR firstname like '$search%' OR u.idno = '$search') AND s.status = 2 and s.department IN ('Kindergarten','Elementary Department') Order by lastname, firstname");
            }elseif($accesslevel == env("USER_HS")){
                $students = DB::Select("Select * From users u join statuses s on s.idno = u.idno where u.accesslevel = '0' AND (lastname like '$search%' OR lcase(lastname) like '$find%' OR firstname like '$search%' OR u.idno = '$search') AND s.status = 2 and s.department IN ('Junior High School','Senior High School') Order by lastname, firstname");
            }else{
            $students = DB::Select("Select * From users where accesslevel = '0' AND (lastname like '$search%' OR lcase(lastname) like '$find%' OR "
                    . "firstname like '$search%' OR idno = '$search') Order by lastname, firstname");                
            }

            
            return view('ajax.searchstudent',compact('students'));

    }
    
    function getsearchbookstore($student){
        $students = DB::Select("Select u.idno,lastname,firstname,extensionname,middlename from statuses s join users u on s.idno = u.idno where s.status IN (2,3) and (firstname LIKE '%$student%' OR middlename LIKE '%$student%' OR s.idno LIKE '%$student%' OR lastname LIKE '%$student%')");
        
        return view("ajax.bookStudentSearch",compact('students'));
    }
    
    function unclaim($id){
            $book = \App\Ledger::find($id);
            $book->status = 0;
            $book->save();
    }
}

                