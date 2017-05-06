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
    
    function index($fromtran,$totran){
                $consolidated = DB::Select("Select * from ("
                        . "Select accountingcode,acctcode,description,sum(none) as 'none',sum(rector) as 'rector',sum(elem) as 'elem',sum(hs) as 'hs',sum(tvet) as 'tvet',sum(service) as 'service',sum(admin) as 'admin',sum(pastoral) as 'pastoral' from creditconsolidated c where (transactiondate between '$fromtran' and '$totran') and accountingcode LIKE '4%' group by acctcode"
                        . " UNION ALL "
                        . "Select accountingcode,acctcode,description,sum(none),sum(rector),sum(elem),sum(hs),sum(tvet),sum(service),sum(admin),sum(pastoral) from debitconsolidated d where (transactiondate between '$fromtran' and '$totran') and accountingcode LIKE '4%' group by acctcode"
                        . ")consol group by acctcode");
                
                return view("accounting.deptIncome",compact('consolidated'));
        
    }
}
