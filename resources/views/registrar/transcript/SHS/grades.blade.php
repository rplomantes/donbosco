<?php
use App\Http\Controllers\Registrar\GradeController;
?>
<table width="75%" style="font-size: 6.5pt" cellspacing="0" cellpadding="0">
    @if($status->level == "Grade 11")
    <tr>
        <td colspan="3" >
            <b>
                ACADEMIC TRACK:
                @if($status->strand == "ABM")
                    Accountancy, Business and Management (ABM)
                @elseif($status->strand == "STEM")
                    Science, Technology, Engineering and Mathematics (STEM)
                @endif
            </b>
        </td>
    </tr>
    @endif
    <tr>
        <td width="45%">{{strtoupper($status->level)}} - {{$status->section}}</td>
        <td width="30%" style="text-align: right">School ID</td>
        <td width="25%" style="text-align: center">School Year</td>
    </tr>
    <tr>
        <td width="45%">School: DON BOSCO-MAKATI</td>
        <td width="30%" style="text-align: right">1403014</td>
        <td width="25%" style="text-align: center">{{$status->schoolyear}} - {{$status->schoolyear+1}}</td>
    </tr>
</table>

<table width="100%" cellspacing="0" style='font-size: 6.5pt'>
    <tr style="font-size: 6.5pt;">
        <td width="49%">
            <b>FIRST SEMESTER</b>
        </td>
        <td width="2%"></td>
        <td width="49%">
            <b>SECOND SEMESTER</b>
        </td>
    </tr>

    <tr>
        <td valign="top" width="59%">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr style="text-align: center">
                    <td width="60%"><b>SUBJECTS</b></td>
                    <td>FIRST<br>QUARTER</td>
                    <td>SECOND<br>QUARTER</td>
                    <td>FINAL<br>GRADE</td>
                </tr>
                <tr>
                    <td>CONDUCT GRADE</td>
                    <td style="text-align: center;">{{round(GradeController::conductQuarterAve(3,1,$grades),0)}}</td>
                    <td style="text-align: center;">@if(GradeController::conductQuarterAve(3,2,$grades)){{round(GradeController::conductQuarterAve(3,2,$grades),0)}}@endif</td>
                    <td style="text-align: center;">@if(GradeController::conductQuarterAve(3,2,$grades)){{round(GradeController::conductTotalAve($grades,1),0)}}@endif</td>
                </tr>
                <tr ><td colspan="4" style="padding-left: 45px;"><b>CORE SUBJECTS</b></td></tr>

                @foreach($grades as $grade)
                    @if($grade->semester == 1 && $grade->subjecttype == 5)
                    <tr>
                        <td>{{strtoupper($grade->subjectname)}}</td>
                        <td style="text-align: center;">@if($grade->first_grading > 0){{round($grade->first_grading,0)}}@endif</td>
                        <td style="text-align: center;">@if($grade->second_grading > 0){{round($grade->second_grading,0)}}@endif</td>
                        <td style="text-align: center;">@if($grade->second_grading > 0){{round($grade->final_grade,0)}}@endif</td>
                    </tr>
                    @endif
                @endforeach

                <tr ><td colspan="4" style="padding-left: 45px;"><b>APPLIED AND SPECIALIZED SUBJECTS</b></td></tr>
                @foreach($grades as $grade)
                    @if($grade->semester == 1 && $grade->subjecttype == 6)
                    <tr>

                        <td>{{strtoupper($grade->subjectname)}}</td>
                        <td style="text-align: center;">@if($grade->first_grading > 0){{round($grade->first_grading,0)}}@endif</td>
                        <td style="text-align: center;">@if($grade->second_grading > 0){{round($grade->second_grading,0)}}@endif</td>
                        <td style="text-align: center;">@if($grade->second_grading > 0){{round($grade->final_grade,0)}}@endif</td>
                    </tr>
                    @endif
                @endforeach

                <tr >
                    <td colspan="4" style="padding-bottom:10pt;height:10pt;text-align: left;vertical-align: top">
                        <?php
                        $remedial = $grades->where('semester',1,false)->filter(function($item){
                            if(round($item->final_grade) < 75){
                                return true;
                            }
                        });
                        ?>
                        @if(count($remedial)>0)
                        {{$status->schoolyear}} Remedial {{$remedial->implode('subjectcode','=75;')}}=75
                        @endif
                        
                    </td>
                </tr>
                <tr style='font-weight: bold'>
                    <td>GENERAL AVERAGE FOR THE SEMESTER</td>
                    <td style="text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(1),1,$grades,$status->level)}}</td>
                    <td style="text-align: center;">
                            @if(GradeController::gradeQuarterAve(array(0,5,6),array(1),2,$grades,$status->level) > 0)
                            {{GradeController::gradeQuarterAve(array(0,5,6),array(1),2,$grades,$status->level)}}
                            @endif
                    </td>
                    <td style="text-align: center;">
                        @if(GradeController::gradeQuarterAve(array(0,5,6),array(1),2,$grades,$status->level) > 0)
                        {{GradeController::gradeQuarterAve(array(0,5,6),array(1),5,$grades,$status->level)}}
                        @endif
                    </td>
                </tr>
                    <tr>
                        <td>DAYS OF SCHOOL</td>
                        <?php
                            $q1Att = ($q1daya[0] + $q1dayp[0]) > 0;
                            $q2Att = ($q1daya[1] + $q1dayp[1]) > 0;
                        ?>
                        <td style="text-align: center;">
                            @if($q1Att)
                            {{$q1daya[0] + $q1dayp[0]}}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($q2Att)
                            {{$q1daya[1] + $q1dayp[1]}}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($q2Att)
                            {{($q1daya[1] + $q1dayp[1])+($q1daya[0] + $q1dayp[0])}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>DAYS ABSENT</td>
                        <td style="text-align: center;">{{$q1daya[0]}}</td>
                        <td style="text-align: center;">{{$q1daya[1]}}</td>
                        <td style="text-align: center;">
                            @if($q2Att)
                            {{$q1daya[0]+$q1daya[1]}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>DAYS TARDY</td>
                        <td style="text-align: center;">{{$q1dayt[0]}}</td>
                        <td style="text-align: center;">{{$q1dayt[1]}}</td>
                        <td style="text-align: center;">
                            @if($q2Att)
                            {{$q1dayt[0]+$q1dayt[1]}}
                            @endif
                        </td>
                    </tr>
            </table>
        </td>
        <td width="2%" style="padding-top:8pt"></td>
        <td valign="top" width="59%">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr style="text-align: center">
                    <td width="60%"><b>SUBJECTS</b></td>
                    <td>FIRST<br>QUARTER</td>
                    <td>SECOND<br>QUARTER</td>
                    <td>FINAL<br>GRADE</td>
                </tr>
                <tr>
                    <td>CONDUCT GRADE</td>
                    <td style="text-align: center;">{{round(GradeController::conductQuarterAve(3,3,$grades),0)}}</td>
                    <td style="text-align: center;">@if(GradeController::conductQuarterAve(3,4,$grades) > 0){{round(GradeController::conductQuarterAve(3,4,$grades),0)}}@endif</td>
                    
                    <td style="text-align: center;">@if(GradeController::conductQuarterAve(3,4,$grades) > 0){{round(GradeController::conductTotalAve($grades,2),0)}}@endif</td>
                </tr>
                <tr ><td colspan="4" style="padding-left: 45px;"><b>CORE SUBJECTS</b></td></tr>
                @foreach($grades as $grade)
                    @if($grade->semester == 2 && $grade->subjecttype == 5)
                    <tr>
                        <td>
                            {{strtoupper($grade->subjectname)}}
                        </td>
                        <td style="text-align: center;">{{round($grade->third_grading,0)}}</td>
                        <td style="text-align: center;">@if($grade->fourth_grading > 0){{round($grade->fourth_grading,0)}}@endif</td>
                        <td style="text-align: center;">@if($grade->fourth_grading > 0){{round($grade->final_grade,0)}}@endif</td>
                    </tr>
                    @endif
                @endforeach
                <tr ><td colspan="4" style="padding-left: 45px;"><b>APPLIED AND SPECIALIZED SUBJECTS</b></td></tr>
                @foreach($grades as $grade)
                    @if($grade->semester == 2 && $grade->subjecttype == 6)
                    <tr>
                        <td>{{strtoupper($grade->subjectname)}}</td>
                        <td style="text-align: center;">{{round($grade->third_grading,0)}}</td>
                        <td style="text-align: center;">@if($grade->fourth_grading > 0){{round($grade->fourth_grading,0)}}@endif</td>
                        <td style="text-align: center;">@if($grade->fourth_grading > 0){{round($grade->final_grade,0)}}@endif</td>
                    </tr>
                    @endif
                @endforeach
                <tr >
                    <td colspan="4" style="padding-bottom:10pt;height:10pt;text-align: left;vertical-align: top">
                        <?php
                        $summer = $grades->where('semester',2,false)->filter(function($item){
                            if(round($item->final_grade) < 75){
                                return true;
                            }
                        });
                        ?>
                        @if(count($summer) > 0 && GradeController::gradeQuarterAve(array(0,5,6),array(2),4,$grades,$status->level) > 0)
                        {{$status->schoolyear+1}} Summer {{$summer->implode('subjectcode','=75;')}}=75
                        @endif
                        
                    </td>
                </tr>
                <tr style='font-weight: bold'>
                    <td>GENERAL AVERAGE FOR THE SEMESTER</td>
                    <td id="moom" style="text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(2),3,$grades,$status->level)}}</td>
                    <td style="text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(2),4,$grades,$status->level)}}</td>
                    <td style="text-align: center;">
                        @if(GradeController::gradeQuarterAve(array(0,5,6),array(2),4,$grades,$status->level))
                        {{GradeController::gradeQuarterAve(array(0,5,6),array(2),5,$grades,$status->level)}}
                        @endif
                    </td>
                </tr>
                        <?php
                            $q3Att = ($q2daya[0] + $q2dayp[0]) > 0;
                            $q4Att = ($q2daya[1] + $q2dayp[1]) > 0;
                        ?>
                <tr>
                    <td>DAYS OF SCHOOL</td>
                    <td style="text-align: center;">
                        @if($q3Att)
                        {{$q2daya[0] + $q2dayp[0]}}
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($q4Att)
                        {{$q2daya[1] + $q2dayp[1]}}
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($q4Att)
                        {{($q2daya[0] + $q2dayp[0])+($q2daya[1] + $q2dayp[1])}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>DAYS ABSENT</td>
                    <td style="text-align: center;">{{$q2daya[0]}}</td>
                    <td style="text-align: center;">{{$q2daya[1]}}</td>
                    <td style="text-align: center;">
                        @if($q4Att)
                        {{$q2daya[0]+$q2daya[1]}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>DAYS TARDY</td>
                    <td style="text-align: center;">{{$q2dayt[0]}}</td>
                    <td style="text-align: center;">{{$q2dayt[1]}}</td>
                    <td style="text-align: center;">
                        @if($q4Att)
                        {{$q2dayt[0]+$q2dayt[1]}}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
