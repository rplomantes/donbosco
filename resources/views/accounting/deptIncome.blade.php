@extends('appaccounting')
@section('content')
<style>
    .amount{
        text-align: right
    }
</style>
<?php 
use App\Http\Controllers\Accounting\DeptIncomeController;

$totalnone = 0;
$totalrector = 0;
$totalservice = 0;
$totaladmin = 0;
$totalelem = 0;
$totalhs = 0;
$totaltvet = 0;
$totalpastoral = 0;
$abstotal = 0;
?>

<h4 style="text-align: left;">CONSOLIDATED DEPARTMENTAL
    @if($accountcode == 4)
    INCOME
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
        <th>Total Income</th>
        <th>None</th>
        <th>Rector</th>
        <th>Student Services</th>
        <th>Administration</th>
        <th>Grade School</th>
        <th>High School</th>
        <th>TVET</th>
        <th>Pastoral</th>
    </thead>
    
    @foreach($accounts as $account)
    <?php

        $none = 0;
        $rector= 0;
        $elem= 0;
        $hs= 0;
        $tvet= 0;
        $service= 0;
        $admin= 0;
        $pastoral= 0;
        
        $creditnone = 0;
        $creditrector= 0;
        $creditelem= 0;
        $crediths= 0;
        $credittvet= 0;
        $creditservice= 0;
        $creditadmin= 0;
        $creditpastoral= 0;
        
        $debitnone = 0;
        $debitrector= 0;
        $debitelem= 0;
        $debiths= 0;
        $debittvet= 0;
        $debitservice= 0;
        $debitadmin= 0;
        $debitpastoral= 0;
        
        $total = 0;
        
        
            $creditnone = $account->creditnone;
            $creditrector= $account->creditrector;
            $creditelem= $account->creditelem;
            $crediths= $account->crediths;
            $credittvet= $account->credittvet;
            $creditservice= $account->creditservice;
            $creditadmin= $account->creditadmin;
            $creditpastoral= $account->creditpastoral;
        
        
        
            $debitnone = $account->debitnone;
            $debitrector= $account->debitrector;
            $debitelem= $account->debitelem;
            $debiths= $account->debiths;
            $debittvet= $account->debittvet;
            $debitservice= $account->debitservice;
            $debitadmin= $account->debitadmin;
            $debitpastoral= $account->debitpastoral;
        
        if($accountcode == 4){
            $none = $creditnone - $debitnone;
            $rector= $creditrector - $debitrector;
            $elem= $creditelem - $debitelem;
            $hs= $crediths - $debiths;
            $tvet= $credittvet - $debittvet;
            $service= $creditservice - $debitservice;
            $admin= $creditadmin - $debitadmin;
            $pastoral= $creditpastoral - $debitpastoral;
        }else{
            $none =  $debitnone - $creditnone;
            $rector= $debitrector - $creditrector;
            $elem= $debitelem - $creditelem;
            $hs= $debiths - $crediths;
            $tvet= $debittvet - $credittvet;
            $service= $debitservice - $creditservice;
            $admin= $debitadmin - $creditadmin;
            $pastoral= $debitpastoral - $creditpastoral;
        }
        
        
        $total = $none + $rector + $elem + $hs + $tvet + $service + $admin + $pastoral;
        
        $totalnone = $totalnone + $none;
        $totalrector = $totalrector + $rector;
        $totalservice = $totalservice + $service;
        $totaladmin = $totaladmin + $admin;
        $totalelem = $totalelem + $elem;
        $totalhs = $totalhs + $hs;
        $totaltvet = $totaltvet + $tvet;
        $totalpastoral = $totalpastoral + $pastoral;
        $abstotal = $abstotal + $total;
    ?>
    
    <tr>
        <td>{{$account->accountname}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($total)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($none)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($rector)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($service)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($admin)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($elem)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($hs)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($tvet)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($pastoral)}}</td>
    </tr>    
    @endforeach
    <tr>
        <td class="amount"><b>Total</b></td>
        <td class="amount">{{DeptIncomeController::returnzero($abstotal)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totalnone)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totalrector)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totalservice)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totaladmin)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totalelem)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totalhs)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totaltvet)}}</td>
        <td class="amount">{{DeptIncomeController::returnzero($totalpastoral)}}</td>
    </tr>
</table>
<br>
<div class="container">
    <a href="{{url('printconsolidate',array($accountcode,$fromtran,$totran))}}" class="col-md-12 btn btn-info">PRINT</a>
</div>

<script>
function showtran(){
   //alert("hello")
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    var acctcode =  {{$accountcode}}
    document.location= "/deptincome/" + acctcode + "/" + fromtran + "/" + totran
}
</script>
@stop