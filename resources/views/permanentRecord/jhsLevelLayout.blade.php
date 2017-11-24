<?php 
use App\Http\Controllers\Registrar\PermanentRecord; 
use App\Http\Controllers\Registrar\GradeController;
use App\Http\Controllers\Registrar\AttendanceController;
use App\Http\Controllers\Registrar\GradeComputation;
?>
<table style="font-size: 7pt;border: 3px solid" width="100%" border="1" cellspacing="0">
<tr style="font-size: 8pt">
    <td style="border-bottom: 2pt solid;border-left: 2pt solid;border-right: 2pt solid;height: 21.6px" colspan="6" class="border_top">
        <?php $grade7info = PermanentRecord::syInfo($idno,$level); ?>
        @if(count($grade7info)>0)
        <?php 
        $grade7Grades  = \App\Grade::whereIn('schoolyear',array($grade7info->schoolyear,($grade7info->schoolyear+1)." SUMMER"))->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
        $grade7Rank     = \App\Ranking::where('idno',$idno)->where('schoolyear',$grade7info->schoolyear)->first();
        ?>
        <table width='100%' cellspacing='0' >
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
<tr style="text-align: center" class="border_bottom border_left border_right border_top">
    <td width="60%" style="height: 21.6px"><b>SUBJECTS</b></td>
    <td style="font-size: 7pt">1</td>
    <td style="font-size: 7pt">2</td>
    <td style="font-size: 7pt">3</td>
    <td style="font-size: 7pt">4</td>
    <td style="font-size: 7pt">Final</td>
</tr>
<tr class="border_left border_right">
    <td ><b>CONDUCT GRADE</b></td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(3),0,1,$grade7Grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(3),0,2,$grade7Grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(3),0,3,$grade7Grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(3),0,4,$grade7Grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(3),0,5,$grade7Grades)}}</td>
</tr>
<tr class="border_left border_right border_top">
    <td colspan="6" style="height: 22px">ACADEMIC SUBJECTS</td>
</tr>
@foreach($grade7Grades as $grade)
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
    <tr class="border_left border_right" style='font-weight: bolder'>
        <td>ACADEMIC AVERAGE</td>
        <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(0),0,1,$grade7Grades)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(0),0,2,$grade7Grades)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(0),0,3,$grade7Grades)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(0),0,4,$grade7Grades)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(0),0,5,$grade7Grades)}}</td>
    </tr>

    <tr class="border_left border_right">
        <td>RANK</td>
        <td style="font-size: 7pt;text-align: center;"></td>
        <td style="font-size: 7pt;text-align: center;"></td>
        <td style="font-size: 7pt;text-align: center;"></td>
        <td style="font-size: 7pt;text-align: center;"></td>
        <td style="font-size: 7pt;text-align: center;"></td>
    </tr>
    <tr class="border_left border_right">
        <td colspan="6">TECHNICAL SUBJECTS</td>
    </tr>
    @foreach($grade7Grades as $grade)
        @if($grade->subjecttype == 1)
        <?php $weight7 = $grade->weighted;?>
        <tr class="border_left border_right">
            <td>{{strtoupper($grade->subjectname)}} @if($weight7 > 0)<span style='float: right;margin-right: 50px'>({{$weight7}}%)</span> @endif</td>
            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
        </tr>
        @endif
    @endforeach
        <tr class="border_left border_right" style='font-weight: bolder'>
            <td>TECHNICAL AVERAGE</td>
            <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(1),0,1,$grade7Grades)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(1),0,2,$grade7Grades)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(1),0,3,$grade7Grades)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(1),0,4,$grade7Grades)}}</td>
            <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($grade7info->schoolyear,$level,array(1),0,5,$grade7Grades)}}</td>
        </tr>
        <tr class="border_left border_right border_bottom">
            <td>RANK</td>
            <td style="font-size: 7pt;text-align: center;"></td>
            <td style="font-size: 7pt;text-align: center;"></td>
            <td style="font-size: 7pt;text-align: center;"></td>
            <td style="font-size: 7pt;text-align: center;"></td>
            <td style="font-size: 7pt;text-align: center;"></td>
        </tr>
        <tr class="border_left border_right border_top border_bottom">
            <td colspan="6">
                @foreach($grade7Grades as $grade)
                    @if($grade->schoolyear == ($grade7info->schoolyear+1)." SUMMER")<br>
                    {{$grade->schoolyear}} {{$grade->school}}: {{$grade->subjectname}} = {{round($grade->finalgrade,0)}}
                    @else
                    <div style="height: 1.5px"></div>
                    @endif
                @endforeach
            </td>
        </tr>
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
        <tr class="border_left border_right">
            <td>DAYS OF SCHOOL</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayp[1]+$daya[1]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayp[2]+$daya[2]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayp[3]+$daya[3]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
        </tr>
        <tr class="border_left border_right">
            <td>DAYS ABSENT</td>
            <td style="font-size: 7pt;text-align: center;">{{$daya[0]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$daya[1]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$daya[2]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$daya[3]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
        </tr>
        <tr class="border_left border_right border_bottom">
            <td>DAYS TARDY</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
            <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
        </tr>
    @endif
</table>