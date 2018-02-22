<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
$sy = App\CtrSchoolYear::first()->schoolyear;
?>
<style>
    .row{
        height:auto;
        display: flex;
        flex-flow:row;
    }
    .center{
        position: absolute;
        top: 50%;
    }

</style>
@foreach($hits as $hit)
<div class="panel">
    <div class="panel-body ">
        <div class=" col col-md-9">
            <div><b><a href="{{url('studentinfokto12',$hit->idno)}}">{{Info::get_name($hit->idno)}}</a></b></div>
            @if(Info::get_status($hit->idno,$sy) == 2)
            <div>{{Info::get_level($hit->idno,$sy)}}</div>
            <div>{{Info::get_section($hit->idno,$sy)}}</div>
            @else
            Registered
            @endif
        </div>
    </div>
</div>
@endforeach