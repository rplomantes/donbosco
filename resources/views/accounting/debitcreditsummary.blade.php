@extends('appaccounting')
@section('content')
<style>
    table td{font-size:14pt; }
</style>
<div class = "container">
@if($entry == '1')
<h3>CASH RECEIPT DEBIT/CREDIT SUMMARY</h3>
@elseif($entry == "2")
<h3>DEBIT MEMO DEBIT/CREDIT SUMMARY</h3>
@elseif($entry=="3")
<h3>GENERAL JOURNAL DEBIT/CREDIT SUMMARY</h3>
@elseif($entry == "4")
<h3>CASH DISBURSEMENT DEBIT/CREDIT SUMMARY</h3>
@elseif($entry=="5")
<h3>SYSTEM GENERATED DEBIT/CREDIT SUMMARY</h3>
@endif
<input type="hidden" name="entry_type" id="entry_type" value="{{$entry}}">
<div class="col-md-3">
<div class class="form form-group">
<label>From :</label>
    <input type="text" id="fromtran" class="form-control" value="{{$fromtran}}">
</div>   
</div>    
<div class="col-md-3">
<div class="form form-group">
    <label>To :</label>
    <input type="text" id="totran"  value="{{$totran}}" class="form-control">
</div>
</div>
<div class="col-md-3">
<div class="form form-group">
    <br>    
    <button onclick="showtran()" class="btn btn-primary form-control">View Transaction</button>
</div>    
</div>
<div class="col-md-12">
    <table class="table table-striped table-bordered">
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            ?>
            <thead><tr><th>Acct No.</th><th>Account Title</th><th>Debit</th><th>Credit</th></tr></thead>
            @foreach($trials as $trial)
            <?php 
            $totaldebit = $totaldebit + $trial->debit;
            $totalcredit = $totalcredit + $trial->credits;
            ?>        
            <tr><td>{{$trial->accountingcode}}</td><td>{{$trial->accountname}}</td><td style="text-align: right">
                    @if($trial->debit > 0)
                    {{number_format($trial->debit, 2, '.', ', ')}}
                    @endif
                </td><td style="text-align: right">
                    @if($trial->credits > 0)
                    {{number_format($trial->credits, 2, '.', ', ')}}
                    @endif
                </td></tr>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>
    <div class="form form-group">
        <div class="col-md-12"><a class="form-control btn btn-danger" href="{{url('printmaincollection',array($entry,$fromtran,$totran))}}" target="_blank">Print</a></div>
    </div>    
</div>



</div>

<script>
function showtran(){
   //alert("hello")
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    var entry =  document.getElementById('entry_type').value
    document.location= "/maincollection/" + entry + "/" + fromtran + "/" + totran
}
</script>
@stop
