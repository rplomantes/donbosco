<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
class DeptIncomeController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index($accountcode,$fromtran,$totran){
                //$accounts = \App\ChartOfAccount::where('acctcode','LIKE',$accountcode.'%')->orderBy('acctcode','ASC')->get();
            $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                    . "left join (Select accountingcode,sum(none) as creditnone,sum(rector) as creditrector,sum(elem) as creditelem,sum(hs) as crediths,sum(tvet) as credittvet,sum(service) as creditservice,sum(admin) as creditadmin,sum(pastoral) as creditpastoral "
                    . "from creditconsolidated "
                    . "where (transactiondate between '$fromtran' AND '$totran') group by accountingcode) c "
                    . "on c.accountingcode=coa.acctcode "
                    . "left join (Select accountingcode,sum(none) as debitnone,sum(rector) as debitrector,sum(elem) as debitelem,sum(hs) as debiths,sum(tvet) as debittvet,sum(service) as debitservice,sum(admin) as debitadmin,sum(pastoral) as debitpastoral "
                    . "from debitconsolidated  "
                    . "where (transactiondate between '$fromtran' AND '$totran') "
                    . "group by accountingcode) d "
                    . "on d.accountingcode=coa.acctcode "
                    . "where coa.acctcode LIKE '$accountcode%' order by coa.acctcode asc");
        
                return view("accounting.deptIncome",compact('accounts','fromtran','totran','accountcode'));
        
    }
    
    function printconsolidatedreport($accountcode,$fromtran,$totran){
        //$accounts = \App\ChartOfAccount::where('acctcode','LIKE',$accountcode.'%')->orderBy('acctcode','ASC')->get();
            $accounts = DB::Select("select * from `chart_of_accounts` as coa "
                    . "left join (Select accountingcode,sum(none) as creditnone,sum(rector) as creditrector,sum(elem) as creditelem,sum(hs) as crediths,sum(tvet) as credittvet,sum(service) as creditservice,sum(admin) as creditadmin,sum(pastoral) as creditpastoral "
                    . "from creditconsolidated "
                    . "where (transactiondate between '$fromtran' AND '$totran') group by accountingcode) c "
                    . "on c.accountingcode=coa.acctcode "
                    . "left join (Select accountingcode,sum(none) as debitnone,sum(rector) as debitrector,sum(elem) as debitelem,sum(hs) as debiths,sum(tvet) as debittvet,sum(service) as debitservice,sum(admin) as debitadmin,sum(pastoral) as debitpastoral "
                    . "from debitconsolidated  "
                    . "where (transactiondate between '$fromtran' AND '$totran') "
                    . "group by accountingcode) d "
                    . "on d.accountingcode=coa.acctcode "
                    . "where coa.acctcode LIKE '$accountcode%' order by coa.acctcode asc");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('legal','landscape');
        $pdf->loadView("print.printconsolidateddept",compact('accounts','fromtran','totran','accountcode'));
        return $pdf->stream();
        
        
    }
    
    function deptreport($dept,$accountcode,$fromtran,$totran){
        $dept=str_replace('',' ',$dept);
        //$accounts = \App\ChartOfAccount::where

         //return $account;
    }
    
    static function returnzero($amount){
        if($amount != 0){
            return number_format($amount,2,'.',',');
        }else{
            return " ";
        }
    }
}
