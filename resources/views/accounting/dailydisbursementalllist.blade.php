<?php

$lists = \App\Disbursement::whereBetween('transactiondate', array($fromdate,$todate))->get();
$banktotal = DB::Select("Select sum(amount) as amount, bank from disbursements where (transactiondate between '$fromdate' and '$todate')  and isreverse = '0' group by bank");
$total=0;
$totalbank=0;
$template = 'appaccounting';
if(in_array(Auth::user()->accesslevel,array(env('USER_ACCOUNTING'),env('USER_ACCOUNTING_HEAD')))){
    $template = 'appaccounting';
}elseif(in_array(Auth::user()->accesslevel,array(env('USER_ADMIN')))){
    $template = 'layouts.administrator';
}
?>

@extends($template)

@section("content")
<div class="container">
 <h3>Disbursement Daily Summary</h3>
 <div class="col-md-2"> 
    <div class="form form-group">
        <label>From  :</label>
        <input type="text" class="form-control" id="fromdate" value="{{$fromdate}}">
    </div>
 </div>
 <div class="col-md-2"> 
    <div class="form form-group">
        <label>From  :</label>
        <input type="text" class="form-control" id="todate" value="{{$todate}}">
    </div>
 </div>
 <div class="col-md-2"> 
    <div class="form form-group">
        <br>
        <button id="processbtn" class="btn btn-primary form-control">View List</button>
    </div>
 </div>
 
 <table class="table table-bordered table-striped">
     <tr><th>Date</th><th>Voucher No</th><th>Check No</th><th>Account No</th><th>Payee</th><td>Amount</th><th>Particular</th><th>View</th><th>Status</th></tr>
     @foreach($lists as $list)
     <tr><td>{{$list->transactiondate}}</td><td>{{$list->voucherno}}</td><td>{{$list->checkno}}</td><td>{{$list->bank}}</td><td>{{$list->payee}}</td><td align="right">{{number_format($list->amount,2)}}</td><td>{{$list->remarks}}</td>
         <td><a href="{{url('printdisbursement',$list->refno)}}">View</a></td><td>
         @if($list->isreverse == 0)
         OK
         @else
         Cancelled
         @endif
         </td></tr>
     <?php
     if($list->isreverse == '0'){
         $total = $total + $list->amount;
     }
     ?>
     @endforeach
     <tr><td colspan="5">Total</td><td align="right"><b>{{number_format($total,2)}}</b></td>
 </table>
 <br>
 <h5>Bank Account Summary</h5>
 <div class="col-md-6">
     <table class="table table-striped" >
     <tr><td>Bank Account</td><td>Amount</td></tr>
     @foreach($banktotal as $bt)
     <tr><td>{{$bt->bank}}</td><td align="right">{{number_format($bt->amount,2)}}</td></tr>
     <?php $totalbank = $totalbank + $bt->amount; ?>
     @endforeach
     <tr><td>Total</td><td align="right"><b>{{number_format($totalbank,2)}}</b></td></tr>
     
 </table>
 </div>
 
 </div>

<script>
    $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('dailydisbursementalllist')}}" + "/" + $("#fromdate").val() + "/" + $("#todate").val();
    })
</script>
@stop

