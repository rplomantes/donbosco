<?php
$sundries = $credits->where('isreverse',0)->where('transactiondate',$transactiondate)->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    });
                    
$dsundries = $debits->where('isreverse',0)->where('transactiondate',$transactiondate)->filter(function($item){
                                                return !in_array(data_get($item, 'paymenttype'), array('1','4'));
                                                    });
?>
<style>
    td{
        border:1px solid #000000;
    }
</style>
<table border="1">
    <tr><td colspan="2">Credit Sundries</td></tr>
    <tr>
        <td>Account</td>
        <td>Amount</td>
    </tr>
    @foreach($sundries->groupBy('accountingcode') as $sundry)
    <tr>
        <td>{{$sundry->pluck('acctcode')->last()}}</td>
        <td style='text-align: right'>{{$sundry->sum('amount')}}</td>
    </tr>
    @endforeach
    <tr>
        <td>Total</td>
        <td style='text-align: right'>{{$sundries->sum('amount')}}</td>
    </tr>
</table>

<table border="1">
    <tr><td colspan="2">Debit Sundries</td></tr>
    <tr>
        <td>Account</td>
        <td>Amount</td>
    </tr>
    @foreach($dsundries->groupBy('accountingcode') as $dsundry)
    <tr>
        <td>{{$dsundry->pluck('acctcode')->last()}}</td>
        <td style='text-align: right'>{{$dsundry->sum('amount')}}</td>
    </tr>
    @endforeach
    <tr>
        <td>Total</td>
        <td style='text-align: right'>{{$dsundries->sum('amount')}}</td>
    </tr>
</table>