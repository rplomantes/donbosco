@extends('appadmin')
@section('content')
<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;

$credittotal=0;
$debittotal=0;
$total=0;
?>
<div class='container-fluid'><h3 class='col-md-12'>Bank Accounts Debit/Credit</h3></div>
<div class="container-fluid">
    <div class="col-md-8 form-inline">
        <div class="form-group">
            <label class='control-label col-md-4' for="from">Fiscal Year:</label>
            <div class=' col-md-8'>
                <input type='text' class="form-control" id='from' value='{{$fromdate}}' placeholder="YYYY-MM-DD">
            </div>
        </div>
        <button onclick="viewreport()" class="btn btn-danger">Submit</button>
    </div>
    <div class="col-md-3">
    </div>
</div>
<div class='container-fluid'>
    <div class="col-md-6">
        <table class="table table-borderless">
            <tr>
                <td>Account</td>
                <td style="text-align: right">Debit</td>
                <td style="text-align: right">Credit</td>
                <td style="text-align: right">Total</td>
            </tr>
            @foreach($banks as $bank)
            <?php 
            $accttotal = round(AcctHelper::getaccttotal($bank->credits,$bank->debit,$bank->entry),2); 
            $total = $total + $accttotal;
            $credittotal = $credittotal + $bank->credits;
            $debittotal = $debittotal + $bank->debit;
            ?>
            <tr>
                <td>{{$bank->accountname}}</td>
                <td style="text-align: right">{{number_format($bank->debit,2)}}</td>
                <td style="text-align: right">{{number_format($bank->credits,2)}}</td>
                <td style="text-align: right">{{number_format($accttotal,2)}}</td>
            </tr>
            @endforeach
            <tr>
                <td>Total</td>
                <td style="text-align: right">{{number_format($debittotal,2)}}</td>
                <td style="text-align: right">{{number_format($debittotal,2)}}</td>
                <td style="text-align: right">{{number_format($total,2)}}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
    <div style="width:100%;">
        {!!$chart->render()!!}
    </div>    
    </div>
</div>
<script>
    function viewreport(){
        var from = $('#from').val();
        var to = $('#to').val();
        
        window.location.href = "/bankfunds/"+from+"/"+to;
    }
</script>
@stop