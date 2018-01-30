<?php 
$chunk = $entries->chunk(19);
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
                width:120px;
                white-space: nowrap;
                text-align: left
            }
            td{
                font-size: 10pt;
            }
            .report{
                page-break-after: always
            }
        </style>
    </head>
    <body>
@include('inludes.header')
        @foreach($chunk as $group)
        <table class="report" border="1" cellspacing="0" cellpadding="2">
            <tr>
                <th>Voucher No</th><th width='15%'>Payee</th><th>Voucher Amount</th><th>Advance To Employee</th><th>Cost of Sales</th>
                <th>Instructional  Materials</th><th>Salaries / Allowances</th><th>Personnel <br>Development</th>
                <th>Other Employee Benefit</th><th>Office Supplies</th><th>Travel Expenses</th>
                <th>Sundries Debit</th><th>Sundies Credit</th><th>Status</th>
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
                <td></td>
            </tr>
            @foreach($group as $entry)
            <tr align='right'>
                <td align='left'>{{$entry->voucherno}}</td>
                <td align='left'>
                    <div class="payee">
                        @if(strlen($entry->payee)>20)
                        {{substr($entry->payee, 0 , 20)}}...
                        @else 
                        {{$entry->payee}}
                        @endif
                    </div>

                </td>
                <td>{{number_format($entry->voucheramount,2)}}</td>
                <td>{{number_format($entry->advances_employee,2)}}</td>
                <td>{{number_format($entry->cost_of_goods,2)}}</td>
                <td>{{number_format($entry->instructional_materials,2)}}</td>
                <td>{{number_format($entry->salaries_allowances,2)}}</td>
                <td>{{number_format($entry->personnel_dev,2)}}</td>
                <td>{{number_format($entry->other_emp_benefit,2)}}</td>
                <td>{{number_format($entry->office_supplies,2)}}</td>
                <td>{{number_format($entry->travel_expenses,2)}}</td>
                <td>{{number_format($entry->sundry_debit,2)}}</td>
                <td>{{number_format($entry->sundry_credit,2)}}</td>
                <td align='center'>
                    @if($entry->sundry_credit == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
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
                <td></td>
            </tr>
        </table>
        @endforeach
        
        <?php 
        $sundryCredit = $sundries->where('cr_db_indic',1)->filter(function($item){
            return !in_array(data_get($item, 'accountcode'), array(110012,110013,110014,110015,110016,110017,110018,110019,110020,110021));
        });
        $sundryDebit = $sundries->where('cr_db_indic',0)->filter(function($item){
            return !in_array(data_get($item, 'accountcode'), array(120103,440601,440701,580000,500000,500500,500300,120201,590200));
        });
        
        $creditGroup = $sundryCredit->groupBy('accountcode')->chunk(20);
        $debitGroup = $sundryDebit->groupBy('accountcode')->chunk(20);
        
        $creditcol = count($creditGroup);
        $debitcol = count($debitGroup);
        ?>
        
        <table width="100%" class="report">
            <tr>
                <td><h3><b>Debit Sundry Breakdown</b></h3></td>
            </tr>
            <tr>
                @foreach($debitGroup as $debit)
                <td style="vertical-align: top" width="{{100/3}}%">
                    <table border="1" cellspacing="0" width="100%">
                        <tr>
                            <th>Account</th>
                            <th>Amount</th>
                        </tr>
                        @foreach($debit as $debitsundry)
                        <tr>
                            <td>{{$debitsundry->pluck('accountname')->last()}}</td>
                            <td align='right'>{{number_format($debitsundry->sum('debit'),2)}}</td>
                        </tr>
                        @endforeach 
                        @if($debit == $debitGroup->last())
                        <tr>
                            <td>Total</td>
                            <td  align='right'>{{number_format($sundryDebit->sum('debit'),2)}}</td>
                        </tr>
                        @endif
                    </table>
                </td>
                @endforeach
                @while($debitcol < 3)
                <td></td>
                @php
                $debitcol++;
                @endphp
                @endwhile
            </tr>
        </table>
        
        <table width="100%">
            <tr>
                <td><h3><b>Credit Sundry Breakdown</b></h3></td>
            </tr>
            <tr>
                @foreach($creditGroup as $credit)
                <td style="vertical-align: top" width="{{100/3}}%">
                    <table border="1" cellspacing="0" width="100%">
                        <tr>
                            <th>Account</th>
                            <th>Amount</th>
                        </tr>
                        @foreach($credit as $creditsundry)
                        <tr>
                            <td>{{$creditsundry->pluck('accountname')->last()}}</td>
                            <td align='right'>{{number_format($creditsundry->sum('credit'),2)}}</td>
                        </tr>
                        @endforeach 
                        @if($credit == $creditGroup->last())
                        <tr>
                            <td>Total</td>
                            <td align='right'>{{number_format($sundryCredit->sum('credit'),2)}}</td>
                        </tr>
                        @endif
                    </table>
                </td>
                @endforeach
                @while($creditcol < 3)
                <td width="{{100/3}}%"></td>
                @php
                $creditcol++;
                @endphp
                @endwhile

            </tr>
        </table>
    </body>
</html>

