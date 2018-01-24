<?php
$dmcmlists = \App\DebitMemo::where('transactiondate',$trandate)->where('postedby',\Auth::user()->idno)->get();
$totaldm=0;
$totalcancel =0;
?>

@extends('appaccounting')
@section('content')
<div class="container">
    <h3>DEBIT MEMO DAILY REPORT</H3>
    <table class="table table-striped table-bordered">
        <tr><th>Debit Memo No.</th><th>Student No</th><th>Student Name</th><th>Explanation</th><th>Amount</th><th>Status</th><th>View</th></tr>
        @foreach($dmcmlists as $dmcmlist)
        <tr><td>{{$dmcmlist->voucherno}}</td><td>{{$dmcmlist->idno}}</td><td>{{$dmcmlist->fullname}}</td>
            <td>{{$dmcmlist->remarks}}</td><td align="right">{{number_format($dmcmlist->amount,2)}}</td><td>
                @if($dmcmlist->isreverse == "0")
                <?php $totaldm = $totaldm + $dmcmlist->amount; ?>
                OK
                @else
                Cancelled
                <?php $totalcancel = $totalcancel + $dmcmlist->amount; ?>
                @endif
            </td>
            <td><a href="{{url('viewdm',[$dmcmlist->refno,$dmcmlist->idno])}}" class="btn btn-primary form-control">View DM</a></td></tr>
        @endforeach
    </table> 
    <div class="col-md-5" style="font-size:12pt; font-weight: bold">
    <table class="table table-bordered">
    <tr><td>Total Debit Memo</td><td align="right">{{number_format($totaldm,2)}}</td></tr>
    <tr><td>Total Cancelled</td><td align="right">{{number_format($totalcancel,2)}}</td></tr>
    </table>
    </div>    
        
    
</div>
@stop

