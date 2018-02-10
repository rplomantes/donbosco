<?php 
use App\Http\Controllers\Accounting\DisbursementController;

$chunk = DisbursementController::customChunk($entries, $group_count);
$entrysundies = \App\RptDisbursementBookSundries::where('idno',\Auth::user()->idno)->get();


$forwarded_voucheramount = 0;
$forwarded_advToEmp = 0;
$forwarded_coastOfSales = 0;
$forwarded_insMat = 0;
$forwarded_salary = 0;
$forwarded_persDev = 0;
$forwarded_empBenefit = 0;
$forwarded_office = 0;
$forwarded_travel = 0;
$forwarded_dsundry = 0;
$forwarded_csundry = 0;

?>
<html>
    <head>
        <style>
            .payee{
                text-align: left;
            }
            td,th{
                font-size: 9pt;
            }
            .report{
                page-break-after: always
            }
        </style>
    </head>
    <body>
@include('inludes.header')
        @foreach($chunk as $group)
        <table 
            @if($group != $chunk->last())
            class="report" 
            @endif
            border="1" cellspacing="0" cellpadding="2" width="100%">
            <tr>
                <th>Voucher No</th><th width='15%'>Payee</th><th>Voucher Amount</th><th>Advances To Employees</th><th>Cost of Sales</th>
                <th>Instructional  Materials</th><th>Salaries / Allowances</th><th>Personnel <br>Development</th>
                <th>Other Employee Benefit</th><th>Office Supplies</th><th>Travel Expenses</th>
                <th>Sundries Debit</th><th>Sundies Credit</th><th>Account</th><th>Status</th>
            </tr>
            <tr align='right'>
                <td align='left' colspan="2">Forwarded Balance</td>
                <td>{{number_format($forwarded_voucheramount,2)}}</td>
                <td>{{number_format($forwarded_advToEmp,2)}}</td>
                <td>{{number_format($forwarded_coastOfSales,2)}}</td>
                <td>{{number_format($forwarded_insMat,2)}}</td>
                <td>{{number_format($forwarded_salary,2)}}</td>
                <td>{{number_format($forwarded_persDev,2)}}</td>
                <td>{{number_format($forwarded_empBenefit,2)}}</td>
                <td>{{number_format($forwarded_office,2)}}</td>
                <td>{{number_format($forwarded_travel,2)}}</td>
                <td>{{number_format($forwarded_dsundry,2)}}</td>
                <td>{{number_format($forwarded_csundry,2)}}</td>
                <td colspan="2"></td>
            </tr>
            @foreach($group as $entry)
            <?php $sundries = $entrysundies->where('refno',$entry->refno,false);?>
            <tr align='right'>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}' align='left'>{{$entry->voucherno}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}' align='left'>
                    <div class="payee">
                        @if(strlen($entry->payee)>25 && $entry->row_count ==1)
                        {{substr($entry->payee, 0 , 25)}}...
                        @else 
                        {{$entry->payee}}
                        @endif
                    </div>

                </td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->voucheramount,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->advances_employee,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->cost_of_goods,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->instructional_materials,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->salaries_allowances,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->personnel_dev,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->other_emp_benefit,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->office_supplies,2)}}</td>
                <td style='vertical-align: top' rowspan='{{$entry->row_count}}'>{{number_format($entry->travel_expenses,2)}}</td>
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

                            <td align='right'>@if($sundryRec->debit > 0) {{number_format($sundryRec->debit,2)}}@endif</td>
                            <td align='right'>@if($sundryRec->credit > 0){{number_format($sundryRec->credit,2)}}@endif</td>
                            <td align='left' style="white-space: nowrap;">{{$sundryRec->particular}}</td>

                        </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        <?php
            $forwarded_voucheramount = $forwarded_voucheramount+$group->sum('voucheramount');
            $forwarded_advToEmp = $forwarded_advToEmp+$group->sum('advances_employee');
            $forwarded_coastOfSales = $forwarded_coastOfSales+$group->sum('cost_of_goods');
            $forwarded_insMat = $forwarded_insMat+$group->sum('instructional_materials');
            $forwarded_salary = $forwarded_salary+$group->sum('salaries_allowances');
            $forwarded_persDev = $forwarded_persDev+$group->sum('personnel_dev');
            $forwarded_empBenefit = $forwarded_empBenefit+$group->sum('other_emp_benefit');
            $forwarded_office = $forwarded_office+$group->sum('office_supplies');
            $forwarded_travel = $forwarded_travel+$group->sum('travel_expenses');
            $forwarded_dsundry = $forwarded_dsundry+$group->sum('sundry_debit');
            $forwarded_csundry = $forwarded_csundry+$group->sum('sundry_credit');
        ?>
            <tr align='right'>
                <td align='left' colspan="2">Sub Total</td>
                <td>{{number_format($forwarded_voucheramount,2)}}</td>
                <td>{{number_format($forwarded_advToEmp,2)}}</td>
                <td>{{number_format($forwarded_coastOfSales,2)}}</td>
                <td>{{number_format($forwarded_insMat,2)}}</td>
                <td>{{number_format($forwarded_salary,2)}}</td>
                <td>{{number_format($forwarded_persDev,2)}}</td>
                <td>{{number_format($forwarded_empBenefit,2)}}</td>
                <td>{{number_format($forwarded_office,2)}}</td>
                <td>{{number_format($forwarded_travel,2)}}</td>
                <td>{{number_format($forwarded_dsundry,2)}}</td>
                <td>{{number_format($forwarded_csundry,2)}}</td>
                <td colspan="2"></td>
            </tr>
        </table>
        @endforeach

    </body>
</html>

