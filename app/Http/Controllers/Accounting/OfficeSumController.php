<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class OfficeSumController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    function index($fromdate,$todate,$dept,$acctcode){
        $departments = DB::Select("Select distinct main_department from ctr_acct_dept where main_department NOT IN('Elementary Department','High School Department','TVET','Pastoral Department')");
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $offices = DB::Select("Select sub_department from ctr_acct_dept where main_department ='$dept' ");
        $coas = \App\ChartOfAccount::where('acctcode','LIKE',$acctcode.'%')->orderBy('accountname','ASC')->get();
        $accounts = $this->accounts($fromdate,$todate,$dept,$acctcode,$schoolyear);


        return view('accounting.officeSummary',compact('fromdate','todate','dept','accounts','offices','acctcode','departments','coas'));
    }

    function printOfficeSum($fromdate,$todate,$dept,$acctcode){
        $offices = DB::Select("Select sub_department from ctr_acct_dept where main_department ='$dept' ");
        $coas = \App\ChartOfAccount::where('acctcode','LIKE',$acctcode.'%')->orderBy('accountname','ASC')->get();
        $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
        $accounts = $this->accounts($fromdate,$todate,$dept,$acctcode,$schoolyear);


        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView('print.officeSummary',compact('fromdate','todate','dept','accounts','offices','acctcode','coas'));
        return $pdf->stream();
    }
    
    static function accounttotal($accounts,$acctcode,$accounttype){
        $total = 0;
        $credit = 0;
        $debit = 0;
        foreach($accounts as $account){
            if($account->acctcode == $acctcode){
                $credit = $credit + $account->cred;
                $debit = $debit + $account->deb;
            } 
        }
        
        if($accounttype == 4 || $accounttype == 3 || $accounttype == 2){
            $total = $credit - $debit;
        }else{
            $total = $debit - $credit;
        }
        
        if($total != 0){
            return $total;
        }else{
            return 0;
        }
    }
    
    static function deptTotal($accounts,$dept,$accounttype){
        $total = 0;
        $credit = 0;
        $debit = 0;
        foreach($accounts as $account){
            
            if(strcmp(preg_replace("/[\s+',]/","",$account->coffice),preg_replace("/[\s+',]/","",$dept)) == 0){
                $credit = $credit + $account->cred;
                $debit = $debit + $account->deb;
            } 
        }
        
        if($accounttype == 4){
            $total = $credit - $debit;
        }else{
            $total = $debit - $credit;
        }
        return number_format($total,2,' .',',');
    }

    static function accountdepttotal($accounts,$dept,$acctcode,$accounttype){
        $total = 0;
        $credit = 0;
        $debit = 0;
        foreach($accounts as $account){
            
            if(($account->acctcode == $acctcode) AND (strcmp(preg_replace("/[\s+',]/","",$account->coffice),preg_replace("/[\s+',]/","",$dept)) == 0)){
                $credit = $credit + $account->cred;
                $debit = $debit + $account->deb;
 
            } 
        }
        
        if($accounttype == 4){
            $total = $credit - $debit;
        }else{
            $total = $debit - $credit;
        }
        
        if($total != 0){
            return number_format($total,2,' .',',');
        }else{
            return "";
        }     
    }
    
    static function showAcct($accounts,$depts,$acctcode,$accounttype){
        $total = 0;
        
        foreach($depts as $dept){
            $total = $total + abs(OfficeSumController::accountdepttotal($accounts,$dept->sub_department,$acctcode,$accounttype));
        }
        
        if($total != 0 ){
            return true;
        }else{
            return false;
        }
    }
    
    function checkfiscalyear($fromdate,$todate){
        $fiscalstart = "05-01";
        $fiscalend   = "04-31";
        $fiscalyear = date("Y",  strtotime($fromdate));
        
        if(strtotime($fromdate)<=strtotime($fiscalyear."-".$fiscalstart)){
            $fiscalyear = $fiscalyear;
        }else{
            $fiscalyear=$fiscalyear+1;
        }
        
        if(strtotime($todate)<=strtotime($fiscalyear."-".$fiscalend)){
            $fiscalend = $todate;
        }else{
            $fiscalend = $fiscalyear."-".$fiscalend;
        }
        
        return array('enddate'=>$fiscalend,'fiscal',$fiscalyear);
    }
    
    function accounts($fromdate,$todate,$dept,$acctcode,$schoolyear){
        if($acctcode == 1){
        $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                . "left join (Select accountingcode, SUM( amount ) AS cred, 0 AS deb, sub_department AS coffice "
                . "from credits "
                . "where (transactiondate between '$fromdate' AND '$todate') "
                . "and acct_department = '$dept' "
//                . "and fiscalyear = $schoolyear "
//                . "and schoolyear IN ($schoolyear,'') "
                . "and isreverse = 0 group by accountingcode,sub_department "
                . "UNION "
                . "Select accountingcode, 0, SUM( amount ) + SUM( checkamount ) AS deb, sub_department AS coffice from dedits "
                . "where (transactiondate between '$fromdate' AND '$todate') "
                . "and acct_department = '$dept' "
//                . "and fiscalyear = $schoolyear "
                . "and isreverse = 0 group by accountingcode,sub_department) d "
                . "on d.accountingcode=coa.acctcode "
                . "where (coa.acctcode BETWEEN 140100 AND 140116) order by coa.acctcode asc");
        }else{
            $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                    . "left join (Select accountingcode, SUM( amount ) AS cred, 0 AS deb, sub_department AS coffice "
                    . "from credits "
                    . "where (transactiondate between '$fromdate' AND '$todate') "
                    . "and acct_department = '$dept' "
//                    . "and fiscalyear = $schoolyear "
                    . "and isreverse = 0 group by accountingcode,sub_department "
                    . "UNION "
                    . "Select accountingcode, 0, SUM( amount ) + SUM( checkamount ) AS deb, sub_department AS coffice from dedits "
                    . "where (transactiondate between '$fromdate' AND '$todate') "
                    . "and acct_department = '$dept' "
//                    . "and fiscalyear = $schoolyear "
                    . "and isreverse = 0 group by accountingcode,sub_department) d "
                    . "on d.accountingcode=coa.acctcode "
                    . "where coa.acctcode LIKE '$acctcode%' order by coa.acctcode asc");            
        }        
        return $accounts;
    }
}

