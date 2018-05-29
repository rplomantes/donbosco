<!--Revenue-->
<?php
use App\Http\Controllers\Accounting\Budgeting\BudgetHelper;
?>
<tr>
    <td colspan="{{$offices->count() + 6}}"><b>REVENUE:</b></td>
</tr>
@foreach($fields->where('type','revenue',false)->groupBy('group') as $group=>$sub_groups)
<!--With subgroup-->

@if($sub_groups->count() > 1)
<tr>
    <td colspan="{{$offices->count() + 6}}" style="padding-left:30px">{{$group}}</td>
</tr>

@foreach($sub_groups as $sub_group)
<?php
    $accountingcodes = $sub_group->budgetFieldAccounts->pluck('accountingcode')->toArray();
    $accounts = \App\ChartOfAccount::whereIn('acctcode',$accountingcodes)->get();

    $prev_budget = App\BudgetAccounts::institutional_annualBudget($prev_fiscalyear,$sub_group->entry_code);
    $prev_actual = BudgetHelper::groupTotal($prev_records,$accounts);

    $prev_balance = App\BudgetAccounts::institutional_RemainingBudget($prev_fiscalyear, $prev_actual['amount']);
    $prev_util = App\BudgetAccounts::institutional_annualUtilization($prev_fiscalyear, $prev_actual['amount']);
?>
<tr>
    <td style="padding-left:60px">{{$sub_group->sub_group}}</td>
    <td>{{$prev_budget['display']}}</td>
    <td>{{$prev_actual['display']}}</td>
    <td>{{$prev_balance['display']}}</td>
    <td>{{$prev_util}}</td>
    <td></td>
    @foreach($offices as $office)

    <td></td>
    @endforeach
</tr>
@endforeach
<!--Without subgroup-->
@else
<?php
    $accountingcodes = $sub_groups->first()->budgetFieldAccounts->pluck('accountingcode')->toArray();
    $accounts = \App\ChartOfAccount::whereIn('acctcode',$accountingcodes)->get();
    
    $prev_budget = App\BudgetAccounts::institutional_annualBudget($prev_fiscalyear,$sub_groups->first()->entry_code);
    $prev_actual = BudgetHelper::groupTotal($prev_records,$accounts);
    
    $prev_balance = App\BudgetAccounts::institutional_RemainingBudget($prev_fiscalyear, $prev_actual['amount']);
    $prev_util = App\BudgetAccounts::institutional_annualUtilization($prev_fiscalyear, $prev_actual['amount']);
?>
<tr align='right'>
    <td  align='left' style="padding-left:30px">{{$group}}</td>
    <td>{{$prev_budget['display']}}</td>
    <td>{{$prev_actual['display']}}</td>
    <td>{{$prev_balance['display']}}</td>
    <td>{{$prev_util}}</td>
    <td></td>
    @foreach($offices as $office)
    <td></td>
    @endforeach
</tr>
@endif
@endforeach
<!--END Revenue-->