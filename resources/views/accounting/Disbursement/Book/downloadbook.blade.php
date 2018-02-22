<?php 
$total = $entries->where('isreverse',0,false);
$entrysundies = \App\RptDisbursementBookSundries::with('RptDisbursementBook')->where('idno',\Auth::user()->idno)->get();
?>
<style>
    td,th{
        border:1px solid #000000;
    }
</style>
        <table class='table table-bordered table-striped'>
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
                <th style="text-align:center">Sundries</th>
                <th></th>
                <th></th>
                <th>Status</th>
            </tr>
            <tr>
                <th></th>
                <th width='15%'></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Sundry Debit</th>
                <th>Sundry Credit</th>
                <th>Account</th>
                <th></th>
            </tr>
            @foreach($entries as $entry)
            <?php $sundries = $entrysundies->where('refno',$entry->refno,false);?>
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
                <td>@if(count($sundries)>0 && $sundries->first()->debit >0){{$sundries->first()->debit}}@endif</td>
                <td>@if(count($sundries)>0 && $sundries->first()->credit >0){{$sundries->first()->credit}}@endif</td>
                <td style="white-space: nowrap;" align='left'>@if(count($sundries)>0){{$sundries->first()->particular}}@endif</td>
                
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}' align='center'>
                    @if($entry->isreverse == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
                @if(count($sundries)>1)
                    @foreach($sundries as $sundryRec)
                        @if($sundryRec != $sundries->first())
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align='right'>@if($sundryRec->debit > 0) {{$sundryRec->debit}}@endif</td>
                            <td align='right'>@if($sundryRec->credit > 0){{$sundryRec->credit}}@endif</td>
                            <td align='left' style="white-space: nowrap;">{{$sundryRec->particular}}</td>
                            <td></td>

                        </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <tr align='right'>
                <td align='left' colspan="2">Total</td>
                <td>{{$total->sum('voucheramount')}}</td>
                <td>{{$total->sum('advances_employee')}}</td>
                <td>{{$total->sum('cost_of_goods')}}</td>
                <td>{{$total->sum('instructional_materials')}}</td>
                <td>{{$total->sum('salaries_allowances')}}</td>
                <td>{{$total->sum('personnel_dev')}}</td>
                <td>{{$total->sum('other_emp_benefit')}}</td>
                <td>{{$total->sum('office_supplies')}}</td>
                <td>{{$total->sum('travel_expenses')}}</td>
                <td>{{$total->sum('sundry_debit')}}</td>
                <td>{{$total->sum('sundry_credit')}}</td>
                <td colspan="2"></td>
            </tr>
        </table>