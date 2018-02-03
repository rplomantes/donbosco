<?php $total = $entries->where('isreverse',0);?>
<style>
    td,th{
        border:1px solid #000000;
    }
</style>
<table border="1" >
    <tr>
        <th>Voucher No</th>
        <th width='15%'>Payee</th>
        <th>Voucher Amount</th>
        <th>Advances To Employees</th>
        <th>Cost of Sales</th>
        <th>Instructional  Materials</th>
        <th>Salaries / Allowances</th>
        <th>Personnel <br>Development</th>
        <th>Other Employee Benefit</th>
        <th>Office Supplies</th>
        <th>Travel Expenses</th>
        <th>Sundries Debit</th>
        <th>Sundies Credit</th>
        <th>Status</th>
    </tr>
    @foreach($entries as $entry)
    <tr align='right'>
        <td align='left'>{{$entry->voucherno}}</td>
        <td align='left'>{{$entry->payee}}</td>
        <td>{{$entry->voucheramount}}</td>
        <td>{{$entry->advances_employee}}</td>
        <td>{{$entry->cost_of_goods}}</td>
        <td>{{$entry->instructional_materials}}</td>
        <td>{{$entry->salaries_allowances}}</td>
        <td>{{$entry->personnel_dev}}</td>
        <td>{{$entry->other_emp_benefit}}</td>
        <td>{{$entry->office_supplies}}</td>
        <td>{{$entry->travel_expenses}}</td>
        <td style="white-space:nowrap;text-align: right">{!!rtrim($entry->sundry_debit_account,"<br>")!!}</td>
        <td style="white-space:nowrap;text-align: right">{!!rtrim($entry->sundry_credit_account,"<br>")!!}</td>
        <td>
            @if($entry->sundry_credit == 0)
            OK
            @else
            Cancelled
            @endif
        </td>
    </tr>
    @endforeach
    <tr align='right'>
        <td align='left' colspan="2">Total</td>
        <td>{{$entries->sum('voucheramount')}}</td>
        <td>{{$entries->sum('advances_employee')}}</td>
        <td>{{$entries->sum('cost_of_goods')}}</td>
        <td>{{$entries->sum('instructional_materials')}}</td>
        <td>{{$entries->sum('salaries_allowances')}}</td>
        <td>{{$entries->sum('personnel_dev')}}</td>
        <td>{{$entries->sum('other_emp_benefit')}}</td>
        <td>{{$entries->sum('office_supplies')}}</td>
        <td>{{$entries->sum('travel_expenses')}}</td>
        <td>{{$entries->sum('sundry_debit')}}</td>
        <td>{{$entries->sum('sundry_credit')}}</td>
        <td></td>
    </tr>
</table>