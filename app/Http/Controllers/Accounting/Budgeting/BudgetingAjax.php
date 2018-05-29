<?php

namespace App\Http\Controllers\Accounting\Budgeting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BudgetingAjax extends Controller
{
    //create record
    function updateRecord(Request $request){
        if($request->ajax()){
            $fiscalyear = \App\CtrFiscalyear::fiscalyear();
            
            
            $budget = \App\BudgetAccounts::where('sub_department',$request->office)->where('fiscalyear',$fiscalyear)->where('entry_code',$request->account)->first();
            if(!$budget){
                $department = \App\CtrAcctDep::where('sub_department',$request->office)->first()->main_department;
                $accountdetails = \App\BudgetField::where('entry_code',$request->account)->first();
                
                $budget = new \App\BudgetAccounts();
                $budget->type = $accountdetails->type;
                $budget->entry_code = $request->account;
                $budget->group = $accountdetails->group;
                $budget->sub_group = $accountdetails->sub_group;
                $budget->department = $department;
                $budget->sub_department = $request->office;
                $budget->fiscalyear = $fiscalyear;
            }
            $budget->amount = $request->amount;
            $budget->save();
        }
    }
    
}
