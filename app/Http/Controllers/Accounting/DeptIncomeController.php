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
//                $consolidated = DB::Select("Select * from ("
//                        . "Select accountingcode,acctcode,description,sum(none) as 'none',sum(rector) as 'rector',sum(elem) as 'elem',sum(hs) as 'hs',sum(tvet) as 'tvet',sum(service) as 'service',sum(admin) as 'admin',sum(pastoral) as 'pastoral' from creditconsolidated c where (transactiondate between '$fromtran' and '$totran') and accountingcode LIKE '4%' group by acctcode"
//                        . " UNION ALL "
//                        . "Select accountingcode,acctcode,description,sum(none),sum(rector),sum(elem),sum(hs),sum(tvet),sum(service),sum(admin),sum(pastoral) from debitconsolidated d where (transactiondate between '$fromtran' and '$totran') and accountingcode LIKE '$account%' group by acctcode"
//                        . ")consol group by acctcode");
                $accounts = \App\ChartOfAccount::where('acctcode','LIKE',$accountcode.'%')->orderBy('acctcode','ASC')->get();
                return view("accounting.deptIncome",compact('accounts','fromtran','totran','accountcode'));
        
    }
    
    static function returnzero($amount){
        if($amount != 0){
            return number_format($amount,2,'.',',');
        }else{
            return " ";
        }
    }
}
