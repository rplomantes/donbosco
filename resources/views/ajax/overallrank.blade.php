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
            @if($subject->subjecttype == 1){
            <td>{{$subject->subjectname}}</td>
            @endif
        @endforeach
        @if($level == "Grade 7" | $level == "Grade 8" | $level == "Grade 9" | $level == "Grade 10")
            <td>General Average</td>
            <td>Overall Ranking</td>
        @endif
        <!--END TECH RANK-->
    </thead>
    <tbody>
        @foreach($students as $student)
        
        <?php 
            $studInfo = App\User::where('idno',$student->idno)->first();

            if($level == 'Grade 11' || $level == 'Grade 12'){
                $grades = \App\Grade::where('idno',$student->idno)->where('isdisplaycard',1)->where('quarter',$quarter)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
            }else{
                $grades = \App\Grade::where('idno',$student->idno)->where('isdisplaycard',1)->where('schoolyear',$sy)->orderBy('sortto','ASC')->get();
            }
            
            $acad_rank = 'No Rank';
        ?>
        
        <tr>
            <td>{{strrchr($student->section," ")}}</td>
            <td style="text-align: left">{{$studInfo->lastname}}, {{$studInfo->firstname}} {{$studInfo->middlename}} {{$studInfo->extensionname}}</td>
            @foreach($grades as $grade)
                <?php
                    switch($quarter){
                        case 1:
                            $acad_rank =$student->oa_acad_1;
                            break;
                        case 2:
                            $acad_rank =$student->oa_acad_2;
                            break;
                        case 3:
                            $acad_rank =$student->oa_acad_3;
                            break;
                        case 4:
                            $acad_rank =$student->oa_acad_4;
                            break;
                        default:
                            $acad_rank =$student->oa_acad_final;
                                break;
                    }
                    
                    $acad = GradeController::gradeSubjectAve($quarter,$grade,$level);
                ?>
                @if($grade->subjecttype == 5 || $grade->subjecttype == 6 || $grade->subjecttype == 0)
                <td>{{$acad}}</td>
                @endif
            @endforeach
            <td>{{GradeController::gradeQuarterAve(array(0,5,6),$quarter,$grades,$level)}}</td>
            <td>{{$acad_rank}}</td>
            @foreach($grades as $grade)
                <?php
                    switch($quarter){
                        case 1:
                            $tech = round($grade->first_grading,0);
                            $tech_rank =$student->oa_acad_1;
                            break;
                        case 2:
                            $tech = round($grade->second_grading,0);
                            $tech_rank =$student->oa_acad_2;
                            break;
                        case 3:
                            $tech = round($grade->third_grading,0);
                            $tech_rank =$student->oa_acad_3;
                            break;
                        case 4:
                            $tech = round($grade->fourth_grading,0);
                            $tech_rank =$student->oa_acad_4;
                            break;
                        default:
                            $acad = $grade->final_grade;
                            $tech_rank =$student->oa_acad_final;
                                break;
                    }
                ?>
                @if($grade->subjecttype == 1)
                <td>{{$tech}}</td>
                @endif
            @endforeach
            
        </tr>
        @endforeach
    </tbody>
</table>