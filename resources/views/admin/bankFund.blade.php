@extends('appadmin')
@section('content')
<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;

$credittotal=0;
$debittotal=0;
$total=0;
?>
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
        $credittotal = $credittotal + $bank->credits;
        $debittotal = $debittotal + $bank->debit;
        $total = 
        ?>
        <tr>
            <td>{{$bank->accountname}}</td>
            <td style="text-align: right">{{$bank->debit}}</td>
            <td style="text-align: right">{{$bank->credits}}</td>
            <td style="text-align: right">{{$accttotal}}</td>
        </tr>
        @endforeach
    </table>
</div>
<div class="col-md-4">
<div style="width:100%;">
    {!!$chart->render()!!}
</div>    
</div>
@stop