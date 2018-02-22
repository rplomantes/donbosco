<style>
    td,th{
        border:1px solid #000000;
    }
</style>
<table>
    <tr>
        <th colspan="2">Debit Sundries</th>
    </tr>
    <tr>
        <th>Account</th>
        <th>Amount</th>
    </tr>
    @foreach($totalsundries->groupBy('accountingcode') as $debitsundry)
    @if($debitsundry->sum('debit') > 0)
    <tr>
        <td>{{$debitsundry->pluck('particular')->last()}}</td>
        <td align='right'>{{number_format($debitsundry->sum('debit'),2)}}</td>
    </tr>
    @endif
    @endforeach 
    <tr>
        <td>Total</td>
        <td  align='right'>{{number_format($totalsundries->sum('debit'),2)}}</td>
    </tr>
</table>
<table >
    <tr>
        <th colspan="2">Debit Sundries</th>
    </tr>
    <tr>
        <th>Account</th>
        <th>Amount</th>
    </tr>
    @foreach($totalsundries->groupBy('accountingcode') as $creditsundry)
    @if($creditsundry->sum('credit') > 0)
    <tr>
        <td>{{$creditsundry->pluck('particular')->last()}}</td>
        <td align='right'>{{number_format($creditsundry->sum('credit'),2)}}</td>
    </tr>
    @endif
    @endforeach 
    <tr>
        <td>Total</td>
        <td align='right'>{{number_format($totalsundries->sum('credit'),2)}}</td>
    </tr>
</table>