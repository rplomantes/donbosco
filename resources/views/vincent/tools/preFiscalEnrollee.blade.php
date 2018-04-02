<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
use App\Http\Controllers\ReportGenerator as Generate;

?>

@extends('appaccounting')
@section('content')
<table class='table table-striped'>
    <tr>
        <td>Name</td>
        <td>Enrollment Date</td>
        <td>Level</td>
        <td>Tuition Fee</td>
        <td>Discount</td>
    </tr>
    @foreach($students as $student)
    <?php $amounts = Generate::getTuition($student->idno, $student->schoolyear);?>
    <tr>
        <td>{{Info::get_name($student->idno)}}</td>
        <td>{{$student->date_enrolled}}</td>
        <td>{{Info::get_level($student->idno, $student->schoolyear)}}</td>
        <td>{{$amounts['amount']}}</td>
        <td>{{$amounts['discount']}}</td>
    </tr>
    @endforeach
</table>
@stop