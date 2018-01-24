<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Excel;

class TrialBalanceController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    function gettrialBalance($fromtran,$totran){
       
//        $trials = DB::Select("select coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
//                . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  group by accountingcode "
//                . "UNION ALL "
//                . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  group by accountingcode) r "
//                . "on coa.acctcode = r.accountingcode group by accountingcode order by coa.acctcode");
        
        $trials = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  group by accountingcode "
                . "UNION ALL "
                . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromtran' and '$totran') and isreverse = '0'  group by accountingcode) r "
                . "on coa.acctcode = r.accountingcode group by coa.acctcode order by coa.acctcode");
       
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
    
    function download($fromtran,$totran){
        $trials = $this->gettrialBalance($fromtran,$totran);
        $name = "Trial balance - ".date("M_d_Y",strtotime($fromtran))." to ".date("M_d_Y",strtotime($totran));
        
        Excel::create($name,function($excel) use($trials,$fromtran,$totran){
            $excel->sheet('trial bal',function($sheet) use($trials,$fromtran,$totran){
                $sheet->loadView('accounting.trialBalDownload')
                        ->with('trials',$trials)
                        ->with('fromtran',$fromtran)
                        ->with('totran',$totran);                
                
                $sheet->setStyle(array(
                    'font' => array(
                    'name'      =>  'Calibri',
                    'size'      =>  12)
                    ));
                $sheet->setColumnFormat(array(
                    'C' => '0.00',
                    'D' => '0.00'
                ));
            });
        })->export('xlsx');
    }
}
