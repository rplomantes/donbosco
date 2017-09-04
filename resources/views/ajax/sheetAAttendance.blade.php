<?php use App\Http\Controllers\Registrar\AttendanceController as Attendance; ?>
<table class="table table-bordered">
    <tr style="text-align: center">
        <td>CLASS NO</td>
        <td>LAST NAME</td>
        <td>FIRST NAME</td>
        <td>DAYS PRESENT</td>
        <td>DAYS TARDY</td>
        <td>DAYS ABSENT</td>
    </tr>
    <?php 
    $cn = 1; 
    ?>
    @foreach($students as $student)
    <?php
    $name = \App\User::where('idno',$student->idno)->first();
    $attendance = Attendance::studentQuarterAttendance($student->idno,$sy,$quarter,$level);
    ?>
    <tr>
        <td style="text-align: center">{{$cn}}</td>
        <td>{{$name->lastname}}</td>
        <td>{{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
        <td style="text-align: center">{{$attendance[0]}}</td>
        <td style="text-align: center">{{$attendance[2]}}</td>
        <td style="text-align: center">{{$attendance[1]}}</td>
    </tr>
    <?php $cn++; ?>
    @endforeach
</table>