<?php
    use App\Http\Controllers\Registrar\GradeController;
?>
<table style='text-align:center' border='1' cellspacing='0' cellpadding='1' width='150%'>
    <thead>
        <td>Section</td>
        <td>Name</td>
        @foreach($subjects as $subject)
            @if($subject->subjecttype == 0)
            <td>{{$subject->subjectname}}</td>
            @endif
            @if($subject->subjecttype == 5 |$subject->subjecttype == 6)
            <td>{{$subject->subjectname}}</td>
            @endif
        @endforeach
        <td>General Average</td>
        <td>Overall Ranking</td>
        
        <!--TECH RANK-->
        @foreach($subjects as $subject)
            @if($subject->subjecttype == 1)
            <td>{{$subject->subjectname}}</td>
            @endif
        @endforeach
        @if(in_array($level,array('Grade 7','Grade 8','Grade 9','Grade 10')))
            <td>General Average</td>
            <td>Overall Ranking</td>
        @endif
        <!--END TECH RANK-->
    </thead>
    <tbody>
        @foreach($students as $student)
        
        <?php 
            $studInfo = App\User::where('idno',$student->idno)->first();  
            $grades = \App\Grade::where('idno',$student->idno)->where('schoolyear',$sy)->where('isdisplaycard',1)->get();
        ?>
        
        <tr>
            <td>{{strrchr($student->section," ")}}</td>
            <td style="text-align: left">{{$studInfo->lastname}}, {{$studInfo->firstname}} {{$studInfo->middlename}} {{$studInfo->extensionname}}</td>
            @foreach($grades as $grade)
                @if($grade->subjecttype == 5 || $grade->subjecttype == 6 || $grade->subjecttype == 0)
                <td>{{round($grade->$gradefield,0)}}</td>
                @endif
            @endforeach
            <td>{{GradeController::gradeQuarterAve(array(0,5,6),array($semester),$quarter,$grades,$level)}}</td>
            <td>{{$student->acad}}</td>
            
            @foreach($grades as $grade)
                @if($grade->subjecttype == 1)
                <td>{{round($grade->$gradefield,0)}}</td>
                @endif
            @endforeach
            @if(in_array($level,array('Grade 7','Grade 8','Grade 9','Grade 10')))
            <?php
                $subjectsetting = \App\GradesSetting::where('level',$level)->where('schoolyear',$sy)->whereIn('subjecttype',array(1))->first();
            ?>
            <td>
            @if($subjectsetting->calculation == "A")
                {{GradeController::gradeQuarterAve(array(1),array($semester),$quarter,$grades,$level)}}
            @elseif($subjectsetting->calculation == "W")
                {{GradeController::weightedgradeQuarterAve(array(1),array($semester),$quarter,$grades,$level)}}
            @endif
            </td>
            <td>{{$student->tech}}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
