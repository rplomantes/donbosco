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
    <h3>Promotion Deliberation</h3>
    <div class='col-md-4'>
        <div class="form-group" id="sy_group">
            <label>For Schoolyear</label>
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
        <div class="form-group" id="level_group">
            <label>Level</label>
            <select class="form-control" id="level" name="level" onchange="getSections()">
                <option selected="selected" hidden="hidden">--Select--</option>
                @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="section_group">
            
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
    
    function getSections(){
        var arrays = {};
        
        arrays['level']= $('#level').val();
        arrays['sy']= '{{$sy-1}}';
        arrays['course']= 'null';
        $.ajax({
            type:"GET",
            url: "/getlevelsections/1/viewreport",
            data: arrays,
            success:function(data){
                $('#section_group').html(data);
                viewreport()
            }
        });
        
        
    }
    
    function viewreport(){
        
        var arrays = {};
        
        arrays['level']= $('#level').val();
        arrays['sy']= '{{$sy-1}}';
        arrays['section']= $('#section').val();
        
        $.ajax({
            type:"GET",
            url: "/viewpromotion", 
            data:arrays,
            success:function(data){
                $('#report').html(data);
                document.getElementById("control").style.visibility = "visible";
                viewfinalize(level);                
            }
        });
        

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
        window.open("{{url('/editpromotion')}}/{{$sy}}/"+$('#level').val()+"/"+$('#section').val(), '_blank');
    }
</script>
@stop