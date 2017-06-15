<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AcademicDeptincomeController extends Controller
{
    function index($schoolyear){
        $incomeacct = $this->accounts(4, $schoolyear);
        $expenseacct = $this->accounts(5, $schoolyear);
        
        $departments = $departments = DB::Select("Select distinct main_department from ctr_acct_dept where main_department IN('Elementary Department','High School Department','TVET')");
        return view('accounting.AcadDeptIncome',compact('incomeacct','expenseacct','schoolyear','departments'));
    }
    
    function accounts($acctcode,$schoolyear){
        $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                . "left join (Select accountingcode, SUM( amount ) AS cred, 0 AS deb, acct_department AS coffice "
                . "from credits "
                . "where fiscalyear = $schoolyear and schoolyear IN ($schoolyear,'') "
                . "and isreverse = 0 group by accountingcode,acct_department "
                . "UNION "
                . "Select accountingcode, 0, SUM( amount ) + SUM( checkamount ) AS deb, acct_department AS coffice from dedits "
                . "where fiscalyear = $schoolyear and schoolyear IN ($schoolyear,'') "
                . "and isreverse = 0 group by accountingcode,acct_department) d "
                . "on d.accountingcode=coa.acctcode "
                . "where coa.acctcode LIKE '$acctcode%' order by coa.acctcode asc");

        return $accounts;
    }
}
