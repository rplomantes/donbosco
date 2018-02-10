@extends('appaccounting')
@section('content')
<?php
$students = App\Status::where('schoolyear',$schoolyear)->whereIn('status',array(2,3))->get()->implode(',','idno');
$accounts = \App\Ledger::where('schoolyear',$schoolyear)
        ->where('categoryswitch','<',7)
        ->whereIn('idno',$students)
        ->whereRaw('payment+debitmemo+plandiscount+otherdiscount <= amount')
                ->get();
?>
<div class='container'>
    <h3>Summary Main Account</h3>
    <table class='table table-striped'>
        <tr>
            <th>Account Name</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Debit Memo</th>
            <th>Plan Discount</th>
            <th>Other Discount</th>
            <th>Remaining Balance</th>
        </tr>
        @foreach($accounts->groupBy('accountingcode') as $account)
        <?php
        $amount = $account->sum('amount');
        $payment = $account->sum('payment');
        $dm = $account->sum('debitmemo');
        $plandc = $account->sum('plandiscount');
        $otherdc = $account->sum('otherdiscount');
        ?>
        <tr align='right'>
            <td  align='left'>{{$account->pluck('acctcode')->last()}}</td>
            <td>{{number_format($amount,2)}}</td>
            <td>{{number_format($payment,2)}}</td>
            <td>{{number_format($dm,2)}}</td>
            <td>{{number_format($plandc,2)}}</td>
            <td>{{number_format($otherdc,2)}}</td>
            <td>{{number_format($amount-($payment+$dm+$plandc+$otherdc),2)}}</td>
        </tr>
        @endforeach
        <?php
        $totalamount = $accounts->sum('amount');
        $totalpayment = $accounts->sum('payment');
        $totaldm = $accounts->sum('debitmemo');
        $totalplandc = $accounts->sum('plandiscount');
        $totalotherdc = $accounts->sum('otherdiscount');
        ?>
        <tr  align='right'>
            <td  align='left'>Total</td>
            <td>{{number_format($totalamount,2)}}</td>
            <td>{{number_format($totalpayment,2)}}</td>
            <td>{{number_format($totaldm,2)}}</td>
            <td>{{number_format($totalplandc,2)}}</td>
            <td>{{number_format($totalotherdc,2)}}</td>
            <td>{{number_format($totalamount-($totalpayment+$totaldm+$totalplandc+$totalotherdc),2)}}</td>
        </tr>
    </table>
</div>
@stop