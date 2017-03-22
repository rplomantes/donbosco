<?php
$lists = \App\Disbursement::where('transactiondate',$trandate)->where('postedby',\Auth::user()->idno)->get();
$banktotal = DB::Select("Select sum(amount) as amount, bank from disbursements where transactiondate = '$trandate' and isreverse = '0' group by bank");
$total=0;
$totalbank=0;
?>
@extends('appaccounting')
@section("content")
<div class="container">
 <h3>Disbursement Daily Summary</h3>
 <h5>Date : {{$trandate}}</h5>
 <table class="table table-bordered table-striped">
     <tr><td>Voucher No</td><td>Check No</td><td>Account No</td><td>Payee</td><td>Amount</td><td>Particular</td><td>View</td><td>Status</td></tr>
     @foreach($lists as $list)
     <tr><td>{{$list->voucherno}}</td><td>{{$list->checkno}}</td><td>{{$list->bank}}</td><td>{{$list->payee}}</td><td align="right">{{number_format($list->amount,2)}}</td><td>{{$list->remarks}}</td>
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
     <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($total,2)}}</b></td>
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
 <div class="col-md-12">
     <a href="{{url('printdisbursementlistpdf',$trandate)}}" class="btn btn-primary form-control" target="_blank">
         Print Disbursement Daily Summary</a>
 </div>
 </div>
@stop

