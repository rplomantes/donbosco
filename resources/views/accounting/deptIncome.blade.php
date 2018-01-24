<?php
$template = 'appaccounting';
if(in_array(Auth::user()->accesslevel,array(env('USER_ACCOUNTING'),env('USER_ACCOUNTING_HEAD')))){
    $template = 'appaccounting';
}elseif(in_array(Auth::user()->accesslevel,array(env('USER_ADMIN')))){
    $template = 'appadmin';
}
?>
@extends($template)
@section('content')
<?php
use App\Http\Controllers\Accounting\OfficeSumController;
use App\Http\Controllers\Accounting\DeptIncomeController;
$total = 0;
?>
<style>
    .amount{
        text-align: right
    }
</style>

<h4 style="text-align: left;">CONSOLIDATED DEPARTMENTAL
    @if($acctcode == 4)
    INCOME
    @elseif($acctcode == 1)
    ASSETS
    @else
    EXPENSE
    @endif
    SUBSIDIARY LEDGER
</h4>

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
    <button onclick="showtran()" class="btn btn-primary form-control">View Report</button>
</div>    
</div>

<table class="table table-striped">
    <thead>
        <th>ACCOUNT TITLE</th>
        <th>Total 
            @if($acctcode == 4)
                Income
            @elseif($acctcode == 1)
                Asset
            @else
                Expense
            @endif
	</th>
        @foreach($departments as $department)
        <th>{{str_replace(" Department","",$department)}}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach($coas as $coa)
        @if(DeptIncomeController::showAcct($accounts,$departments,$coa->acctcode,$acctcode))
            <?php
            $acctTotal = OfficeSumController::accounttotal($accounts,$coa->acctcode,$acctcode);
            $total = $total + $acctTotal;
            ?>
        <tr>
            <td>{{$coa->accountname}}</td>
            <td>{{number_format($acctTotal,2,' .',',')}}</td>
            @foreach($departments as $department)
            <td>
                {{OfficeSumController::accountdepttotal($accounts,$department,$coa->acctcode,$acctcode)}}
            </td>
            @endforeach
        </tr>
        @endif
        @endforeach
        <tr>
            <td>Grand Total</td>
            <td>{{number_format($total,2,' .',',')}}</td>
            @foreach($departments as $department)
            <td>
                {{OfficeSumController::deptTotal($accounts,$department,$acctcode)}}
            </td>
            @endforeach
        </tr>
    </tbody>
</table>

<br>
<div class="container">
    <a href="{{url('printconsolidate',array($acctcode,$fromtran,$totran))}}" class="col-md-12 btn btn-info">PRINT</a>
</div>

<script>
function showtran(){
   //alert("hello")
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    var acctcode =  {{$acctcode}}
    document.location= "/deptincome/" + acctcode + "/" + fromtran + "/" + totran
}
</script>
@stop

