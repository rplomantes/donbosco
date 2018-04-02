@extends('app')
@section('content')
<style>
    #control{
        visibility: hidden;
    }
    #control button{
        border-radius: 0px;
    }
</style>
<div class='container'>
    <div class='col-md-4'>
        <div class="form-group">
            <label>Schoolyear</label>
            <select class="form-control" id="schoolyear" name="schoolyear" onchange="changeSy(this.value)">
                @for ($i = 2016; $i <= $currSY; $i++)
                    <option value="{{$i}}"
                            @if($i==$sy)
                            selected
                            @endif
                            >{{$i}}
                    </option>
                @endfor            
            </select>
        </div>
        <div class="form-group">
            <label>Level</label>
            <select class="form-control" id="level" name="level" onchange="viewreport(this.value)">
                <option selected="selected" hidden="hidden">--Select--</option>
                @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div id='control'>
            <button class="col-md-6 btn btn-success" onclick="editPromo()">EDIT</button>
            <button class="col-md-6 btn btn-danger" onclick="printPromo()">PRINT</button>
            <div id='finalize'>
            </div>
        </div>
    </div>
    <div class='col-md-8' style='max-height:1000px;overflow-y: scroll' id='report'>
        
    </div>
</div>

<script>
    var levels = "";
    function changeSy(schoolyear){
        window.location.href = "/promotion/"+schoolyear;
    }
    
    function changeStatus(){
        var level = $('#level').val();
        
        $.ajax({
            type:"GET",
            url: "/finalizepromotion/{{$sy}}/"+level, 
            success:function(){
                viewfinalize(level);
            }
        });
        
        
    }
    
    function viewreport(level){
        document.getElementById("control").style.visibility = "visible";
        levels = level;
        
        viewfinalize(level);
        $.ajax({
            type:"GET",
            url: "/viewpromotion/{{$sy}}/"+level, 
            success:function(data){
                $('#report').html(data);
            }
        });
        

    }
    
    function viewfinalize(level){
        $.ajax({
            type:"GET",
            url: "/viewfinalizepromotion/{{$sy}}/"+level, 
            success:function(data){
                $('#finalize').html(data);
            }
        });
    }
    
    function printPromo(){
        window.open("{{url('/printpromotion')}}/{{$sy}}/"+levels, '_blank');
    }
    
    function editPromo(){
        window.open("{{url('/editpromotion')}}/{{$sy}}/"+levels, '_blank');
    }
</script>
@stop