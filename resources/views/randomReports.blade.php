@extends('appaccounting')
@section('content')
<?php
use App\Http\Controllers\ReportGenerator;
       $levels = \App\CtrLevel::all();
      ?>
       @foreach($levels as $level)
       
       <?php
           $students = ReportGenerator::getLevelStudents($level);
           $number = rand(5,20);
           $ledger = ReportGenerator::getRandomStudent($students,$number);
           
           $name = App\User::where('idno',$ledger->pluck('idno')->last())->first();
       ?>
       <table class='table table-bordered'>
           
           <tr><td  colspan='7'><h3 class='col-md-12'>{{$level->level}}</h3></td></tr>
           <tr><td  colspan='6'><h4>{{$ledger->pluck('idno')->last()}} - {{$name->lastname}}, {{$name->firstname}} {{$name->middlename}}</h4></td><td>{{$students->where('idno',$ledger->pluck('idno')->last())->first()->plan}}</td></tr>
        <tr>
            <th>Account Name</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Debit Memo</th>
            <th>Plan Discount</th>
            <th>Other Discount</th>
            <th>Remaining Balance</th>
        </tr>
           @foreach($ledger->groupBy('accountingcode') as $account)
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
        $totalamount = $ledger->sum('amount');
        $totalpayment = $ledger->sum('payment');
        $totaldm = $ledger->sum('debitmemo');
        $totalplandc = $ledger->sum('plandiscount');
        $totalotherdc = $ledger->sum('otherdiscount');
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
       @endforeach

@stop