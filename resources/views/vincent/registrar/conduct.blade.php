@extends('app')
@section('content')
<div class="container">
    <div class="col-md-6">
        <div class="form-group">
            <label for="level">Select Grade Level</label>
            <select id="level" onchange="getSection()" class="form-control">
                <option>Select Level</option>
                @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>    
        </div>
        <div class="form-group">
            <div id="displaysection">
            </div>    
        </div>
        <div class="form-group">
            <div id="displayqtr" style="visibility:hidden">
                <label>Select Quarter Period</label>
                <select id ="qtr" class="form-control">
                    <option value="1" selected>First Quarter Period</option>
                    <option value="2">Second Quarter Period</option>
                    <option value="3">Third Quarter Period</option>
                    <option value="4">Fourth Quarter Period</option>
                </select>    
            </div>    
            </div>
        <div class="form-group">
            <div id="displaybtn">
                <button class="btn btn-primary" onclick="goto()" style="visibility:hidden" id="link">Display Attendance</button>
            </div>
        </div>
        </div>    
    </div>    
</div>    
<script>
function getSection(){
 $.ajax({
            type: "GET", 
            url: "/getsection1/" + $('#level').val() , 
            success:function(data){
                $("#displaysection").html(data);
            }
});   
}

function showqtr(){
    document.getElementById('displayqtr').style.visibility="visible"
    document.getElementById('link').style.visibility="visible"
    }
function goto(){
    var level = document.getElementById('level').value
    var section = document.getElementById('section').value
    var quarter = document.getElementById('qtr').value
    window.open("/sheetaconduct/" + level + "/" + section +"/"+ quarter, '_blank');
}
</script>
@stop

