<?php
$lists = \App\AccountingRemark::whereBetween('trandate', array($fromtran,$totran))->get();
?>
@extends('appaccounting')
@section('content')
<div class="container">
    <h3>Journal Entry Summary</h3>
    
    <div class="form-group">
        <div class="col-md-2">
            <label>From : </label>
            <input type="text" id="fromtran" class="form-control" value="{{$fromtran}}">
        </div>    
        <div class="col-md-2">
            <label>to : </label>
            <input type="text" id="totran" class="form-control" value="{{$totran}}">
        </div>
        <div class="col-md-2">
            <br>
            <button id="processbtn" class="btn btn-primary form-control">View List</button>
        </div>
    </div>
<br><hr>    
        <table class="table table-bordered table-striped"><tr><td>Date</td><td>Journal Voucher No</td><td>Remarks</td><td>Amount</td><td>View</td><td>Status</td></tr>
           @foreach($lists as $list)
           <tr><td>{{$trandate}}</td><td>{{$list->referenceid}}</td><td>{{$list->remarks}}</td><td>{{$list->amount}}</td><td><a href="{{url('printjournalvoucher',$list->refno)}}"> View Voucher </a></td>
           <td>@if($list->isreverse == "0")
               OK
               @else
               Cancelled
               @endif
           </td>    
           </tr>
           @endforeach 
        </table>
       
</div>
<script>
    $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('dailyalljournallist')}}" + "/" + $("#fromtran").val() + "/" + $("#totran").val();
    })
</script>
@stop


