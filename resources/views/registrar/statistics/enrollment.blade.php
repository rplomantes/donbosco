@extends('app')
@section('content')
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
        <td></td>
        <td></td>
    </tr>
        @foreach($level->ctrLevelStrands->slice(1,$level->ctrLevelStrands->count()) as $course_strand)
        <?php
        $students = $enrollees->where('level',$level->level,false)->where('strand',$course_strand->course_strand    ,false);
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
            <td></td>
            <td></td>
        </tr>
        @endforeach
    @else
        <?php
        $students = $enrollees->where('level',$level->level,false);
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
        <td></td>
        <td></td>
    </tr>
    @endif
    @endforeach
</table>
@stop