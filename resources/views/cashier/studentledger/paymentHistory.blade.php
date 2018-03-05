<?php
use App\Http\Controllers\Accounting\Student\TransanctionHistory\Helper as TranHelper;


?>
<h4><b>Payment History</b></h4>
<table class='table table-striped'>
    <tr>
        <th>Date</th>
        <th>Ref Number</th>
        <th>OR Number</th>
        <th>Amount</th>
        <th>Payment Type</th>
        <th>Details</th>
        <th>Status</th>
    </tr>
    @foreach($transactions->groupBy('refno') as $transaction)
    <tr>
        <td>{{$transaction->pluck('transactiondate')->last()}}</td>
        <td>{{$transaction->pluck('refno')->last()}}</td>
        <td>{{$transaction->pluck('receiptno')->last()}}</td>
        <td align='right'>{{number_format($transaction->where('paymenttype',1,false)->sum('amount')+$transaction->where('paymenttype',1,false)->sum('checkamount'),2)}}</td>
        <td>{{TranHelper::paymenttype($transaction)}}</td>
        <td><a href='{{url("/viewreceipt",array($transaction->pluck("refno")->last(),$idno))}}'>view</a></td>
        <td>@if($transaction->pluck('isreverse')->last()==0)OK @else CANCELLED @endif</td>
    </tr>
    @endforeach
</table>