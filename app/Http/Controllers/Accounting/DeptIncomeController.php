<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Controllers\Accounting\OfficeSumController;
class DeptIncomeController extends Controller
{
    public function __construct(){
	$this->middleware(['auth']);
    }
    
    function index($acctcode,$fromtran,$totran){
        $departments = array("None","Rectors Office","Student Services","Administration Department","Elementary Department","High School Department","TVET","Pastoral Department");
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $coas = \App\ChartOfAccount::where('acctcode','LIKE',$acctcode.'%')->orderBy('accountname','ASC')->get();
        
        
            $accounts = $this->accounts($fromtran,$totran,$acctcode,$schoolyear);
        
                return view("accounting.deptIncome",compact('accounts','fromtran','totran','acctcode','coas','departments'));
        
    }
    
    function printreport($acctcode,$fromtran,$totran){
        $departments = array("None","Rectors Office","Student Services","Administration Department","Elementary Department","High School Department","TVET","Pastoral Department");
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $coas = \App\ChartOfAccount::where('acctcode','LIKE',$acctcode.'%')->orderBy('accountname','ASC')->get();
        $accounts = $this->accounts($fromtran,$totran,$acctcode,$schoolyear);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,632.00,1008.00],'landscape');
        $pdf->loadView("print.printconsolidateddept",compact('accounts','fromtran','totran','acctcode','coas','departments'));
        return $pdf->stream();
    }
    
    static function accounts($fromdate,$todate,$acctcode,$schoolyear){

        if($acctcode == 1){
        $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                . "left join (Select accountingcode, SUM( amount ) AS cred, 0 AS deb, acct_department AS coffice "
                . "from credits "
                . "where (transactiondate between '$fromdate' AND '$todate') "
                . "and isreverse = 0 group by accountingcode,acct_department "
                . "UNION "
                . "Select accountingcode, 0, SUM( amount ) + SUM( checkamount ) AS deb, acct_department AS coffice from dedits "
                . "where (transactiondate between '$fromdate' AND '$todate') "
                . "and isreverse = 0 group by accountingcode,acct_department) d "
                . "on d.accountingcode=coa.acctcode "
                . "where (coa.acctcode BETWEEN 140100 AND 140116) order by coa.acctcode asc");
        }else{
        $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                . "left join (Select accountingcode, SUM( amount ) AS cred, 0 AS deb, acct_department AS coffice "
                . "from credits "
                . "where (transactiondate between '$fromdate' AND '$todate') "
                . "and isreverse = 0 group by accountingcode,acct_department "
                . "UNION "
                . "Select accountingcode, 0, SUM( amount ) + SUM( checkamount ) AS deb, acct_department AS coffice from dedits "
                . "where (transactiondate between '$fromdate' AND '$todate') "
                . "and isreverse = 0 group by accountingcode,acct_department) d "
                . "on d.accountingcode=coa.acctcode "
                . "where coa.acctcode LIKE '$acctcode%' order by coa.acctcode asc");
        }
        return $accounts;
    }
    
    static function showAcct($accounts,$depts,$acctcode,$accounttype){
        $total = 0;
        
        foreach($depts as $dept){
            $total = $total + abs(OfficeSumController::accountdepttotal($accounts,$dept,$acctcode,$accounttype));
        }
        
        if($total != 0 ){
            return true;
        }else{
            return false;
        }
    }
    
    
    static function returnzero($amount){
        if($amount != 0){
            return number_format($amount,2,'.',',');
        }else{
            return " ";
        }
    }
}


