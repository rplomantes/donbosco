<?php
$lists = \App\AccountingRemark::where('trandate', $trandate)->where('posted_by',\Auth::user()->idno)->get();
?>
@extends('appaccounting')
@section('content')
<div class="container">
    <h3>Journal Entry Daily Summary</h3>
    <h5>Date : {{$trandate}}</h5>
        <table class="table table-bordered table-striped"><tr><td>Journal Voucher No</td><td>Remarks</td><td>Amount</td><td>View</td><td>Status</td></tr>
           @foreach($lists as $list)
           <tr><td>{{$list->referenceid}}</td><td>{{$list->remarks}}</td><td>{{$list->amount}}</td><td><a href="{{url('printjournalvoucher',$list->refno)}}"> View Voucher </a></td>
           <td>@if($list->isreverse == "0")
               OK
               @else
               Cancelled
               @endif
           </td>    
           </tr>
           @endforeach 
        </table>
    <div class="form-group">
        <div class="col-md-12">
            <a href="{{url('printjournallistpdf',$trandate)}}" class="form-control btn btn-primary" target="_blank">Print Daily Journal Entry Summary</a> 
        </div>    
    </div>    
</div>

@stop
