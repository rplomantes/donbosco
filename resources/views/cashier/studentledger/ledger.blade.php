<h4><b>Accounts Details</b></h4>
<table class='table table-striped'>
    <tr>
        <th>Description</th>
        <th>Amount</th>
        <th>Discount</th>
        <th>DM</th>
        <th>Payment</th>
        <th>Balance</th>
    </tr>
    @foreach($accounts->groupBy('accountingcode') as $account)
    <tr align='right'>
        <td align='left'>{{$account->pluck('acctcode')->last()}}</td>
        <td>{{number_format($account->sum('amount'),2)}}</td>
        <td>{{number_format($account->sum('plandiscount')+$account->sum('otherdiscount'),2)}}</td>
        <td>{{number_format($account->sum('debitmemo'),2)}}</td>
        <td style='color: red'>{{number_format($account->sum('payment'),2)}}</td>
        <td>{{number_format($account->sum('amount')-($account->sum('plandiscount')+$account->sum('otherdiscount')+$account->sum('debitmemo')+$account->sum('payment')),2)}}</td>
    </tr>
    @endforeach
    <tr align='right'>
        <td align='left'>Total</td>
        <td>{{number_format($accounts->sum('amount'),2)}}</td>
        <td>{{number_format($accounts->sum('plandiscount')+$accounts->sum('otherdiscount'),2)}}</td>
        <td>{{number_format($accounts->sum('debitmemo'),2)}}</td>
        <td style='color: red'>{{number_format($accounts->sum('payment'),2)}}</td>
        <td>{{number_format($accounts->sum('amount')-($accounts->sum('plandiscount')+$accounts->sum('otherdiscount')+$accounts->sum('debitmemo')+$accounts->sum('payment')),2)}}</td>
    </tr>
</table>