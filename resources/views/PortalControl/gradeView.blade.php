<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
$quarters = 5;
?>
@extends('app')
@section('content')
<div class='container'>
    <h3>Grade Viewing Control</h3>
    <h3><small>{{$level}} / {{$section}}  for SY {{$schoolyear}}</small></h3>
    
    <table class='table table-striped'>
        <tr>
            <td>Class No</td>
            <td>Name</td>
            @for($i=1;$i <= $quarters;$i++)
            <td style="text-align: center">
                Quarter {{$i}}
                <div><input type="checkbox" class='allquarter' id='quarter{{$i}}all' data-quarter='{{$i}}'></div>
            </td>
            @endfor
        </tr>
        @foreach($students as $student)
        <tr>
            <td>{{$student->class_no}}</td>
            <td>{{Info::get_name($student->idno)}}</td>
            @for($i=1;$i <= $quarters;$i++)
            <td style="text-align: center"><input type="checkbox" class='quarter{{$i}}' data-id='{{$student->idno}}' data-quarter='{{$i}}' ></td>
            @endfor
        </tr>
        @endforeach
    </table>
</div>
<script>
    $('.allquarter').change(function(){
        var quarter = $(this).
        if(this.check){
            
        }
    });
</script>
@stop