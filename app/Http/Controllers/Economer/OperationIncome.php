<?php

namespace App\Http\Controllers\Economer;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Economer\Charts;
use DB;
use App\Http\Controllers\Accounting\Helper as AcctHelper;

class OperationIncome extends Controller
{
    function index($fromdate,$todate){
        $fund =$this->bankFund($fromdate,$todate,'prev');
        $totalfund = $this->compute($fund,'debit');
        
        $expense = $this->expenses($fromdate,$todate);
        $totalexpense = $this->compute($expense,'debit');
        
        $income = $this->income($fromdate,$todate);
        $totalincome = $this->compute($income,'credit');
        
        $counterIncome = $this->counterIncome($fromdate,$todate);
        $totalcounterincome = $this->compute($counterIncome,'debit');
        
        $height = 400;
        $width = 600;
        $labels = array('Expense','Revenue','Assets');
        $colors = array(sprintf("#%06x",rand(0,16777215)),sprintf("#%06x",rand(0,16777215)),sprintf("#%06x",rand(0,16777215)));
        $data = array($totalexpense,$totalincome,$totalfund);
        $chart = Charts::piechart($width, $height, $labels, $colors, $data);
        
        return view('example',compact('chart','fund','expense','income','totalincome'));
                
                
    }
    
    function expenses($fromdate,$todate){
            $expense = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode where coa.acctcode LIKE '5%' OR (coa.acctcode LIKE '4%' and coa.entry = 'debit') group by coa.acctcode order by coa.acctcode");        
            
            return $expense;
    }
    
    function income($fromdate,$todate){
            $expense = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode where coa.acctcode LIKE '4%' and coa.entry = 'credit' group by coa.acctcode order by coa.acctcode");        
            
            return $expense;
    }
    
    function counterIncome($fromdate,$todate){
            $expense = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode where coa.acctcode LIKE '4%' and coa.entry = 'debit' group by coa.acctcode order by coa.acctcode");        
            
            return $expense;
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
    
    function compute($accounts,$type){
        $total = 0;
        $debit = 0;
        $credit = 0;
        foreach($accounts as $account){
            if($account->entry == "debit"){
                $debit = $debit + round(AcctHelper::getaccttotal($account->credits,$account->debit,$account->entry),2);
            }
            
            if($account->entry == "credit"){
                $credit = $credit + round(AcctHelper::getaccttotal($account->credits,$account->debit,$account->entry),2);
            }
        }
        
        if($type == 'debit'){
            $total = $debit;
        }
        if($type == 'credit'){
            $total = $credit;
        }
        
        
        return $total;
    }
}
