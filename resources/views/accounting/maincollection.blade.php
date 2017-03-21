@extends('appaccounting')
@section('content')

<div class="container">
    @if($entry == 1)
    <h5> Cash Receipt Debit/Credit Summary Report</h5>
    @elseif($entry == 2)
    <h5> Debit Memo Debit/Credit Summary Report</h5>
    @elseif($entry == 3)
    <h5> General Journal Debit/Credit Summary Report</h5>
    @elseif($entry == 4)
    <h5> Cash Disbursement Debit/Credit Summary Report</h5>
    @endif
    
    <p>Period Covered</p>
    <label>From</label>
    <input type="text" id="fromtran" class="form" value="{{$fromtran}}">
    <label>To</label>
    <input type="text" id="totran" class="form" value="{{$totran}}">
    <button onclick="showtran()" class="btn btn-primary">View Transaction</button>
    <h5>Credit</h5>
    <table class="table table-striped">
        <tr><td>Account Name</td><td>Amount</td></tr>
        <?php $totalcredit=0;?>
        @foreach($credits as $credit)
        <?php $totalcredit = $totalcredit + $credit->amount;?>
        <tr><td>{{$credit->acctcode}}</td><td align="right">{{number_format($credit->amount,2)}}</td></tr>
        @endforeach
        <tr><td><b>Total Credit</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
     </table>   
</div>
<div class="container" style="page-break-inside: avoid">
<h5>Debit</h5>
    <table class="table table-striped" style="page-break-inside: avoid">
    <tr><td>Partcular</td><td align="right">Amount</td></tr>
    <?php $totaldebits=0;?>    
    @if(count($debitcashchecks)>0)
    @foreach($debitcashchecks as $debitcashcheck)
    <?php $totaldebits = $totaldebits + $debitcashcheck->totalamount;?>
    <tr><td>{{$debitcashcheck->acctcode}} {{$debitcashcheck->depositto}}</td><td align="right">{{number_format($debitcashcheck->totalamount,2)}}</td></tr>
    @endforeach    
    @endif
    
    
    
   <tr><td><b>Total Debit</b></td><td align="right"><b>{{number_format($totaldebits,2)}}</b></td></tr> 
</table>
</div>

<div class="col-md-12"><a class="form-control btn btn-danger" href="{{url('printmaincollection',array($entry,$fromtran,$totran))}}">Print</a></div>
<script>
function showtran(){
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    document.location="/maincollection/"+{{$entry}} + "/" + fromtran + "/" + totran
}
</script>
@stop

