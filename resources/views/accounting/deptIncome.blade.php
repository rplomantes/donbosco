@extends('appaccounting')
@section('content')
<?php 
$totalnone = 0;
$totalrector = 0;
$totalservice = 0;
$totaladmin = 0;
$totalelem = 0;
$totalhs = 0;
$totaltvet = 0;
$totalpastoral = 0;
?>
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
    @foreach($consolidated as $acct)
    <?php 
    $totalnone = $totalnone + $acct->none;
    $totalrector = $totalrector + $acct->rector;
    $totalservice = $totalservice + $acct->service;
    $totaladmin = $totaladmin+ $acct->admin;
    $totalelem = $totalelem + $acct->elem;
    $totalhs = $totalhs + $acct->hs;
    $totaltvet = $totaltvet + $acct->tvet;
    $totalpastoral = $totalpastoral + $acct->pastoral;
    ?>
    <tr style="text-align: right">
        <td style="text-align: left">{{$acct->acctcode}}</td>
        <td>{{$acct->accountingcode}}</td>
        <td>@if($acct->none > 0){{number_format($acct->none,2,'.',', ')}}@endif</td>
        <td>@if($acct->rector > 0){{number_format($acct->rector,2,'.',', ')}}@endif</td>
        <td>@if($acct->service > 0){{number_format($acct->service,2,'.',', ')}}@endif</td>
        <td>@if($acct->admin > 0){{number_format($acct->admin,2,'.',', ')}}@endif</td>
        <td>@if($acct->elem > 0){{number_format($acct->elem,2,'.',', ')}}@endif</td>
        <td>@if($acct->hs > 0){{number_format($acct->hs,2,'.',', ')}}@endif</td>
        <td>@if($acct->tvet > 0){{number_format($acct->tvet,2,'.',', ')}}@endif</td>
        <td>@if($acct->pastoral > 0){{number_format($acct->pastoral,2,'.',', ')}}@endif</td>
    </tr>
    @endforeach
    <tr style="text-align: right;font-weight: bold">
        <td colspan="2" style="text-align: center">Grand Total: </td>
        
        <td>@if($totalnone > 0){{number_format($totalnone,2,'.',', ')}}@endif</td>
        <td>@if($totalrector > 0){{number_format($totalrector,2,'.',', ')}}@endif</td>
        <td>@if($totalservice > 0){{number_format($totalservice,2,'.',', ')}}@endif</td>
        <td>@if($totaladmin > 0){{number_format($totaladmin,2,'.',', ')}}@endif</td>
        <td>@if($totalelem > 0){{number_format($totalelem,2,'.',', ')}}@endif</td>
        <td>@if($totalhs > 0){{number_format($totalhs,2,'.',', ')}}@endif</td>
        <td>@if($totaltvet > 0){{number_format($totaltvet,2,'.',', ')}}@endif</td>
        <td>@if($totalpastoral > 0){{number_format($totalpastoral,2,'.',', ')}}@endif</td>
    </tr>
</table>

@stop