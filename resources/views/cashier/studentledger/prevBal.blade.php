
<h4>Previous Balance</h4>
<table class="table table-striped">
    <tr>
        <th>School Year</th>
        <th>Amount</th>
    </tr>
    @foreach($accounts->groupBy('schoolyear') as $account)
    @if($account->sum('amount')-($account->sum('plandiscount')+$account->sum('otherdiscount')+$account->sum('debitmemo')+$account->sum('payment')) > 0)
    <tr>
        <td>{{$account->pluck('schoolyear')->last()}}</td>
        <td align="right">{{number_format($account->sum('amount')-($account->sum('plandiscount')+$account->sum('otherdiscount')+$account->sum('debitmemo')+$account->sum('payment')),2)}}</td>
    </tr>
    @endif
    @endforeach
</table>