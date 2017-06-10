@extends('appaccounting')
@section('content')
<div class="container">
    <table class="table table-striped">
    <tr><td colspan="2">Collection Report</td></tr>
    <tr><td colspan="2">Transaction Date : {{$datefrom}} - {{$dateto}}</td></tr>
    <?php $totalcredit = 0;?>
    @foreach($credits as $credit)
    <tr><td>{{$credit->acctcode}}</td><td align="right">{{$credit->amount}}</td></tr>
    <?php $totalcredit = $totalcredit + $credit->amount;?>
    @endforeach
    <tr><td>Total</td><td>{{$totalcredit}}</td></tr>
    </table>
    </table>    
    
    
     <table class="table table-striped">
    <tr><td colspan="3">Debit</td></tr>
    <?php $totaldebit = 0;?>
    @foreach($debits as $debit)
    <tr><td>{{$debit->acctcode}}</td><td>{{$debit->paymenttype}}</td><td align="right">{{$debit->amount}}</td></tr>
    <?php $totaldebit = $totaldebit + $debit->amount;?>
    @endforeach
    <tr><td colspan="2">Total</td><td>{{$totaldebit}}</td></tr>
    </table>
    </table> 
</div>
@stop

