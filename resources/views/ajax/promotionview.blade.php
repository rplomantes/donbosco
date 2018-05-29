<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

?>
<table class='table table-bordered'>
    <tr style='text-align: center' id='fixed'>
        <td></td>
        <td>IDNO</td>
        <td>STUDENT'S NAME</td>
        <td>SEC</td>
        <td>ADMISSION</td>
        <td>CONDUCT</td>
        <td>ACADEMIC</td>
        <td>TECHNICAL</td>
    </tr>
    <?php $row = 1;?>
    @foreach($students as $student)
    <?php
    $promotionStat = App\StudentPromotion::where('schoolyear',$sy)->where('idno',$student->idno)->first();
    ?>
    <tr>
        <td style='text-align: center'>{{$row}}</td>
        <td>{{$student->idno}}</td>
        <td>{{Info::get_name($student->idno)}}</td>
        <td>{{$student->section}}</td>
        
        <td style='text-align: center'>{{$promotionStat ? $promotionStat->admission:""}}</td>
        <td style='text-align: center'>{{$promotionStat ? $promotionStat->conduct:""}}</td>
        <td style='text-align: center'>{{$promotionStat ? $promotionStat->academic:""}}</td>
        <td style='text-align: center'>{{$promotionStat ? $promotionStat->technical:""}}</td>
    </tr>
    <?php $row++;?>
    @endforeach
    @if(count($students) <= 0)
    <tr>
        <td colspan='7' style='text-align: center'>No Records Retrieved</td>
    </tr>
    @endif
</table>