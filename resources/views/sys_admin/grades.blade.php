<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

?>
<table border='1'>
    <tr>
        <td rowspan='3'>Name</td>
        @foreach($subjects as $subject)
        <td colspan="2">{{$subject->subjectcode}}</td>
        @endforeach
    </tr>
    <tr>
        @foreach($subjects as $subject)
        @if($subject->semester == 1)
        <td colspan="2">1st Semester</td>
        @else
        <td colspan="2">2nd Semester</td>
        @endif
        @endforeach
    </tr>
    <tr>
        @foreach($subjects as $subject)
        <td>1st Quarter</td>
        <td>2nd Quarter</td>
        @endforeach
    </tr>
    @foreach($students as $student)
    <tr>
        <td>{{Info::get_propername($student->idno)}}</td>
        @foreach($subjects as $subject)
        <?php $grade = \App\Grade::where('idno',$student->idno)->where('subjectcode',trim($subject->subjectcode))->where('schoolyear','2017')->first(); ?>
        @if($grade)
            @if($grade->semester == 1)
            <td>{{number_format($grade->first_grading)}}</td>
            <td>{{number_format($grade->second_grading)}}</td>
            @else
            <td>{{number_format($grade->third_grading)}}</td>
            <td>{{number_format($grade->fourth_grading)}}</td>
            @endif
        @else
        <td></td>
        <td></td>
        @endif
        
        @endforeach
    </tr>
    @endforeach
</table>