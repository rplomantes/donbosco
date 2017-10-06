<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Economer\Charts;
use DB;
use App\Http\Controllers\Accounting\Helper as AcctHelper;

class BankFunds extends Controller
{
    function index($fromdate,$todate){
        $labels = array();
        $colors = array();
        $data = array();
        
        $colorranges = 6;
        
        $banks = $this->bankFund($fromdate, $todate);
                
        foreach ($banks as $bank){
            $labels[] = $bank->accountname;
            $colors[] = sprintf("#%06x",$this->getColor($colorranges));
            $data[] = round(AcctHelper::getaccttotal($bank->credits,$bank->debit,$bank->entry),2);
            $colorranges = $colorranges*4;
            
        }
        //Chart Data
        $height = 400;
        $width = 600;
        $position = 'right';
        $chart = Charts::piechart($width, $height,$position, $labels, $colors, $data);
        
        return view('admin.bankFund',compact('chart','banks'));
    }
    
    function bankFund($fromdate,$todate){

            $fund = DB::Select("select coa.entry,coa.acctcode as accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa left join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where (transactiondate between '$fromdate' and '$todate') and isreverse = '0'  group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode where coa.acctcode IN(110011,110012,110013,110014,110015,110016,110017,110018,110019,110020,110021,110022) group by coa.acctcode order by coa.acctcode");
        
        return $fund;
    }
    
    function getColor($num) {
        
        $hash = md5('color' . $num);
        
        return hexdec(substr($hash, 0, 3))."37";
    }
}
