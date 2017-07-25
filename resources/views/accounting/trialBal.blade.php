@extends('appaccounting')
@section('content')
<?php use App\Http\Controllers\Accounting\Helper; ?>
<div class="container">
    <h5>Trial Balance</h5>
    <p><b>Period Covered</b></p>
    <label>From</label>
    <input type="text" id="fromtran" class="form" value="{{$fromtran}}">
    <label>To</label>
    <input type="text" id="totran" class="form" value="{{$totran}}">
    <button onclick="showtran()" class="btn btn-primary">View Transaction</button>
    <br>
    <br>
    <br>
    <div class="col-md-12">
        <table class="table table-striped ">
            <thead><tr><th>Acct No.</th><th>Account Title</th><th>Amount</th></tr></thead>
            @foreach($trials as $trial)
            <tr>
                <td>{{$trial->accountingcode}}</td>
                <td>{{$trial->accountname}}</td>
                <td style="text-align: right">
                    <?php $accttotal = Helper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode);?>
                    @if($accttotal < 0)
                    ({{number_format(abs($accttotal),2)}})
                    @else
                    {{number_format($accttotal,2)}}
                    @endif
                </td>
            </tr>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format(Helper::allaccttotal($trials),2)}}</td></tr>
        </table>
    </div>
    <a class="col-md-12 btn btn-danger" href="{{url('printtrialbalance',array($fromtran,$totran))}}">Print</a>
</div>
    


<script>
function showtran(){
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    document.location="/trialbalance/" + fromtran + "/" + totran
}
</script>
@stop

