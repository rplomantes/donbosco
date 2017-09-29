<?php

namespace App\Http\Controllers\Economer;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Economer\Charts;

class OperationIncome extends Controller
{
    function index(){
        //$fund =$this->bankFund($fromdate,$todate,'prev');
        //$totalfund = $this->compute($fund);
        
        $height = 400;
        $width = 600;
        $labels = array('Expense','Income','Assets');
        $colors = array(sprintf("#%06x",rand(0,16777215)),sprintf("#%06x",rand(0,16777215)),sprintf("#%06x",rand(0,16777215)));
        $data = array(5000,2000,9000);
        $chart = Charts::piechart($width, $height, $labels, $colors, $data);
        
        return view('example',compact('chart'));
                
                
    }
    
    function bankFund($fromdate,$todate,$filter){
        if($filter == 'range'){
            $fund = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode where coa.acctcode IN(110011,110012,110013,110014,110015,110016,110017,110018,110019,110020,110021,110022) group by coa.acctcode order by coa.acctcode");
        }else{
            $fund = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where transactiondate > '$fromdate' and isreverse = '0'  group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where transactiondate > '$fromdate' and isreverse = '0'  group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode where coa.acctcode IN(110011,110012,110013,110014,110015,110016,110017,110018,110019,110020,110021,110022) group by coa.acctcode order by coa.acctcode");
        }
        
        return $fund;
    }
    
    function compute($accounts){
        $total = 0;
        foreach($accounts as $account){
            //$total = 
        }
    }
}
