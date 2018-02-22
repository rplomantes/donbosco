<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

$ledger = Info::get_studentLedger($idno,$enrollmentSy);
?>

@extends('app')
@section('content')
<div class="container-fluid">
    <div class="col-md-6">
        <div class="col-md-12 panel-body">
            <a class="btn btn-primary" href="{{url('/')}}">Back</a>
            <a class="btn btn-primary" href="{{url('/registrar/edit',$idno)}}">Edit Name</a>
            <a class="btn btn-primary" href="{{url('/registrar/evaluate',$idno)}}">Reset</a>
        </div>
        
        <div class="col-md-12">
            <table class="table table-striped">
                <tr><td>Student ID</td><td>{{$idno}}</td></tr>
                <tr><td>Student Name</td><td><strong>{{Info::get_name($idno)}}</strong></td></tr>
                <tr><td>Gender</td><td>{{Info::get_gender($idno)}}</td></tr>
            </table>
        </div>
        
        <div class='col-md-12'>
            <table border ="1"  class="table table-bordered">
                <thead><tr><th>Status</th><th>Current Balance</th></tr></thead>
                <tbody>
                    <tr>
                        <td>{{Info::get_statusWord($idno,$enrollmentSy)}}</td>
                        <td>0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop