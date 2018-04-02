<?php 
use App\Http\Controllers\Accounting\IndividualAccountSmmary\ReportController as IAS;
?>
@extends('appaccounting')
@section('content')
<div class='container-fluid'>
    <div class='col-md-3'>
        {!!IAS::renderForm($request)!!}
    </div>
    <div class='col-md-9' id="report">

        @if($request->input('account') != "")
        {!!IAS::renderResult($request)!!}
        @endif
    </div>
    
</div>
<script type='text/javascript'>
    $('#iasform').submit(function(){
        $('#report').html('<div class="col-md-12 fa-3x" style="padding-top:30px;" id="spinner" align="center"><i class="fa fa-3x fa-spinner fa-pulse"></i></div>');
    });
</script>
@stop

