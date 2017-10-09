<?php 
use App\Http\Controllers\Registrar\PermanentRecord; 
use App\Http\Controllers\Registrar\GradeController;
use App\Http\Controllers\Registrar\AttendanceController;
use App\Http\Controllers\Registrar\GradeComputation;
use App\Http\Controllers\Registrar\PermanentRec\Helper as RecordHelper;

$gradeinfo = RecordHelper::getLevelInfo($level,$idno);
$grades  = \App\Grade::whereIn('schoolyear',array($gradeinfo['sy'],($gradeinfo['sy']+1)." SUMMER"))->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
$gradeSetting = App\GradesSetting::where('level',$level)->where('schoolyear',$gradeinfo['sy'])->first();

$decimal = 0;
if(count($gradeSetting)> 0){
    $decimal = $gradeSetting->decimal;
}
?>
@if(count($grades)>0)

<table width="100%" style="font-size: 8pt;font-weight: bold">
    <tr>
        <td clospan="2">{{$level}} - Section: <span class="underscore">{{$gradeinfo['section']}}</span></td>
    </tr>
    <tr>
        <td>School:<span class="underscore">{{$gradeinfo['school']}}</span></td>
        <td>SY:<span class="underscore">{{$gradeinfo['sy']}} - {{$gradeinfo['sy']+1}}</span></td>
    </tr>
</table>

<table style="font-size: 7pt;border: 3px solid" width="100%" border="1" cellspacing="0">
<tr style="text-align: center" class="border_bottom border_left border_right border_top">
    <td rowspan="2" width="55%" style="height: 21.6px"><b>LEARNING AREA</b></td>
    <td style="font-size: 7pt" colspan="4">Periodic Rating</td>
    <td rowspan="2" style="font-size: 7pt">Final Rating</td>
    <td rowspan="2" style="font-size: 7pt">Action Taken</td>
</tr>
<tr style="text-align: center" class="border_bottom border_left border_right border_top">
    <td style="font-size: 7pt">1</td>
    <td style="font-size: 7pt">2</td>
    <td style="font-size: 7pt">3</td>
    <td style="font-size: 7pt">4</td>
</tr>
<tr class="border_left border_right">
    <td ><b>CONDUCT/GMRC</b></td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($gradeinfo['sy'],$level,array(3),0,1,$grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($gradeinfo['sy'],$level,array(3),0,2,$grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($gradeinfo['sy'],$level,array(3),0,3,$grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($gradeinfo['sy'],$level,array(3),0,4,$grades)}}</td>
    <td style="font-size: 7pt;text-align: center;">{{GradeComputation::computeQuarterAverage($gradeinfo['sy'],$level,array(3),0,5,$grades)}}</td>
    <td style="font-size: 7pt;text-align: center;"></td>
</tr>
@foreach($grades as $grade)
    @if($grade->subjecttype == 0)
    <tr class="border_left border_right">
        <td>{{strtoupper($grade->subjectname)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
        <td style="font-size: 7pt;text-align: center;">{{number_format(round($grade->final_grade,$decimal),$decimal)}}</td>
        <td style="font-size: 7pt;text-align: center;"></td>
    </tr>
    @endif
@endforeach
</table>
<table style="font-size: 7pt;border: 3px solid" width="100%" border="1" cellspacing="0">
    <?php 
    $dayp = array();
    $daya = array();
    $dayt = array();
    for($i=1; $i < 5 ;$i++){
        if($gradeinfo['sy'] == 2016){
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$gradeinfo['sy'],$i,$level); 
        }else{
            $attendance  = AttendanceController::studentQuarterAttendance($idno,$gradeinfo['sy'],array($i),$level); 
        }
        
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
    <tr class="border_left border_right">
        <td>DAYS TARDY</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayt[0]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayt[1]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayt[2]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayt[3]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
    </tr>
    <tr class="border_left border_right border_bottom">
        <td>DAYS PRESENT</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayp[0]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayp[1]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayp[2]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayp[3]}}</td>
        <td style="font-size: 7pt;text-align: center;">{{$dayp[0]+$dayp[1]+$dayp[2]+$dayp[3]}}</td>
    </tr>
</table>
<table style="font-size: 7pt;" width="100%" cellspacing="0">
    <tr class="border_left border_right" style='font-weight: bolder'>
        <td colspan="3">Eligible for admission to Grade II</td>
        <td colspan="4" style="font-size: 7pt;text-align: center;">General Average<span class="underscore">  {{GradeComputation::computeQuarterAverage($gradeinfo['sy'],$level,array(0),0,5,$grades)}}</span></td>
    </tr>

    <tr class="border_left border_right border_top border_bottom">
        <td colspan="6">
            @foreach($grades as $grade)
                @if($grade->schoolyear == ($gradeinfo['sy']+1)." SUMMER")<br>
                {{$grade->schoolyear}} {{$grade->school}}: {{$grade->subjectname}} = {{round($grade->finalgrade,0)}}
                @else
                <div style="height: 1.5px"></div>
                @endif
            @endforeach
        </td>
    </tr>
</table>

@endif
