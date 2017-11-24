<?php 
use App\Http\Controllers\Registrar\PermanentRecord; 
use App\Http\Controllers\Registrar\GradeController;
use App\Http\Controllers\Registrar\AttendanceController;
use App\Http\Controllers\Registrar\GradeComputation;
use App\Http\Controllers\Registrar\PermanentRec\Helper as RecordHelper;
use App\Http\Controllers\Registrar\Helper as MainHelper;

$gradeinfo = RecordHelper::getLevelInfo($level,$idno);
$grades  = \App\Grade::whereIn('schoolyear',array($gradeinfo['sy'],($gradeinfo['sy']+1)." SUMMER"))->where('idno',$idno)->where('isdisplaycard',1)->orderBy('sortto','ASC')->get();
$subjects = \App\CtrSubjects::where('level',$level)->where('isdisplaycard',1)->get();
$gradeSetting = App\GradesSetting::where('level',$level)->where('schoolyear',$gradeinfo['sy'])->first();

list($grade,$lvl) = explode(' ',$level);

$decimal = 0;
if(count($gradeSetting)> 0){
    $decimal = $gradeSetting->decimal;
}
?>
@if(count($grades)>0)

<table width="100%" style="font-size: 8pt;font-weight: bold" cellspacing='0'>
    <tr>
        <td colspan="2">GRADE {{mainHelper::integerToRoman($lvl)}}  Section: <span class="underscore">{{$gradeinfo['section']}}</span></td>
    </tr>
    <tr>
        <td  width='70%'>School:<span class="underscore">{{$gradeinfo['school']}}</span></td>
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
    <td style="font-size: 7pt" width='7%'>1</td>
    <td style="font-size: 7pt" width='7%'>2</td>
    <td style="font-size: 7pt" width='7%'>3</td>
    <td style="font-size: 7pt" width='7%'>4</td>
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
<table style="font-size: 7pt;" width="100%" cellspacing="0">
    <tr class="border_left border_right" style='font-weight: bolder'>
        <td colspan="3">Eligible for admission to Grade {{mainHelper::integerToRoman($lvl+1)}}</td>
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
@else
<table width="100%" style="font-size: 8pt;font-weight: bold">
    <tr>
        <td clospan="2">GRADE {{mainHelper::integerToRoman($lvl)}}  Section: <span class="underscore"></span></td>
    </tr>
    <tr>
        <td width='70%'>School:<span class="underscore"></span></td>
        <td>SY:<span class="underscore" style='color: white;border-bottom:1px solid black'>2016 - 2017 </span></td>
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
    <td style="font-size: 7pt" width='7%'>1</td>
    <td style="font-size: 7pt" width='7%'>2</td>
    <td style="font-size: 7pt" width='7%'>3</td>
    <td style="font-size: 7pt" width='7%'>4</td>
</tr>
<tr class="border_left border_right">
    <td ><b>CONDUCT/GMRC</b></td>
    <td style="font-size: 7pt;text-align: center;"> </td>
    <td style="font-size: 7pt;text-align: center;"> </td>
    <td style="font-size: 7pt;text-align: center;"> </td>
    <td style="font-size: 7pt;text-align: center;"> </td>
    <td style="font-size: 7pt;text-align: center;"> </td>
    <td style="font-size: 7pt;text-align: center;"> </td>
</tr>
@foreach($subjects as $grade)
    @if($grade->subjecttype == 0)
    <tr class="border_left border_right">
        <td>{{strtoupper($grade->subjectname)}}</td>
        <td style="font-size: 7pt;text-align: center;"> </td>
        <td style="font-size: 7pt;text-align: center;"> </td>
        <td style="font-size: 7pt;text-align: center;"> </td>
        <td style="font-size: 7pt;text-align: center;"> </td>
        <td style="font-size: 7pt;text-align: center;"> </td>
        <td style="font-size: 7pt;text-align: center;"></td>
    </tr>
    @endif
@endforeach
</table>
<table style="font-size: 7pt;" width="100%" cellspacing="0">
    <tr class="border_left border_right" style='font-weight: bolder'>
        <td colspan="3">Eligible for admission to Grade {{mainHelper::integerToRoman($lvl+1)}}</td>
        <td colspan="4" style="font-size: 7pt;text-align: center;">General Average<span class="underscore">     </span></td>
    </tr>

    <tr class="border_left border_right border_top border_bottom">
        <td colspan="6">
        </td>
    </tr>
</table>
@endif
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



