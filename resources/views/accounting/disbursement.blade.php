@extends('appaccounting')
@section('content')
<style>
    .header{font-size: 16pt; font-weight: bold}
    .date{font-size:12pt;}
</style>
<div class="container">
  <h3>CASH DISBURSEMENT REPORT</h3>
    
<div class="col-md-3">
<div class class="form form-group">
<label>From :</label>
    <input type="text" id="trandate" class="form-control" value="{{$trandate}}">
</div>   
</div>    

<div class="col-md-3">
<div class="form form-group">
    <br>    
    <button onclick="showtran()" class="btn btn-primary form-control">View Transaction</button>
</div>    
</div>
    
    
    <?php
    foreach($rangereports as $rangereport){
    $accountings = \App\Accounting::where('refno',$rangereport->refno)->get();
    $disbursement = \App\Disbursement::where('refno',$rangereport->refno)->first();
    $totaldebit=0;
    $totalcredit=0;
    ?>
    
    <table class="table table-bordered table-striped">
    <tr><td><b>Voucher Number<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$disbursement->voucherno}}</span></td></tr>
    
    <tr><td><b>Payee</b></td><td colspan="5"><b>{{$disbursement->payee}}</b></td></tr> 
    <tr><td><b>Bank Account<b></td><td colspan="5">{{$disbursement->bank}}</td></tr>    
    <tr><td><b>Check Number</b></td><td colspan="5">{{$disbursement->checkno}}</td></tr>    
    <tr><td><b>Amount</b></td><td colspan="5"><span style="font-size: 14pt;font-weight: bold;color: red">{{number_format($disbursement->amount,2)}}</span></td></tr>    
    <tr><td><b>Particular</b></td><td colspan="5" ><span style="font-size: 12pt; font-style: italic">{{$disbursement->remarks}}</span></td></tr>
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
    <?php
    }
    ?>
</div>

<script>
    function showtran(){
        trandate = document.getElementById("trandate").value
        document.location = "/disbursement/" + trandate; 
     }
</script>    
@stop