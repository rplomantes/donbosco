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
        @if($disbursement->transactiondate == date('Y-m-d',strtotime(\Carbon\Carbon::now())) && \Auth::user()->accesslevel == "4" || \Auth::user()->accesslevel=="5")
        <a href="{{url('restorecanceldisbursement',array($cancel,$refno))}}" class="btn btn-danger navbar-right" id="cancelRestore">{{$cancel}}</a>
        @endif
    </div>    
    <table class="table table-bordered table-striped">
    
    <tr><td><b>Voucher Number<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$disbursement->voucherno}}</span></td></tr>
    <tr><td><b>Date</b> </td><td colspan="5">{{$disbursement->transactiondate}}</td></tr>
    <tr><td><b>Payee</b></td><td colspan="5"><b>{{$disbursement->payee}}</b></td></tr> 
    <tr><td><b>Bank Account<b></td><td colspan="5">{{$disbursement->bank}}</td></tr>    
    <tr><td><b>Check Number</b></td><td colspan="5">{{$disbursement->checkno}}</td></tr>    
    <tr><td><b>Amount</b></td><td colspan="5"><span style="font-size: 14pt;font-weight: bold;color: red">{{number_format($disbursement->amount,2)}}</span></td></tr>    
    <tr><td><b>Particular</b></td><td colspan="5" id="remarks"><span style="font-size: 12pt; font-style: italic">{{$disbursement->remarks}}</span>&nbsp;&nbsp;&nbsp;<a href='#' onclick="showInput('remarks','{{$disbursement->remarks}}')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td></tr>
    <tr><td colspan="6" align="center">A C C O U N T I N G &nbsp;&nbsp;&nbsp; E N T R I E S</td></tr>
    <tr><td><b>Account Code</b></td><td><b>Account Title</b></td><td><b>Subsidiary</b></td><td><b>Office</b></td><td><b>Debit</b></td><td><b>Credit</b></td></tr>
    @foreach($accountings as $accounting)
    <tr><td>{{$accounting->accountcode}}</td><td>{{$accounting->accountname}}</td><td>{{$accounting->subsidiary}}</td><td>{{$accounting->sub_department}}</td><td align="right">{{number_format($accounting->debit,2)}}</td><td align="right">{{number_format($accounting->credit,2)}}</td></tr>
    <?php
    $totaldebit = $totaldebit + $accounting->debit;
    $totalcredit = $totalcredit + $accounting->credit; 
    ?>
    @endforeach
    </tr><td colspan="4"> Total</td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
    </table>
    <div class="col-md-6">
        <a href="{{url('printcheckdetails',$refno)}}" class="btn btn-primary form-control" target="_blank">Print Check Details</a>
    </div>    
    <div class="col-md-6">
        <a href="{{url('printcheckvoucher',$refno)}}" class="btn btn-primary form-control" target="_blank">Print Voucher</a>
    </div>    
</div>

<script>
    function showInput(field,current){
        var input= "<input type='text' class='col-md-12 editfield' value='"+current+"' onkeyup='updateDM('"+field+"',this.value)'>";
        $("#"+field).html(input);
    }
    
    function updateDM(field,vals){
        var arrays = {};
        arrays ["editfields"]= field;
        arrays ["values"]= vals;
        arrays ["voucher"]= '{{$refno}}';

        $.ajax({
            type:"GET",
            url:"/editdisbursement",
            data:arrays,
            errors:function(){
                alert("Somethig went wrong while updating disbursement. Please call administrator");
            };
        });
    }
    
    $('.editfield').keyup(function(e){
        var key = e.which || e.keyCode;
        if(key == 13){
            location.reload();           
        }
    })
</script>
@stop