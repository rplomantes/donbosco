<?php

namespace App\Http\Controllers\Widget;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Accounting\DeptIncomeController;

class ConsolidatedReport extends Controller
{
    function piechart(){
        $reasons = \Lava::DataTable();
        $acctcode = 4;
        $departments = array("None","Rectors Office","Student Services","Administration Department","Elementary Department","High School Department","TVET","Pastoral Department");
        $accounts = DeptIncomeController::accounts('2017-05-01',date('Y-m-d'),$acctcode,2017);
        
        $reasons->addStringColumn('Reasons')
                ->addNumberColumn('Percent');
        foreach($departments as $department){
            $value = $this->deptTotal($accounts,$department,$acctcode);
                $reasons->addRow([$department, $value]);            
        }



        \Lava::PieChart('IMDB', $reasons, [
                        'height' => 300,
                        'width'  =>700
                    ]);
        
        return view('widgets.consolidated');
    }
    
    function barchart(){
        $reasons = \Lava::DataTable();
        $acctcode = 4;
        $departments = array("None","Rectors Office","Student Services","Administration Department","Elementary Department","High School Department","TVET","Pastoral Department");
        $accounts = DeptIncomeController::accounts('2017-05-01',date('Y-m-d'),$acctcode,2017);
        
        $reasons->addStringColumn('Reasons')
                ->addNumberColumn('Collected');
        foreach($departments as $department){
            $value = $this->deptTotal($accounts,$department,$acctcode);
                $reasons->addRow([$department, $value]);            
        }



        \Lava::BarChart('IMDB', $reasons, [
                        'height' => 300,
                        'width'  =>700
                    ]);
        
        return view('widgets.consolidatedbar');
    }
    
    
    function deptTotal($accounts,$dept,$accounttype){
        $total = 0;
        $credit = 0;
        $debit = 0;
        foreach($accounts as $account){
            
            if(strcmp(preg_replace("/[\s+',]/","",$account->coffice),preg_replace("/[\s+',]/","",$dept)) == 0){
                $credit = $credit + $account->cred;
                $debit = $debit + $account->deb;
            } 
        }
        
        if($accounttype == 4){
            $total = $credit - $debit;
        }else{
            $total = $debit - $credit;
        }
        return abs($total);
    }
}
