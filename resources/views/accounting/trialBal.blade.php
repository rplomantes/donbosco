@extends('appaccounting')
@section('content')
<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;
?>
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
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            ?>
            <thead><tr><th>Acct No.</th><th>Account Title</th><th>Debit</th><th>Credit</th></tr></thead>
            @foreach($trials as $trial)
 
            <tr><td>{{$trial->accountingcode}}</td><td>{{$trial->accountname}}</td><td style="text-align: right">
                    @if(in_array(substr($trial->accountingcode,0,1),array(1,5)))
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        )
                        @endif
<?php
                    $totaldebit = $totaldebit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2);
?>
@else
{{number_format(0,2)}}
                    @endif

                </td><td style="text-align: right">
                    @if(in_array(substr($trial->accountingcode,0,1),array(2,3,4)))
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        )
                        @endif
<?php
                    $totalcredit = $totalcredit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2);
?>
@else
{{number_format(0,2)}}

                    @endif
                </td></tr>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>
    </div>
</div>
    

<div class="col-md-offset-10 col-md-2"><a class="btn btn-danger" href="{{url('printtrialbalance',array($fromtran,$totran))}}">Print</a></div>
<script>
function showtran(){
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    document.location="/trialbalance/" + fromtran + "/" + totran
}
</script>
@stop

