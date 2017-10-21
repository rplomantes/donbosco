<?php 
use App\Http\Controllers\EntranceExam\Helper as EntranceHelper;
?>
@extends('app')
@section('content')

<div class="container">
        @foreach($levels as $level)
            <?php
            $shedules = EntranceHelper::schedPerLevel($level->level);
            $id = trim($level->level,'Grade ');
            ?>
        <div id="{{$id}}">
            <h4>{{$level->level}}</h4>
            <table class="table table-bordered">
                <tr>
                    <td><button onclick="add('{{$level->level}}','{{$id}}')">+</button></td>
                    <td>Batch</td>
                    <td>Date</td>
                    <td>Max no. of Examinees</td>
                    <td>Time Start</td>
                    <td>Time End</td>
                </tr>
            @foreach($shedules as $schedule)
                <tr>
                    <td><button onclick="remove('{{$schedule->id}}','{{$id}}')">-</button></td>
                    <td>{{$schedule->batch}}</td>
                    <td><input class='form-control dates' type='text' value="{{$schedule->date}}" name='{{$schedule->id}}'></td>
                    <td><input class='form-control' type="number" value="{{$schedule->max_examinee}}" onkeyup="updateData(this.value,'{{$schedule->id}}',2)"></td>
                    <td><input class='form-control' type="time" value="{{$schedule->time_start}}" onkeyup="updateData(this.value,'{{$schedule->id}}',3)"></td>
                    <td><input class='form-control' type="time" value="{{$schedule->time_end}}" onkeyup="updateData(this.value,'{{$schedule->id}}',4)"></td>
                </tr>

            @endforeach
            </table>
        </div>
        @endforeach
    
</div>

<script>
$(function() {
    $('.dates').datepicker({ format: 'yyyy-m-d'}).on('changeDate', function(ev) {
        var name = $(this).attr('name'); 
        updateData($(this).val(),name,1);
    });
});
    
    function add(level,id){
        
        $.ajax({
          type:"GET",
          url:"/addschedule/"+level,
          success:function(){
              window.location.href = "/createSched";
          }
        });
    }
    
    function remove(entry,id){
        $.ajax({
          type:"GET",
          url:"/removeschedule/"+entry,
          success:function(){
              $("#"+id).load(document.URL +  ' #' + id);
          }
        });
    }
    
    function updateData(data,entry,type){
        var arrays = {};
        arrays['entry'] = entry;
        arrays['data'] = data;
        arrays['type'] = type;
        $.ajax({
          type:"GET",
          url:"/updatesched",
          data:arrays,
          success:function(){
          }
        });
    }
    

</script>
@stop