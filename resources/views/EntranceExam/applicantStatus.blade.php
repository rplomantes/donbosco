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
        <div class='col-md-9' id="lvl">
            @foreach($levels as $level)
            <?php $divid = str_replace(' ','',$level->level); ?>
            <div class='levels' id='{{$divid}}'>
                <?php $schedules = EntranceHelper::schedPerLevel($level->level); ?>
                @foreach($schedules as $schedule)
                <?php  $applicants = EntranceHelper::schedApplicant($schedule->id)?>
                <h4>{{$schedule->batch}}</h4>
                <div id="{{$schedule->id}}wrapper">
                    <table class='table table-bordered scheds' id='{{$schedule->id}}'>
                        <tr  style="text-align: center">
                            <td>AN</td>
                            <td>Name</td>
                            <td>Passed</td>
                            <td>Probationary</td>
                            <td>Failed</td>
                        </tr>
                        @foreach($applicants as $applicant)
                        <tr>
                            <td style="text-align: center">{{$applicant->applicant_id}}</td>
                            <td>{{$applicant->user->lastname}}, {{$applicant->user->firstname}} {{$applicant->user->middlename}}</td>
                            <td style="text-align: center"><input class="statuses" style="width: 1.5em;height: 1.5em" type="radio" name="applicantStatus[{{$applicant->id}}]" value="1"></td>
                            <td style="text-align: center"><input class="statuses" style="width: 1.5em;height: 1.5em" type="radio" name="applicantStatus[{{$applicant->id}}]" value="2"></td>
                            <td style="text-align: center"><input class="statuses" style="width: 1.5em;height: 1.5em" type="radio" name="applicantStatus[{{$applicant->id}}]" value="3"></td>
                        </tr>
                        @endforeach
                    </table>
                </div>

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
    $('.statuses').click(function(i,v){
        if($(this).attr('checked',true)){
            var name = $(this).attr('name');
            var key = name.match(/\[(\d+)\]/)[1];
            var arrays =  {};
            
            arrays['id'] = key;
            arrays['status'] = $(this).val();
            $.ajax({
                type:'GET',
                url:'/changestudentstat',
                data:arrays,
                success:function(){
                    
                }
            });
        }
    });
    function getList(level){
        $(".levels").css('display','none');
        $("#"+level).css('display','block');
    }    
</script>
@stop