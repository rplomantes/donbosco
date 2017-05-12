<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class SearchNonSutdentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function search(){
       $students = DB::Select("Select fullname,idno from non_students group by trim(fullname) order by fullname LIMIT 0 , 30"); 
       return view("cashier.searchnonstudent",compact('students'));
    }
    
    function viewtransactions($idno){
        $students = DB::Select("SELECT * FROM `non_students` where TRIM(fullname) = (SELECT DISTINCT TRIM(fullname) from non_students where idno = '$idno')");
        return view('cashier.nonstudenttransactions',compact('students'));
    }
    

}
