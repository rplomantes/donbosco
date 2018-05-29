
<tr align='right'>
    <td  align='left' style="padding-left:{{$indent}}px">{{$field->sub_group}}</td>
    <td>{{$prev_budget['display']}}</td>
    <td>{{$prev_actual['display']}}</td>
    <td>{{$prev_balance['display']}}</td>
    <td>{{$prev_util}}</td>
    <td>{{$curr_budget['display']}}</td>
    @foreach($offices as $office)
    <?php
    $curr_office_budget = \App\BudgetAccounts::institutional_annualBudget($fiscalyear,$field->entry_code,$office->sub_department);
    ?>
    <td><input class="form-control divide budgetField" value="{{$curr_office_budget['amount']}}" data-account='{{$field->entry_code}}' data-office ='{{$office->sub_department}}'></td>
    @endforeach
</tr>