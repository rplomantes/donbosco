@extends('appaccounting')
@section('content')
<div class="container-fluid">
    <div class="col-md-3">
        <a href="{{url('dailydisbursementalllist',array(date('Y-m-d'),date('Y-m-d')))}}" class="btn btn-default col-md-12">Disbursement List</a>
        <a href="{{url('overallcollection',date('Y-m-d'))}}" class="btn btn-default col-md-12">Collection Report</a>
        <br>
        <hr>
        <br>
        <a href="{{url('deptincome',array(4,date('Y-m-d'),date('Y-m-d')))}}" class="btn btn-default col-md-12">Consolidated Income Reports</a>
        <a href="{{url('deptincome',array(5,date('Y-m-d'),date('Y-m-d')))}}" class="btn btn-default col-md-12">Consolidated Expense Reports</a>
        
        <a href="{{url('departmentalsummary',array(date('Y-m-d'),date('Y-m-d'),'Student Services',1))}}" class="btn btn-default col-md-12">Departmental Income Reports</a>
        <a href="{{url('departmentalsummary',array(date('Y-m-d'),date('Y-m-d'),'Student Services',1))}}" class="btn btn-default col-md-12">Departmental Expense Reports</a>
        <br>
        <hr>
        <br>
        <a href="#" class="btn btn-default col-md-12">Cash Flow</a>
    </div>
    <div class="col-md-4">        
        <iframe src="{{url('samplewidget')}}" height="350px" width="100%" scrolling="no" frameborder="0" style="border:0"></iframe>
    </div>
    <div class="col-md-5">
        <iframe src="{{url('consolidatedbar')}}" height="350px" width="100%" scrolling="no" frameborder="0" style="border:0"></iframe>
    </div>
</div>
@stop