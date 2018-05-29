<?php
use App\Http\Controllers\Accounting\Budgeting\BudgetReport;

?>
@extends('appaccounting')
@section('content')
<div class='container-fluid'>
    <h3>Budget Report for {{$fiscalyear}}</h3>
</div>
<div class='container-fluid'>
    {!!BudgetReport::renderMainReport($from, $to, $fiscalyear, "")!!}
</div>
@stop