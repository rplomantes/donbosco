@extends('appaccounting')
@section('content')
<style>
    .header{font-size: 16pt; font-weight: bold}
    .date{font-size:12pt;}
</style>
<div class="container">
  <h3>JOURNAL ENTRIES</h3>
    
    
<div class="col-md-3">
<div class="form form-group">
    <label>Transaction Date :</label>
    <input type="text" id="trandate"  value="{{$trandate}}" class="form-control">
</div>
</div>
<div class="col-md-2">
<div class="form form-group">
    <br>    
    <button onclick="showtran()" class="btn btn-primary form-control">View Transaction</button>
</div>    
</div>
    
    
    <?php
    foreach($rangereports as $rangereport){
    $accountings = \App\Accounting::where('refno',$rangereport->refno)->get();
    $journal = \App\AccountingRemark::where('refno',$rangereport->refno)->first();
    $totaldebit=0;
    $totalcredit=0;
    ?>
    
    <table class="table table-bordered table-striped">
    <tr><td><b>GJ Voucher No<b> </td><td colspan="5"><span style="font-weight: bold;color:blue">{{$journal->referenceid}}</span></td></tr>
    
        
    <tr><td><b>Particular</b></td><td colspan="5" ><span style="font-size: 12pt; font-style: italic">{{$journal->remarks}}</span></td></tr>
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
        fromtran = document.getElementById("trandate").value
        
        document.location = "/generaljournal/" + fromtran  
     }
</script>    
@stop

