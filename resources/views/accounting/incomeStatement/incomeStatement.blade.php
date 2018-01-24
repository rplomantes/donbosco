<?php 
use App\Http\Controllers\Accounting\IncomeStatement; 

$totalIncome = 0;
$totalExpense = 0;
$totalOther = 0;

$incomeIndex = 1;
$expenseIndex = 1;
$otherIndex = 1;

$incomeSize = count($incomeGroups);
$expenseSize = count($expenseGroups);
$otherSize = count($otherGroups);
?>
@extends('appaccounting')
@section('content')
<style>
    td {
      padding-right: 10px;
    }
</style>
<div class="container">
    <h3>Income Statement</h3>
</div>
<div class="container hideOnPrint">
    <div class="col-md-6">
        <div class="form form-group col-md-6">
            <label class="col-md-4">From :</label>
            <div class="col-md-8">
                <div class="form-control">{{$from}}</div>
            </div>
        </div>
        <div class="form form-group col-md-6">
            <label class="col-md-3">To :</label>
            <div class="col-md-9">
                <input type="text" id="fromtran" class="form-control" value="{{$date}}">
            </div>
        </div>   
    </div>
    <div class="col-md-2"><button class="btn btn-danger" onclick="updateView()">View</button></div>
</div>
<div class="container">
    
    <table width="80%" style="margin:auto">
        <tr><td colspan="4"><b><h4>Income</h4></b></td></tr>
        @foreach($incomeGroups as $incomeGroup)
        <?php 
        $totalless = 0;

        $accounts = App\accounts_group::with(['chartofaccount','ctraccountgroup'])->where('group',$incomeGroup->id)->get();
        $credits = $accounts->where('chartofaccount.entry','credit');
        $debits = $accounts->where('ctraccountgroup.less',0)->where('chartofaccount.entry','debit');
        $lesses = $accounts->where('ctraccountgroup.less',1)->where('chartofaccount.entry','debit');

        $groupTotal = IncomeStatement::incomeGroupTotal($credits,$debits,$lesses,$date);
        $totalIncome = $totalIncome + $groupTotal;
        ?>
            
        <tr><td colspan="3"><b>{{$incomeGroup->groupname}}</b></td><td style="text-align: right;@if($incomeIndex >= $incomeSize)border-bottom:1px solid;@endif">{{number_format($groupTotal,2)}}</td></tr>

        @foreach($credits as $credit)
            <tr><td width="2%"></td><td width="50%">{{$credit->chartofaccount->accountname}}</td><td style="text-align: right">{{number_format(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2)}}</td><td></td></tr>
        @endforeach

        @foreach($debits as $debit)
            <tr><td></td><td>{{$debit->chartofaccount->accountname}}</td><td style="text-align: right">{{number_format(IncomeStatement::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2)}}</td><td></td></tr>
        @endforeach

        @if(count($lesses) > 0)
        <tr><td>Less:</td><td colspan="3"></td></tr>
        @endif
        @foreach($lesses as $less)
            <tr><td></td><td>{{$less->chartofaccount->accountname}}</td><td style="text-align: right">{{number_format(IncomeStatement::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date),2)}}</td><td></td></tr>
            <?php $totalless = $totalless + round(IncomeStatement::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date),2); ?>
        @endforeach
        @if(count($lesses) > 0)
        <tr><td></td><td></td><td style="border-top:1px solid;text-align: right">{{number_format($totalless,2)}}</td><td></td></tr>
        @endif

        <tr><td></td><td></td><td style="border-top: 1px solid"></td><td></td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>
        <?php $incomeIndex++; ?>
        @endforeach
        
        <tr style="page-break-after: always"><td colspan="3"><b>TOTAL INCOME</b></td><td style="border-bottom:3px solid;text-align: right">{{number_format($totalIncome,2)}}</td></tr>
           
        
        <tr><td colspan="4"><b><h4>Expenses</h4></b></td></tr>
        @foreach($expenseGroups as $expenseGroup)
            <?php 
            $accounts = App\accounts_group::where('group',$expenseGroup->id)->get();
            $debits = $accounts->where('chartofaccount.entry','debit');
            
            $groupTotal = IncomeStatement::otherGroupTotal($debits,$date);
            $totalExpense = $totalExpense+$groupTotal;
            ?>
        <tr><td colspan="3">{{$expenseGroup->groupname}}</td><td style="text-align: right;@if($expenseIndex >= $expenseSize)border-bottom:1px solid;@endif">{{number_format($groupTotal,2)}}</td></tr>
                @if(count($debits)>1)
                    @foreach($debits as $debit)
                        <tr><td></td><td>{{$debit->chartofaccount->accountname}}</td><td style="text-align: right">{{number_format(IncomeStatement::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2)}}</td><td></td></tr>
                    @endforeach
                    
                    <tr><td></td><td></td><td style="border-top: 1px solid"></td><td></td></tr>
                @endif
                <tr><td style="padding-top: 12px;"></td><td></td><td></td><td></td></tr>
        <?php $expenseIndex++; ?>
        @endforeach
        <tr><td colspan="3"><b>TOTAL EXPENSE</b></td><td style="border-bottom:3px solid;text-align: right">{{number_format($totalExpense,2)}}</td></tr>
        <!--tr><td colspan="3"><b>NET INCOME FROM OPERATION</b></td><td style="text-align:right">{{number_format($totalIncome - $totalExpense,2)}}</td></tr-->
        
        @foreach($otherGroups as $otherGroup)
            <?php 
            $totalOther = 0;
            $accounts = App\accounts_group::where('group',$otherGroup->id)->get();
            $credits = $accounts->where('chartofaccount.entry','credit');
            
            $groupTotal = IncomeStatement::otherGroupTotal($credits,$date);
            $totalOther = $totalOther + $groupTotal;
            ?>
        <tr><td colspan="4" style="padding-top: 2px;"></td></tr>
        <tr><td colspan="3">{{$otherGroup->groupname}}</td><td style="text-align: right;@if($otherIndex >= $otherSize)border-bottom:1px solid;@endif">{{number_format($groupTotal,2)}}</td></tr>
            @if(count($credits)>1)
                @foreach($credits as $credit)
                    <tr><td></td><td>{{$credit->chartofaccount->accountname}}</td><td style="text-align: right">{{number_format(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2)}}</td><td></td></tr>
                @endforeach
                <tr><td></td><td></td><td style="border-top: 1px solid"></td><td></td></tr>
            @endif
            <?php 
            $totalIncome = $totalIncome + round(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2);
            $otherIndex++; ?>
        @endforeach
        <tr><td colspan="4" style="padding-top: 5px;"></td></tr>
        <tr><td colspan="3"><b>NET INCOME FOR THE YEAR</b></td><td style="text-align: right;border-bottom: 5px solid;border-bottom-style: double"><b>{{number_format($totalIncome - $totalExpense,2)}}</b></td></tr>
    </table>
    <br><br><br>
    <!--button class="col-md-12 btn btn-info" onclick="printReport()">PRINT</button-->
</div>
<script>
    var date = $("#fromtran").val();
    function updateView(){s
        window.location.href = "/incomestatement/"+date;
    }
    
    function printRepor(){
        window.open("/printincomestatement/"+date);
    }
</script>
@stop