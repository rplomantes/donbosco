@extends('appaccounting')
@section('content')
<div class="container">
    <?php
    $totalcbc=0;
    $totalbpi1=0;
    $totalbpi2=0;
    ?>
    <h3>Don Bosco Technical Institute, Inc.</h3>
    <p>Collection report as of {{$transactiondate}}</p>
    <h5>Deposit (China Bank)</h5>
    <table class="table table-striped"><tr><td>Posted By </td><td>Deposit Type</td><td align="center">Amount</td></tr>
        @if(count($actualcbc)>0)
       <?php $totalcbc=0;?> 
        @foreach($actualcbc as $acbc)
        <?php $totalcbc = $totalcbc + $acbc->amount;?>
         <tr><td>{{$acbc->postedby}}</td><td>@if($acbc->deposittype == "0")
                                        Cash
                                         @else
                                         Check
                                         @endif
                                    </td><td align="right">{{number_format($acbc->amount,2)}}</td></tr>     
        @endforeach
        <tr><td colspan="2">Total</td><td align="right"><b>{{number_format($totalcbc,2)}}</b></td></tr>
        @else
        <tr><td colspan="3">No Deposit slip Issued</td></tr>
        @endif
    </table>
    <h5> Deposit (BPI 1)</h5>
    <table class="table table-striped"><tr><td>Posted By </td><td>Deposit Type</td><td align="center">Amount</td></tr>
        @if(count($actualbpi1)>0)
        <?php $totalbpi1=0;?>
        @foreach($actualbpi1 as $acbc)
        <?php $totalbpi1 = $totalbpi1 + $acbc->amount;?>
         <tr><td>{{$acbc->postedby}}</td><td>@if($acbc->deposittype == "0")
                                        Cash
                                         @else
                                         Check
                                         @endif
                                    </td><td align="right">{{number_format($acbc->amount,2)}}</td></tr>     
        @endforeach
        <tr><td colspan="2">Total</td><td align="right"><b>{{number_format($totalbpi1,2)}}</b></td></tr>
        @else
        <tr><td colspan="3">No Deposit slip Issued</td></tr>
        @endif
    </table>
    <h5> Deposit (BPI 2)</h5>
    <table class="table table-striped"><tr><td>Posted By </td><td>Deposit Type</td><td align="center">Amount</td></tr>
        @if(count($actualbpi2)>0)
        
        <?php $totalbpi2=0;?>
        @foreach($actualbpi2 as $acbc)
        <?php $totalbpi2 = $totalbpi2 + $acbc->amount;?>
         <tr><td>{{$acbc->postedby}}</td><td>@if($acbc->deposittype == "0")
                                        Cash
                                         @else
                                         Check
                                         @endif
                                    </td><td align="right">{{number_format($acbc->amount,2)}}</td></tr>     
        @endforeach
        <tr><td colspan="2">Total</td><td align="right"><b>{{number_format($totalbpi2,2)}}</b></td></tr>
        @else
        <tr><td colspan="3">No Deposit slip Issued</td></tr>
        @endif
    </table>
    <h5>Computed Receipt</h5>
    <table class="table table-striped">
        <tr><td>Posted By</td><td>Bank Account</td><td align="center">Cash Amount</td><td align="center">Check Amount</td><td align="center">Total</td></tr>
        <?php $totalamount = 0; $totalcheckamount="0";?>
        @foreach($computedreceipts as $computedreceipt)
        <?php $totalamount = $totalamount + $computedreceipt->amount;
        $totalcheckamount = $totalcheckamount + $computedreceipt->checkamount;?>
        <tr><td>{{$computedreceipt->postedby}}</td><td>{{$computedreceipt->depositto}}</td><td align="right">{{number_format($computedreceipt->amount,2)}}</td><td align="right">{{number_format($computedreceipt->checkamount,2)}}</td>
            <td align="right">{{number_format($computedreceipt->amount + $computedreceipt->checkamount,2)}}</td></tr>
        @endforeach
        <tr><td colspan="2">Total</td><td align="right">{{number_format($totalamount,2)}}</td><td align="right">{{number_format($totalcheckamount,2)}}</td>
            <td align="right">{{number_format($totalamount + $totalcheckamount,2)}}</td></tr>
    </table>
   
    <h5>Encashment</h5>
    <table class="table table-striped"><tr><td>Posted By</td><td>Bank</td><td>Amount</td></tr>
        <?php $totalencash = 0;?>
        @foreach($encashments as $encashment)
        <tr><td>{{$encashment->postedby}}</td><td>{{$encashment->whattype}}</td><td align="right">{{$encashment->amount}}</td></tr>
        <?php $totalencash = $totalencash + $encashment->amount;?>
        @endforeach
        <tr><td colspan="2">Total</td><td align="right">{{number_format($totalencash,2)}}</td></tr>
    </table> 
    <div class="col-md-6">
    <h5>Difference</h5>
    <table class="table table-striped">
    <tr><td>Total Computed Receipt</td><td>{{number_format($totalamount + $totalcheckamount,2)}}</td></tr>
    <tr><td>Total Actual Deposit</td><td>{{number_format($totalcbc + $totalbpi1 + $totalbpi2,2)}}</td></tr> 
    <tr><td>Difference</td><td>{{number_format($totalamount + $totalcheckamount - $totalcbc - $totalbpi1 - $totalbpi2,2)}}</td></tr> 
    </table>
    </div>
    <div class="col-md-12">
        <a href="{{url('printactualoverall',$transactiondate)}}" class="btn btn-primary">Print Actual Deposit Report</a>
    </div>    
</div>
@stop

