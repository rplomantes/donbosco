<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AcademicDeptincomeController extends Controller
{
    function index($fiscal){
        $income  = DB::Select("Select sum(c.elem) - sum(d.elem) as elementary,sum(c.hs) - sum(d.hs) as high,sum(c.tvet) - sum(d.tvet) as tvet from creditconsolidated join deditconsolidated where accountingcode LIKE '4%';' and fiscalyear = $fiscal");
        $expense  = DB::Select("Select sum(c.elem) - sum(d.elem) as elementary,sum(c.hs) - sum(d.hs) as high,sum(c.tvet) - sum(d.tvet) as tvet from creditconsolidated join deditconsolidated where accountingcode LIKE '5%';' and fiscalyear = $fiscal");
        
        return view('accounting.AcadDeptIncome',compact('income','expense'));
    }
}
