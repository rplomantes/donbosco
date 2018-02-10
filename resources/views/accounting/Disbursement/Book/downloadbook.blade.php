<?php 
$total = $entries->where('isreverse',0);
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
                <th>Sundries Debit</th>
                <th>Sundies Credit</th>
                <th>Particular</th>
                <th>Status</th>
            </tr>
            @foreach($entries as $entry)
            <?php $sundries = $entrysundies->where('refno',$entry->refno,false);?>
            <tr align='right'>
                <td align='left'>{{$entry->voucherno}}</td>
                <td align='left'>{{$entry->payee}}</td>
                <td>{{number_format($entry->voucheramount,2)}}</td>
                <td>{{number_format($entry->advances_employee,2)}}</td>
                <td>{{number_format($entry->cost_of_goods,2)}}</td>
                <td>{{number_format($entry->instructional_materials,2)}}</td>
                <td>{{number_format($entry->salaries_allowances,2)}}</td>
                <td>{{number_format($entry->personnel_dev,2)}}</td>
                <td>{{number_format($entry->other_emp_benefit,2)}}</td>
                <td>{{number_format($entry->office_supplies,2)}}</td>
                <td>{{number_format($entry->travel_expenses,2)}}</td>
                <td>@if(count($sundries)>0 && $sundries->first()->debit >0){{number_format($sundries->first()->debit,2)}}@endif</td>
                <td>@if(count($sundries)>0 && $sundries->first()->credit >0){{number_format($sundries->first()->credit,2)}}@endif</td>
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
                            <td align='right'>@if($sundryRec->debit > 0) {{number_format($sundryRec->debit,2)}}@endif</td>
                            <td align='right'>@if($sundryRec->credit > 0){{number_format($sundryRec->credit,2)}}@endif</td>
                            <td align='left' style="white-space: nowrap;">{{$sundryRec->particular}}</td>
                            <td></td>

                        </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <tr align='right'>
                <td align='left' colspan="2">Total</td>
                <td>{{number_format($total->sum('voucheramount'),2)}}</td>
                <td>{{number_format($total->sum('advances_employee'),2)}}</td>
                <td>{{number_format($total->sum('cost_of_goods'),2)}}</td>
                <td>{{number_format($total->sum('instructional_materials'),2)}}</td>
                <td>{{number_format($total->sum('salaries_allowances'),2)}}</td>
                <td>{{number_format($total->sum('personnel_dev'),2)}}</td>
                <td>{{number_format($total->sum('other_emp_benefit'),2)}}</td>
                <td>{{number_format($total->sum('office_supplies'),2)}}</td>
                <td>{{number_format($total->sum('travel_expenses'),2)}}</td>
                <td>{{number_format($total->sum('sundry_debit'),2)}}</td>
                <td>{{number_format($total->sum('sundry_credit'),2)}}</td>
                <td colspan="2"></td>
            </tr>
        </table>