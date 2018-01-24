@extends('appcashier')
@section('content')
<div class="container">
    <table class="table table-responsive">
        <tr><td>Student No</td><td><span id="idno">{{$student->idno}}</span></td></tr>
        <tr><td>Name</td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td></tr>
    </table>  
    <div class="col-md-12">
    <h5>Select School Year</h5>
    <select id="schoolyear">
        @foreach($schoolyears as $schoolyear)
        <option value="{{$schoolyear->schoolyear}}">{{$schoolyear->schoolyear}} - {{$schoolyear->schoolyear + 1}}</option>
        @endforeach
    </select>
    <button class="btn btn-primary" onclick="popprevious()">View</button>
    </div>
    <div class="col-md-12">
    <div id = "displaydetails">
    </div>
    </div>    
</div>    

<script>
    function popprevious(){
       
      var idno = document.getElementById('idno').innerHTML;
      var schoolyear = document.getElementById('schoolyear').value;
     
      
       $.ajax({
            type: "GET", 
            url: "/getprevious/" + idno + "/" + schoolyear , 
            success:function(data){
                $('#displaydetails').html(data);  
                 }
            }); 
        
    }
</script>    

@stop