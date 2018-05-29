<?php
use App\Http\Controllers\Accounting\Budgeting\BudgetHelper;
?>
<table class="table table-bordered">
    <tr>
        <td>Department</td>
        <td>Office</td>
        <td>Budget</td>
        <td>Expense</td>
        <td>Utilization</td>
    </tr>
    @foreach($departments->groupBy('main_department') as $department=>$offices)
    <?php
    
    //Departmental Details
    $department_expense = BudgetHelper::deptTotal($expenses->where('acct_department',$department,false));
    $department_utilization = App\DepartmentBudget::department_Utilization($department, $fiscalyear, $department_expense);
    $department_budget = App\DepartmentBudget::department_AnnualBudget($department, $fiscalyear);
    
    if($department_budget != "N/A"){
        $department_budget = number_format($department_budget,2,' .',',');
    }
    ?>
    <tr align="right">
        <td  align="left" colspan="2">{{$department}}</td>
        <td>{{$department_budget}}</td>
        <td>{{number_format($department_expense,2,' .',',')}}</td>
        <td>{{$department_utilization}}</td>
    </tr>
    
    @foreach($offices as $office)
    <?php
    
    //Office Details
    $office_expense = BudgetHelper::deptTotal($expenses->where('sub_department',$office->sub_department,false));
    $office_utilization = App\DepartmentBudget::office_Utilization($office->sub_department, $fiscalyear, $office_expense);
    $office_budget = App\DepartmentBudget::office_AnnualBudget($office->sub_department, $fiscalyear);
    
    if($office_budget != "N/A"){
        $office_budget = number_format($office_budget,2,' .',',');
    }    
    
    ?>
    <tr align="right">
        <td></td>
        <td  align="left">{{$office->sub_department}}</td>
        <td>{{$office_budget}}</td>
        <td>{{number_format($office_expense,2,' .',',')}}</td>
        <td>{{$office_utilization}}</td>
    </tr>
    @endforeach
    @endforeach
</table>