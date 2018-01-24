<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Controllers\Cashier\AddtoAccountController;
class AddtoBatchController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }
    
    function batchposting(){
        if(\Auth::user()->accesslevel==env('USER_CASHIER')){
        $acctcode = \App\CtrOtherPayment::distinct()->select('accounttype')->orderBy('accounttype')->get();
        $acct_departments = DB::Select('Select * from ctr_acct_dept order by sub_department');
        return view('vincent.cashier.addbatchaccount',compact('acctcode','acct_departments'));
        }
    }
    
    function savebatchposting(Request $request){
        if(\Auth::user()->accesslevel==env('USER_CASHIER')){
        foreach($request->idnumber as $id){
            AddtoAccountController::savetoaccount($id,$request->all());         
        }

        $students = DB::Select("Select distinct statuses.idno,lastname,firstname,middlename,extensionname,statuses.section from statuses join users on users.idno = statuses.idno join ctr_sections on ctr_sections.section = statuses.section where statuses.idno IN(".implode(',',$request->idnumber).") order by ctr_sections.id,lastname,firstname,middlename,extensionname");
        
        return view('vincent.cashier.studentwithacct',compact('students','request'));
        }
    }
}
