<?php
use App\Http\Controllers\Registrar\Statistics\Enrollment;

?>
@extends('app')
@section('content')
<style>
    td .table{
        margin-bottom:0px;
    }
</style>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-md-7'>
            {!!Enrollment::kto12Enrollment($schoolyear)!!}
        </div>
        <div class='col-md-5'>
            {!!Enrollment::tvetEnrollment()!!}
            <br>
            {!!Enrollment::departmentalEnrollment($schoolyear)!!}
        </div>
    </div>
</div>
@stop