<!DOCTYPE html>
<?php
use App\Http\Controllers\Registrar\GradeController;
use App\Http\Controllers\Registrar\PermanentRecord;
use App\Http\Controllers\Registrar\AttendanceController;
?>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta author="John Vincent Villanueva">
    <meta poweredby = "Nephila Web Technology, Inc">
    
    <style>
        body{
            margin-left: -20px;
            margin-right: -20px;
            margin-top: -40px;
            margin-bottom: -40px;
            font-family: dejavu sans;
            
        }
        .underscore{
            border-bottom: 1px solid;
        }
        tr.border_bottom td {
          border-bottom:2pt solid black;
        }
        
        tr.border_left td:first-child {
          border-left:2pt solid black;
        }
        tr.border_right td:last-child {
          border-right:2pt solid black;
        }
        
    </style>
</head>
<body style="border:1px solid;">
    <table width="100%" style='min-height: 250px;'>
        <tr>
            <td></td>
        </tr>
    </table>
    
    <table width="100%" cellspacing="0">
        <tr>
            <td valign="top" width='49%' style="min-height: 615pt;height:615pt;vertical-align: bottom;">
                @if($grade7 == 1)
                    <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr>
                        <td colspan="6">
                            <?php $grade7info = PermanentRecord::syInfo($idno,'Grade 7'); ?>
                            @if(count($grade7info)>0)
                            <?php 
                            $grade7Grades  = \App\Grade::where('schoolyear',$grade7info->schoolyear)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
                            $grade7Rank     = \App\Ranking::where('idno',$idno)->where('schoolyear',$grade7info->schoolyear)->first();
                            ?>
                            <table width='100%' cellspacing='0'>
                                <tr>
                                    <td style='text-align: left'>{{strtoupper($grade7info->level)}} - {{$grade7info->section}}</td>
                                    <td style='text-align: right'>School Year</td>
                                </tr>
                                <tr>
                                    <td style='text-align: left'>School: DON BOSCO - MAKATI</td>
                                    <td style='text-align: right'>{{$grade7info->schoolyear}}-{{$grade7info->schoolyear+1}}</td>
                                </tr>
                            </table>
                            @endif
                        </td>
                    </tr>
                    @if(isset($grade7Grades))
                    <tr style="text-align: center">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">1</td>
                        <td style="font-size: 7pt">2</td>
                        <td style="font-size: 7pt">3</td>
                        <td style="font-size: 7pt">4</td>
                        <td style="font-size: 7pt">Final</td>
                    </tr>
                    <tr>
                        <td colspan="6">ACADEMIC SUBJECTS</td>
                    </tr>
                    @foreach($grade7Grades as $grade)
                        @if($grade->subjecttype == 0)
                        <tr>
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        @endif
                    @endforeach
                        <tr style='font-weight: bold'>
                            <td>ACADEMI AVERAGE</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade7Grades,'Grade 7')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade7Grades,'Grade 7')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade7Grades,'Grade 7')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade7Grades,'Grade 7')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade7Grades,'Grade 7')}}</td>
                        </tr>

                        <tr>
                            <td>RANK</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_acad_1}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_acad_2}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_acad_3}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_acad_4}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_acad_final}}</td>
                        </tr>
                        <tr>
                            <td colspan="6">TECHNICAL SUBJECTS</td>
                        </tr>
                        @foreach($grade7Grades as $grade)
                            @if($grade->subjecttype == 1)
                            <?php $weight7 = $grade->weighted;?>
                            <tr>
                                <td>{{strtoupper($grade->subjectname)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                            </tr>
                            @endif
                        @endforeach
                            <tr style='font-weight: bold'>
                                <td>TECHNICAL AVERAGE</td>
                                @if($weight7 > 0)
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),1,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),2,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),3,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),4,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grade7Grades,'Grade 7')}}</td>
                                @else
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade7Grades,'Grade 7')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade7Grades,'Grade 7')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>RANK</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_tech_1}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_tech_2}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_tech_3}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_tech_4}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade7Rank->oa_tech_final}}</td>
                            </tr>
                            <tr><td colspan="6" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                            <?php 
                            $dayp = array();
                            $daya = array();
                            $dayt = array();
                            for($i=1; $i < 5 ;$i++){
                                $attendance  = AttendanceController::studentQuarterAttendance($idno,$grade7info->schoolyear,$i,'Grade 7'); 
                                $dayp [] = $attendance[0];
                                $daya [] = $attendance[1];
                                $dayt [] = $attendance[2];
                            }
                            ?>
                            <tr>
                                <td>DAYS OF SCHOOL</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[1]+$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[2]+$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[3]+$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS ABSENT</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS TARDY</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
                            </tr>
                        @endif
                </table>
                @endif
            </td>
            <td width='2%'></td>
            <td width='49%'>
                @if($grade9 == 1)
                <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr>
                        <td colspan="6">
                            <?php $grade9info = PermanentRecord::syInfo($idno,'Grade 9'); ?>
                            @if(count($grade9info)>0)
                            <?php 
                            $grade9Grades  = \App\Grade::where('schoolyear',$grade9info->schoolyear)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
                            $grade9Rank     = \App\Ranking::where('idno',$idno)->where('schoolyear',$grade9info->schoolyear)->first();
                            ?>
                            <table width='100%' cellspacing='0'>
                                <tr>
                                    <td style='text-align: left'>{{strtoupper($grade9info->level)}} - {{$grade9info->section}}</td>
                                    <td style='text-align: right'>School Year</td>
                                </tr>
                                <tr>
                                    <td style='text-align: left'>School: DON BOSCO - MAKATI</td>
                                    <td style='text-align: right'>{{$grade9info->schoolyear}}-{{$grade9info->schoolyear+1}}</td>
                                </tr>
                            </table>
                            @endif
                        </td>
                    </tr>
                    @if(isset($grade9Grades))
                    <tr style="text-align: center">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">1</td>
                        <td style="font-size: 7pt">2</td>
                        <td style="font-size: 7pt">3</td>
                        <td style="font-size: 7pt">4</td>
                        <td style="font-size: 7pt">Final</td>
                    </tr>
                    <tr>
                        <td colspan="6">ACADEMIC SUBJECTS</td>
                    </tr>
                    @foreach($grade9Grades as $grade)
                        @if($grade->subjecttype == 0)
                        <tr>
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        @endif
                    @endforeach
                        <tr style='font-weight: bold'>
                            <td>ACADEMI AVERAGE</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade9Grades,'Grade 9')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade9Grades,'Grade 9')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade9Grades,'Grade 9')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade9Grades,'Grade 9')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade9Grades,'Grade 9')}}</td>
                        </tr>

                        <tr>
                            <td>RANK</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_acad_1}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_acad_2}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_acad_3}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_acad_4}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_acad_final}}</td>
                        </tr>
                        <tr>
                            <td colspan="6">TECHNICAL SUBJECTS</td>
                        </tr>
                        @foreach($grade9Grades as $grade)
                            @if($grade->subjecttype == 1)
                            <?php $weight7 = $grade->weighted;?>
                            <tr>
                                <td>{{strtoupper($grade->subjectname)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                            </tr>
                            @endif
                        @endforeach
                            <tr style='font-weight: bold'>
                                <td>TECHNICAL AVERAGE</td>
                                @if($weight7 > 0)
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),1,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),2,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),3,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),4,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grade9Grades,'Grade 9')}}</td>
                                @else
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade9Grades,'Grade 9')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade9Grades,'Grade 9')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>RANK</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_tech_1}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_tech_2}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_tech_3}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_tech_4}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade9Rank->oa_tech_final}}</td>
                            </tr>
                            <tr ><td colspan="6" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                            <?php 
                            $dayp = array();
                            $daya = array();
                            $dayt = array();
                            for($i=1; $i < 5 ;$i++){
                                $attendance  = AttendanceController::studentQuarterAttendance($idno,$grade9info->schoolyear,$i,'Grade 9'); 
                                $dayp [] = $attendance[0];
                                $daya [] = $attendance[1];
                                $dayt [] = $attendance[2];
                            }
                            ?>
                            <tr>
                                <td>DAYS OF SCHOOL</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[1]+$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[2]+$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[3]+$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS ABSENT</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS TARDY</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
                            </tr>
                        @endif
                </table>
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: bottom;">
                @if($grade8 == 1)
                <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr>
                        <td colspan="6">
                            <?php $grade8info = PermanentRecord::syInfo($idno,'Grade 8'); ?>
                            @if(count($grade8info)>0)
                            <?php 
                            $grade8Grades  = \App\Grade::where('schoolyear',$grade8info->schoolyear)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
                            $grade8Rank     = \App\Ranking::where('idno',$idno)->where('schoolyear',$grade8info->schoolyear)->first();
                            ?>
                            <table width='100%' cellspacing='0'>
                                <tr>
                                    <td style='text-align: left'>{{strtoupper($grade8info->level)}} - {{$grade8info->section}}</td>
                                    <td style='text-align: right'>School Year</td>
                                </tr>
                                <tr>
                                    <td style='text-align: left'>School: DON BOSCO - MAKATI</td>
                                    <td style='text-align: right'>{{$grade8info->schoolyear}}-{{$grade8info->schoolyear+1}}</td>
                                </tr>
                            </table>
                            @endif
                        </td>
                    </tr>
                    @if(isset($grade8Grades))
                    <tr style="text-align: center">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">1</td>
                        <td style="font-size: 7pt">2</td>
                        <td style="font-size: 7pt">3</td>
                        <td style="font-size: 7pt">4</td>
                        <td style="font-size: 7pt">Final</td>
                    </tr>
                    <tr>
                        <td colspan="6">ACADEMIC SUBJECTS</td>
                    </tr>
                    @foreach($grade8Grades as $grade)
                        @if($grade->subjecttype == 0)
                        <tr>
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        @endif
                    @endforeach
                        <tr style='font-weight: bold'>
                            <td>ACADEMI AVERAGE</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade8Grades,'Grade 8')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade8Grades,'Grade 8')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade8Grades,'Grade 8')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade8Grades,'Grade 8')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade8Grades,'Grade 8')}}</td>
                        </tr>

                        <tr>
                            <td>RANK</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_acad_1}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_acad_2}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_acad_3}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_acad_4}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_acad_final}}</td>
                        </tr>
                        <tr>
                            <td colspan="6">TECHNICAL SUBJECTS</td>
                        </tr>
                        @foreach($grade8Grades as $grade)
                            @if($grade->subjecttype == 1)
                            <?php $weight7 = $grade->weighted;?>
                            <tr>
                                <td>{{strtoupper($grade->subjectname)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                            </tr>
                            @endif
                        @endforeach
                            <tr style='font-weight: bold'>
                                <td>TECHNICAL AVERAGE</td>
                                @if($weight7 > 0)
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),1,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),2,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),3,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),4,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grade8Grades,'Grade 8')}}</td>
                                @else
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade8Grades,'Grade 8')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade8Grades,'Grade 8')}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>RANK</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_tech_1}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_tech_2}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_tech_3}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_tech_4}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade8Rank->oa_tech_final}}</td>
                            </tr>
                            <tr ><td colspan="6" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                            <?php 
                            $dayp = array();
                            $daya = array();
                            $dayt = array();
                            for($i=1; $i < 5 ;$i++){
                                $attendance  = AttendanceController::studentQuarterAttendance($idno,$grade8info->schoolyear,$i,'Grade 8'); 
                                $dayp [] = $attendance[0];
                                $daya [] = $attendance[1];
                                $dayt [] = $attendance[2];
                            }
                            ?>
                            <tr>
                                <td>DAYS OF SCHOOL</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[1]+$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[2]+$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[3]+$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS ABSENT</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS TARDY</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
                            </tr>
                        @endif
                </table>
                @endif
            </td>
            <td></td>
            <td style="vertical-align: bottom;">
                @if($grade10 == 1)
                <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr>
                        <td colspan="6" style="border: 2pt solid;">
                            <?php $grade10info = PermanentRecord::syInfo($idno,'Grade 10'); ?>
                            @if(count($grade10info)>0)
                            <?php 
                            $grade10Grades  = \App\Grade::where('schoolyear',$grade10info->schoolyear)->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
                            $grade10Rank     = \App\Ranking::where('idno',$idno)->where('schoolyear',$grade10info->schoolyear)->first();
                            ?>
                            <table width='100%' cellspacing='0'>
                                <tr>
                                    <td style='text-align: left'>{{strtoupper($grade10info->level)}} - {{$grade10info->section}}</td>
                                    <td style='text-align: right'>School Year</td>
                                </tr>
                                <tr>
                                    <td style='text-align: left'>School: DON BOSCO - MAKATI</td>
                                    <td style='text-align: right'>{{$grade10info->schoolyear}}-{{$grade10info->schoolyear+1}}</td>
                                </tr>
                            </table>
                            @endif
                        </td>
                    </tr>
                    @if(isset($grade10Grades))
                    <tr style="text-align: center" class="border_bottom border_left border_right">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">1</td>
                        <td style="font-size: 7pt">2</td>
                        <td style="font-size: 7pt">3</td>
                        <td style="font-size: 7pt">4</td>
                        <td style="font-size: 7pt">Final</td>
                    </tr>
                    <tr class="border_left border_right">
                        <td colspan="6">ACADEMIC SUBJECTS</td>
                    </tr>
                    @foreach($grade10Grades as $grade)
                        @if($grade->subjecttype == 0)
                        <tr class="border_left border_right">
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        @endif
                    @endforeach
                        <tr class="border_left border_right" style='font-weight: bold'>
                            <td>ACADEMI AVERAGE</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade10Grades,'Grade 10')}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade10Grades,'Grade 10')}}</td>
                        </tr>

                        <tr class="border_left border_right">
                            <td>RANK</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_1}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_2}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_3}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_4}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_acad_final}}</td>
                        </tr>
                        <tr class="border_left border_right">
                            <td colspan="6">TECHNICAL SUBJECTS</td>
                        </tr>
                        @foreach($grade10Grades as $grade)
                            @if($grade->subjecttype == 1)
                            <?php $weight7 = $grade->weighted;?>
                            <tr class="border_left border_right">
                                <td>{{strtoupper($grade->subjectname)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                            </tr>
                            @endif
                        @endforeach
                            <tr style='font-weight: bold' class="border_left border_right">
                                <td>TECHNICAL AVERAGE</td>
                                @if($weight7 > 0)
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),1,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),2,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),3,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),4,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grade10Grades,'Grade 10')}}</td>
                                @else
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grade10Grades,'Grade 10')}}</td>
                                    <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grade10Grades,'Grade 10')}}</td>
                                @endif
                            </tr>
                            <tr class="border_left border_right border_bottom">
                                <td>RANK</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_1}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_2}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_3}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_4}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$grade10Rank->oa_tech_final}}</td>
                            </tr>
                            <tr ><td colspan="6" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                            <?php 
                            $dayp = array();
                            $daya = array();
                            $dayt = array();
                            for($i=1; $i < 5 ;$i++){
                                $attendance  = AttendanceController::studentQuarterAttendance($idno,$grade10info->schoolyear,$i,'Grade 10'); 
                                $dayp [] = $attendance[0];
                                $daya [] = $attendance[1];
                                $dayt [] = $attendance[2];
                            }
                            ?>
                            <tr>
                                <td>DAYS OF SCHOOL</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[1]+$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[2]+$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[3]+$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS ABSENT</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
                            </tr>
                            <tr>
                                <td>DAYS TARDY</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
                                <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
                            </tr>
                        @endif
                </table>
                @endif
            </td>
        </tr>
    </table>
   
    <div>

    </div>
</body>
</html>

