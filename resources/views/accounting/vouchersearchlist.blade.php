<?php 
$template = 'appaccounting';
if(in_array(Auth::user()->accesslevel,array(env('USER_ACCOUNTING'),env('USER_ACCOUNTING_HEAD')))){
    $template = 'appaccounting';
}elseif(in_array(Auth::user()->accesslevel,array(env('USER_ADMIN')))){
    $template = 'appadmin';
}
?>
@extends($template)
@section("content")
<div class="container">
 <table class="table table-bordered table-striped">
     <tr><td>Voucher No</td><td>Check No</td><td>Account No</td><td>Payee</td><td>Amount</td><td>Particular</td><td>View</td><td>Status</td></tr>
     @foreach($disbursement as $list)
     <tr><td>{{$list->voucherno}}</td><td>{{$list->checkno}}</td><td>{{$list->bank}}</td><td>{{$list->payee}}</td><td align="right">{{number_format($list->amount,2)}}</td><td>{{$list->remarks}}</td>
         <td><a href="{{url('printdisbursement',$list->refno)}}">View</a></td><td>
         @if($list->isreverse == 0)
         OK
         @else
         Cancelled
         @endif
         </td></tr>
     @endforeach
 </table>
 </div>
@stop

