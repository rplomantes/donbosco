<?php
use App\Http\Controllers\Registrar\Statistics\StatisticsHelper;
?>
<style>
    .cell_projection,.cell_warmBody{
        text-align: center;
        vertical-align: middle!important;
    }
</style>
<h3>Kto12 Enrollment - <small>{{$schoolyear}}</small></h3>
<table class='table table-bordered'>
    <tr>
        <td>Level</td>
        <td>Strand / Shop</td>
        <td>Officially Enrolled</td>
        <td>Projection</td>
        <td>Needed warm bodies</td>
    </tr>
    @foreach($levels as $level)
    @if($level->ctrLevelStrands->count() > 1)
    <tr>
        <td rowspan="{{$level->ctrLevelStrands->count()}}">{{$level->level}}</td>
        <td>{{$level->ctrLevelStrands->first()->course_strand}}</td>
        <?php
        $students = $enrollees->where('level',$level->level,false)->where('strand',$level->ctrLevelStrands->first()->course_strand,false);
        $projection = App\ProjectionEnrollment::get_Projection($schoolyear,$level->level,$level->ctrLevelStrands->first()->course_strand);
        ?>
        <td>
            <table class='table'>
                @foreach($students->sortBy('gender')->groupBy('gender') as $gender=>$studentGroup)
                <tr>
                    <td>{{$gender}}</td>
                    <td align='right'>{{$studentGroup->count()}}</td>
                </tr>
                @endforeach
                <tr style='font-weight: bold'>
                    <td>TOTAL</td>
                    <td align='right'>{{$students->count()}}</td>
                </tr>
            </table>
        </td>
        <td class='cell_projection'>{{$projection['display']}}</td>
        <td class='cell_warmBody'>{!!StatisticsHelper::get_neededRemaining($students->count(), $projection['value'])!!}</td>
    </tr>
        @foreach($level->ctrLevelStrands->slice(1,$level->ctrLevelStrands->count()) as $course_strand)
        <?php
        $students = $enrollees->where('level',$level->level,false)->where('strand',$course_strand->course_strand,false);
        $projection = App\ProjectionEnrollment::get_Projection($schoolyear,$level->level,$course_strand->course_strand);
        ?>
        <tr>
            <td>{{$course_strand->course_strand}}</td>
            <td>
                <table class='table'>
                    @foreach($students->sortBy('gender')->groupBy('gender') as $gender=>$studentGroup)
                    <tr>
                        <td>{{$gender}}</td>
                        <td align='right'>{{$studentGroup->count()}}</td>
                    </tr>
                    @endforeach
                    <tr style='font-weight: bold'>
                        <td>TOTAL</td>
                        <td align='right'>{{$students->count()}}</td>
                    </tr>
                </table>
            </td>
            <td class='cell_projection'>{{$projection['display']}}</td>
            <td class='cell_warmBody'>{!!StatisticsHelper::get_neededRemaining($students->count(), $projection['value'])!!}</td>
        </tr>
        @endforeach
    @else
        <?php
        $students = $enrollees->where('level',$level->level,false);
        $projection = App\ProjectionEnrollment::get_Projection($schoolyear,$level->level);
        ?>
    <tr>
        <td>{{$level->level}}</td>
        <td></td>
        <td>
            <table class='table'>
                @foreach($students->sortBy('gender')->groupBy('gender') as $gender=>$studentGroup)
                <tr>
                    <td>{{$gender}}</td>
                    <td align='right'>{{$studentGroup->count()}}</td>
                </tr>
                @endforeach
                <tr style='font-weight: bold'>
                    <td>TOTAL</td>
                    <td align='right'>{{$students->count()}}</td>
                </tr>
            </table>
        </td>
        <td class='cell_projection'>{{$projection['display']}}</td>
        <td class='cell_warmBody'>{!!StatisticsHelper::get_neededRemaining($students->count(), $projection['value'])!!}</td>
    </tr>
    @endif
    @endforeach
</table>