<?php
$journal =  \App\DebitMemo::where('refno',$refno)->first();
$accountings = \App\Accounting::where('refno',$refno)->get();
$totaldebit=0;
$totalcredit=0;
$iscancel = "Cancel";
if($journal->isreverse =="1"){
    $iscancel = "Restore";
}
?>

@extends('appaccounting')
@section('content')
<div class="container">
    <div class="col-md-2 col-md-offset-8">
        <a href="{{url('accounting',$journal->idno)}}" class="btn btn-primary form-control">View Ledger</a>
    </div>
    <div class="col-md-2">
        @if($journal->transactiondate == date('Y-m-d',strtotime(\Carbon\Carbon::now())) && \Auth::user()->accesslevel != env('USER_ACCOUNTING_HEAD') || \Auth::user()->accesslevel == env('USER_ACCOUNTING_HEAD') )
        <a href="{{url('restorecanceldm',array($iscancel,$refno))}}" class =" btn btn-danger  form-control">{{$iscancel}}</a>
        @endif
    </div>
    <div class="col-md-12"> &nbsp;
    </div>  
    <div class="col-md-12">
    <table class="table table-bordered table-striped">
    <tr><td><b>Debit Voucher No<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$journal->voucherno}}</span></td></tr>
    <tr><td><b>Date<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$journal->transactiondate}}</span></td></tr>
    <tr><td><b>Student No<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$journal->idno}}</span></td></tr>
    <tr><td><b>Student Name<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$journal->fullname}}</span></td></tr>    
    <tr><td><b>Particular</b></td><td colspan="5" ><span style="font-size: 12pt; font-style: italic">{{$journal->remarks}}</span></td></tr>
                <tr><td><b>Status</b></td><td colspan="5" ><span style="font-size: 12pt; font-style: italic">
        @if($journal->isreverse == '0')
        OK
        @else
        Cancelled
        @endif
                        </span></td></tr>
    <tr><td colspan="6" align="center">A C C O U N T I N G &nbsp;&nbsp;&nbsp; E N T R I E S</td></tr>
    <tr><td><b>Account Code</b></td><td><b>Account Title</b></td><td><b>Subsidiary</b></td><td><b>Office</b></td><td><b>Debit</b></td><td><b>Credit</b></td></tr>
    
    
    @foreach($accountings as $accounting)
    @if(count($accountings)>0)
    <tr><td>{{$accounting->accountcode}}</td><td>{{$accounting->accountname}}</td><td>{{$accounting->subsidiary}}</td><td>{{$accounting->sub_department}}</td><td align="right">{{number_format($accounting->debit,2)}}</td><td align="right">{{number_format($accounting->credit,2)}}</td></tr>
    
    <?php
    $totaldebit = $totaldebit + $accounting->debit;
    $totalcredit = $totalcredit + $accounting->credit; 
    ?>
    @endif
    @endforeach
    </tr><td colspan="4"> Total</td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
    </table>
    </div>
        
        
        <div class="col-md-2 col-md-offset-10">
            <a href="{{url('printdm',$refno)}}" class="btn btn-primary form-control" target="_blank">Print DM Voucher</a>
        </div> 
    </div>
          
@stop


