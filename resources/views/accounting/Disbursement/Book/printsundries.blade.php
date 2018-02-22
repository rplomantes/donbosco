<?php
$entrysundies = \App\RptDisbursementBookSundries::with('RptDisbursementBook')->where('idno',\Auth::user()->idno)->get();

$totalsundries = $entrysundies->filter(function($item){
    if($item->RptDisbursementBook->isreverse == 0){
        return true;
    }
});

$debitsundries = $totalsundries->filter(function($item){
    if($item->debit > 0){
        return true;
    }
});
$creditsundries = $totalsundries->filter(function($item){
    if($item->credit > 0){
        return true;
    }
});

$debitsundriesgroup = $debitsundries->groupBy('accountingcode')->chunk($group_count);
$creditsundriesgroup = $creditsundries->groupBy('accountingcode')->chunk($group_count);
?>
@include('inludes.header')
<h4>Debit Sundries</h4>
<table width="100%">
    <tr>
        @foreach($debitsundriesgroup as $debitsun)
        <td style="vertical-align:top" width="50%">
            <div>
            <table border="1" cellspacing="0" cellpadding="1" style="vertical-align: top" width="100%">
                <tr>
                    <th>Account</th>
                    <th>Amount</th>
                </tr>
                @foreach($debitsun as $debitsundry)
                <tr>
                    <td>{{$debitsundry->pluck('particular')->last()}}</td>
                    <td align='right'>{{number_format($debitsundry->sum('debit'),2)}}</td>
                </tr>
                @endforeach 
                @if($debitsundriesgroup->last() == $debitsun)
                <tr style="font-weight: bold">
                    <td>Total</td>
                    <td  align='right'>{{number_format($debitsundries->sum('debit'),2)}}</td>
                </tr>
                @endif
            </table>
                </div>
        </td>
        @endforeach
        @if(count($debitsundriesgroup) == 1)
        <td style="vertical-align:top" width="50%"></td>
        @endif
    </tr>
</table>

<h4>Credit Sundries</h4>
<table width="100%">
    <tr>
        @foreach($creditsundriesgroup as $creditsun)
        <td style="vertical-align:top" width="50%">
            <div>
            <table border="1" cellspacing="0" cellpadding="1" style="vertical-align: top" width="100%">
                <tr>
                    <th>Account</th>
                    <th>Amount</th>
                </tr>
                @foreach($creditsun as $creditsundry)
                <tr>
                    <td>{{$creditsundry->pluck('particular')->last()}}</td>
                    <td align='right'>{{number_format($creditsundry->sum('credit'),2)}}</td>
                </tr>
                @endforeach 
                @if($creditsundriesgroup->last() == $creditsun)
                <tr style="font-weight: bold">
                    <td>Total</td>
                    <td  align='right'>{{number_format($creditsundries->sum('debit'),2)}}</td>
                </tr>
                @endif
            </table>
                </div>
        </td>
        @endforeach
        @if(count($creditsundriesgroup) == 1)
        <td style="vertical-align:top" width="50%"></td>
        @endif
    </tr>
</table>

