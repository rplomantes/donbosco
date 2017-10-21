<?php 
use App\Http\Controllers\EntranceExam\Helper as EntranceHelper;
?>
@extends('app')
@section('content')
<style>
    .levels{
        display: none;
    }
</style>
<div class='container'>
    <div class='col-md-12'>
        <div class='col-md-3'>
            @foreach($levels as $level)
            <?php $id = str_replace(' ','',$level->level); ?>
            <button class='col-md-12 btn btn-default' onclick='getList("{{$id}}")'>{{$level->level}}</button>
            @endforeach
        </div>
        <div class='col-md-9'>
            @foreach($levels as $level)
            <?php $divid = str_replace(' ','',$level->level); ?>
            <div class='levels' id='{{$divid}}'>
                <?php $schedules = EntranceHelper::schedPerLevel($level->level); ?>
                @foreach($schedules as $schedule)
                <?php  $applicants = \App\EntranceApplicant::where('schedule_id',$schedule->id)->get(); ?>
                <h4>{{$schedule->batch}}</h4>
                <table class='table table-bordered scheds' id='{{$schedule->id}}'>
                    <tr>
                        <td>AN</td>
                        <td>Name</td>
                    </tr>
                    @foreach($applicants as $applicant)
                    <tr>
                        <td>{{$applicant->applicant_id}}</td>
                        <td>{{$applicant->user->lastname}}, {{$applicant->user->firstname}} {{$applicant->user->middlename}}</td>
                    </tr>
                    @endforeach
                </table>
                @endforeach
                @if(count($schedules)==0)
                <h4>No schedule set!!</h4>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>

    window.setInterval(function(){
        $('.scheds').each(function(){
            var sched = $(this).attr('id');
            var rows = $("#"+sched+" tr").length;
            
            var arrays = {}
            arrays['rows'] = rows;
            arrays['sched'] = sched;
            $.ajax({
                type:"GET",
                url:"/updateapplicantlist",
                data:arrays,
                success:function(data){
                    //$("#"+sched).append(data);
                    $("#"+sched).load(document.URL +  ' #' + sched);
                    rows = 0;
                } 
            });
            
        });
    }, 5000);
    
    function getList(level){
        $(".levels").css('display','none');
        $("#"+level).css('display','block');
    }    
</script>
@stop