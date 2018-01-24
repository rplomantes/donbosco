<?php

$lists = \App\Disbursement::whereBetween('transactiondate', array($fromdate,$todate))->get();
$banktotal = DB::Select("Select sum(amount) as amount, bank from disbursements where (transactiondate between '$fromdate' and '$todate')  and isreverse = '0' group by bank");
$total=0;
$totalbank=0;
$template = 'appaccounting';
if(in_array(Auth::user()->accesslevel,array(env('USER_ACCOUNTING'),env('USER_ACCOUNTING_HEAD')))){
    $template = 'appaccounting';
}elseif(in_array(Auth::user()->accesslevel,array(env('USER_ADMIN')))){
    $template = 'layouts.administrator';
}
?>

@extends($template)

@section('header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Disbursement Daily Summary
        @if(strtotime($fromdate) == strtotime($todate))
        <small>{{date('F d, Y',strtotime($fromdate))}}</small>
        @else
        <small>{{date('F d, Y',strtotime($fromdate))}} - {{date('F d, Y',strtotime($todate))}}</small>
        @endif
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
@endsection