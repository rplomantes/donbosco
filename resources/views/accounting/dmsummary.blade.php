@extends('appaccounting')
@section('content')
<style>
    .header{font-size: 16pt; font-weight: bold}
    .date{font-size:12pt;}
</style>
<div class="container">
  <h3>DEBIT MEMO ISSUED</h3>
    
   
<div class="col-md-3">
<div class="form form-group">
    <label>Transaction Date :</label>
    <input type="text" id="totran"  value="{{$trandate}}" class="form-control">
</div>
</div>
<div class="col-md-2">
<div class="form form-group">
    <br>    
    <button onclick="showtran()" class="btn btn-primary form-control">View Transaction</button>
</div>    
</div>
    
    
    <?php
    foreach($dms as $rangereport){
    $accountings = \App\Accounting::where('refno',$rangereport->refno)->get();
    $journal = \App\DebitMemo::where('refno',$rangereport->refno)->first();
    $totaldebit=0;
    $totalcredit=0;
    ?>
    
    <table class="table table-bordered table-striped">
    @if(count($journal)>0)    
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
    @endif
    
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
    <?php
    }
    ?>
</div>

<script>
    function showtran(){
        
        totran = document.getElementById("totran").value
        document.location = "/dmsummary/" + totran 
     }
</script>    
@stop

