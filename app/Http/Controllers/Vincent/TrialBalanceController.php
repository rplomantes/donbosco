<?php

namespace App\Http\Controllers\Vincent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class TrialBalanceController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function viewtrilaBalance($fromtran,$totran){
        $trials = DB::Select("select r.accountingcode,accountname,r.credits as credits,r.debit as debit from chart_of_accounts coa join "
                . "(select accountingcode,sum(amount) as credits,NULL as debit from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' group by accountingcode "
                . "UNION ALL "
                . "select accountingcode,NULL,sum(amount)+sum(checkamount) from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0' group by acctcode, depositto) r on coa.acctcode = r.accountingcode order by coa.id");


       return view('accounting.trialBal',compact('trials','fromtran','totran')); 
    }
}
