<?php

namespace App\Http\Controllers\Accounting\Budgeting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Accounting\Budgeting\BudgetHelper;
use DB;

class CreateBudget extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except' => ['updateBudget']]);
    }
    
    function index($department){
        $fiscalyear = \App\CtrFiscalyear::fiscalyear();
        $prev_fiscalyear = \App\CtrFiscalyear::prevFiscal();
        $offices = \App\CtrAcctDep::where('main_department',$department)->get();
        $fields = \App\BudgetField::get();
        
        //Group Fields
        $revenues = $fields->where('type','revenue',false);
        $personel_expense = $fields->where('type','personel_expense',false);
        $other_expense = $fields->where('type','other_expense',false);
        return view('accounting.Budgeting.create.index',compact('department','fiscalyear','prev_fiscalyear','department','offices','revenues','personel_expense','other_expense'));
    }
    
    static function view_accountRow($field,$offices,$indent){
    $fiscalyear = \App\CtrFiscalyear::fiscalyear();
    $prev_fiscalyear = \App\CtrFiscalyear::prevFiscal();
    
    //Prev variables
    $prev_records = BudgetHelper::records($prev_fiscalyear);
    $accountingcodes = $field->budgetFieldAccounts->pluck('accountingcode')->toArray();
    $accounts = \App\ChartOfAccount::whereIn('acctcode',$accountingcodes)->get();

    $prev_budget = \App\BudgetAccounts::institutional_annualBudget($prev_fiscalyear,$field->entry_code);
    $prev_actual = BudgetHelper::groupTotal($prev_records,$accounts);

    $prev_balance = \App\BudgetAccounts::institutional_RemainingBudget($prev_fiscalyear, $prev_actual['amount']);
    $prev_util = \App\BudgetAccounts::institutional_annualUtilization($prev_fiscalyear, $prev_actual['amount']);
    
    //Current variables
    $curr_budget = \App\BudgetAccounts::institutional_annualBudget($fiscalyear,$field->entry_code);
    
    return view('accounting.Budgeting.create.sub_view.renderRowField',compact('fiscalyear','prev_budget','curr_budget','prev_actual','prev_balance','prev_util','offices','field','indent'));
    }
}
