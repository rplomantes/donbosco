<?php
$accountings = \App\Accounting::where('refno',$refno)->get();
$disbursement = \App\Disbursement::where('refno',$refno)->first();
$totaldebit=0;
$totalcredit=0;
$cancel = "Cancel";
if($disbursement->isreverse=='1'){
$cancel = "Restore";    
}

?>
@extends('appaccounting')
@section('content')
<div class="container">
    <div class="col-md-8">
    <h3>DISBURSEMENT DETAILS</h3>
    </div>
    <div class="col-md-4">
        <a href="{{url('restorecanceldisbursement',array($cancel,$refno))}}" class="btn btn-danger navbar-right" id="cancelRestore">{{$cancel}}</a>
    </div>    
    <table class="table table-bordered table-striped">
    
    <tr><td><b>Voucher Number<b> </td><td colspan="3"><span style="font-weight: bold;color:blue">{{$disbursement->voucherno}}</span></td></tr>
    <tr><td><b>Date</b> </td><td colspan="3">{{$disbursement->transactiondate}}</td></tr>
    <tr><td><b>Payee</b></td><td colspan="3"><b>{{$disbursement->payee}}</b></td></tr> 
    <tr><td><b>Bank Account<b></td><td colspan="3">{{$disbursement->bank}}</td></tr>    
    <tr><td><b>Check Number</b></td><td colspan="3">{{$disbursement->checkno}}</td></tr>    
    <tr><td><b>Amount</b></td><td colspan="3"><span style="font-size: 14pt;font-weight: bold;color: red">{{number_format($disbursement->amount,2)}}</span></td></tr>    
    <tr><td><b>Particular</b></td><td colspan="3" ><span style="font-size: 12pt; font-style: italic">{{$disbursement->remarks}}</span></td></tr>
    <tr><td colspan="4" align="center">A C C O U N T I N G &nbsp;&nbsp;&nbsp; E N T R I E S</td></tr>
    <tr><td><b>Account Code</b></td><td><b>Account Title</b></td><td><b>Debit</b></td><td><b>Credit</b></td></tr>
    @foreach($accountings as $accounting)
    <tr><td>{{$accounting->accountcode}}</td><td>{{$accounting->accountname}}</td><td align="right">{{number_format($accounting->debit,2)}}</td><td align="right">{{number_format($accounting->credit,2)}}</td></tr>
    <?php
    $totaldebit = $totaldebit + $accounting->debit;
    $totalcredit = $totalcredit + $accounting->credit; 
    ?>
    @endforeach
    </tr><td colspan="2"> Total</td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
    </table>
    <div class="col-md-6">
        <a href="{{url('printcheckdetails',$refno)}}" class="btn btn-primary form-control" target="_blank">Print Check Details</a>
    </div>    
    <div class="col-md-6">
        <a href="{{url('printcheckvoucher',$refno)}}" class="btn btn-primary form-control" target="_blank">Print Voucher</a>
    </div>    
</div>
@stop