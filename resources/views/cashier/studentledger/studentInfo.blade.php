<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

?>
<table class="table table-striped">
    <tr>
        <td>Student ID</td>
        <td>{{$idno}}</td>
        <td>Department</td>
        <td>{{Info::get_department($idno,$currSy)}}</td>        
    </tr>
    <tr>
        <td>Student Name</td>
        <td><b>{{Info::get_name($idno)}}</b></td>
        <td>Level</td>
        <td>{{Info::get_level($idno,$currSy)}}</td>        
    </tr>
    <tr>
        <td>Gender</td>
        <td><b>{{Info::get_gender($idno)}}</b></td>
        <td>Level</td>
        <td>{{Info::get_section($idno,$currSy)}}</td>        
    </tr>
    <tr>
        <td>Status</td>
        <td><b>{{Info::get_statusWord($idno,$currSy)}}</b></td>
        <td>Specialization</td>
        <td>{{Info::get_strand($idno,$currSy)}}</td>        
    </tr>

</table>