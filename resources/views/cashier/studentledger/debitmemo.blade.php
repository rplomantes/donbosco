<h4><b>Debit Memo</b></h4>
<table class='table table-striped'>
    <tr>
        <th>Date</th>
        <th>Ref Number</th>
        <th>Account</th>
        <th>Amount</th>
        <th>Details</th>
        <th>Status</th>
    </tr>
    @foreach($transactions->groupBy('refno') as $transaction)
    <tr>
        <td>{{$transaction->pluck('transactiondate')->last()}}</td>
        <td>{{$transaction->pluck('refno')->last()}}</td>
        <td>{{$transaction->pluck('acctcode')->last()}}</td>
        <td align='right'>{{number_format($transaction->sum('amount'),2)}}</td>
        <td><a href='{{url("/viewdm",array($transaction->pluck("refno")->last(),$idno))}}'>view</a></td>
        <td>@if($transaction->pluck('isreverse')->last()==0)OK @else CANCELLED @endif</td>
    </tr>
    @endforeach
</table>