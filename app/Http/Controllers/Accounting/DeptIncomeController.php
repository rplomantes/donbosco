<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DeptIncomeController extends Controller
{
    public function __construct(){
	$this->middleware(['auth','acct']);
    }
    
    function index($fromtran,$totran){
                $consolidated = DB::Select("Select c.acctcode,c.description,c.none,c.elem,c.hs,c.tvet,c.service,c.admin,c.pastoral from creditconsolidated c where (transactiondate between '2016-05-01' and '2017-01-01')"
                        . "UNION ALL "
                        . "Select d.acctcode,d.description,d.none,d.elem,d.hs,d.tvet,d.service,d.admin,d.pastoral from debitconsolidated d where (transactiondate between '2016-05-01' and '2017-01-01')");
        
    }
}
