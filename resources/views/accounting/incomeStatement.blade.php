<?php use App\Http\Controllers\Accounting\IncomeStatement; ?>
@extends('appaccounting')
@section('content')
<div class="container">
    <table class="table">
        <tr><td colspan="4"><b><h4>Income</h4></b></td></tr>
        @foreach($accountGroups as $incomeGroup)
        <?php 
        $hascredit = 0; 
        $total = 0;
        $totalless = 0;
        ?>
            @if($incomeGroup->type == 4)
                <tr><td colspan="4">{{$incomeGroup->groupname}}</td></tr>

                <?php $accounts = App\accounts_group::where('group',$incomeGroup->id)->get();?>
                @foreach($accounts as $account)
                    @if($account->chartofaccount->entry == "credit")
                    <tr><td></td><td>{{$account->chartofaccount->accountname}}</td><td>{{number_format(IncomeStatement::accountTotal($account->chartofaccount->acctcode,$account->chartofaccount->entry,$date),2)}}</td><td></td></tr>
                    <?php $total = $total + IncomeStatement::accountTotal($account->chartofaccount->acctcode,$account->chartofaccount->entry,$date); ?>
                    @else
                    <?php $hascredit++; ?>
                    @endif
                @endforeach

                @if($hascredit > 0)
                <tr><td>Less:</td><td></td><td></td><td></td></tr>
                @endif
                @foreach($accounts as $account)
                    @if($account->chartofaccount->entry == "debit")
                    <tr><td></td><td>{{$account->chartofaccount->accountname}}</td><td>{{number_format(IncomeStatement::accountTotal($account->chartofaccount->acctcode,$account->chartofaccount->entry,$date),2)}}</td><td></td></tr>
                    <?php $totalless = $totalless + IncomeStatement::accountTotal($account->chartofaccount->acctcode,$account->chartofaccount->entry,$date); ?>
                    @endif
                @endforeach
            <tr><td></td><td></td><td></td><td>{{$total - $totalless}}</td></tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            @endif
        @endforeach
        <tr><td colspan="4"><b><h4>Expenses</h4></b></td></tr>
    </table>
</div>
@stop