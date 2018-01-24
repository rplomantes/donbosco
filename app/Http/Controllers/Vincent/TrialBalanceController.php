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
    function gettrialBalance($fromtran,$totran){
       
        $trials = DB::Select("select r.accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  group by accountingcode "
                . "UNION ALL "
                . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  group by accountingcode) r "
                . "on coa.acctcode = r.accountingcode group by accountingcode order by coa.acctcode");
       
        return $trials;
        
    }
    
    function viewtrilaBalance($fromtran,$totran){
       $trials = $this->gettrialBalance($fromtran,$totran);
         
       return view('accounting.trialBal',compact('trials','fromtran','totran')); 
    }
    
    function printtrilaBalance($fromtran,$totran){
       $trials = $this->gettrialBalance($fromtran,$totran);
       
       $pdf = \App::make('dompdf.wrapper');
       $pdf->setPaper('letter','portrait');
       $pdf->loadView('print.trialBal',compact('trials','fromtran','totran'));
       return $pdf->stream();      
    }
}
