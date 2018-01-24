<?php
$entries = \App\Accounting::where('refno',$referenceid)->get();
$remark = \App\AccountingRemark::where('refno',$referenceid)->first();
$debittotal = 0;
$totalcredit = 0;
$cancel = "Cancel";
if($remark->isreverse=='1'){
$cancel = "Restore";    
}
?>
@extends('appaccounting')
@section('content')
<div class="container">
    <div class="col-md-4">    
    <h5>JOURNAL VOUCHER</h5>
    Date :{{$remark->trandate}} <br>
    Voucher No : {{$remark->referenceid}}</br>
    Prepared by : {{\Auth::user()->lastname}}, {{\Auth::user()->firstname}}
    </div>
    <div class="col-md-8">
        
        <a href="{{url('restorecanceljournal',array($cancel,$remark->refno))}}" class="btn btn-danger navbar-right" id="cancelRestore">{{$cancel}}</a>
        
        
    </div>    
<table class="table table-bordered table-stripped"><tr><td>Account Title</td><td>Subsidiary</td><td>Office</td><td>Debit Amount</td><td>Credit Amount</td></tr>
@foreach($entries as $entry)
<tr><td>{{$entry->accountname}}</td><td>{{$entry->subsidiary}}</td><td>{{$entry->sub_department}}</td><td align="right">{{ number_format($entry->debit,2)}}</td><td align="right">{{number_format($entry->credit,2)}}</td></tr>
<?php $debittotal=$debittotal + $entry->debit; $totalcredit=$totalcredit + $entry->credit;?>
@endforeach
<tr><td colspan="3">Total</td><td align="right" style="font-weight: bold">{{number_format($debittotal,2)}}</td><td align="right" style="font-weight: bold">{{number_format($totalcredit,2)}}</td></tr>
</table>
<div class="form-group">
    <label>Remarks/Explanation</label>
    <div class="panel panel-danger">
        <div class="panel-heading">
        {{$remark->remarks}}
        </div>
    </div>    
</div>
    <div class="form-group">
        <a href="{{url('printpdfjournalvoucher',$remark->refno)}}" class="form-control btn btn-primary" target="_blank"> Print Journal Voucher</a>
    </div>    
</div>
@stop