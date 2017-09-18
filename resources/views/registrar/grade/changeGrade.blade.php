@extends('app')
@section('content')
<div class="container-fluid">
    <div class="col-md-3" >
        <h4>{{$student->lastname}}, {{$student->firstname}} {{substr($student->middlename,0,1)}}.</h4>
        <h5><b>{{$student->idno}}</b></h5>
        <div>Level: {{$level}}</div>
        <div>Section: {{$section}}</div>
        <div style="height:20px;"></div>
        
        <button class="btn btn-default col-md-12" onclick="view('grade')">
            Grades
        </button>
        <button class="btn btn-default col-md-12" onclick="view('conduct')">
            Conduct
        </button>
        <button class="btn btn-default col-md-12" onclick="view('attendance')">
            Attendance
        </button>
    </div>
    <div id="view" class="col-md-9">
        
    </div>
    
</div>

<script>
    function view(subjecttype){
        arrays ={} ;
        arrays['idno']= {{$student->idno}};
        arrays['sy']= '{{$sy}}';

        $.ajax({
               type: "GET", 
               url: "/getGradeForm/"+subjecttype,
               data : arrays,
               success:function(data){
                   
                   $('#view').html(data);
                   
                   },
                   error:function(){
                       $('#view').html("<div class='alert alert-danger' style='text-align: center'><h3>Whoa!!</h3>Something went wrong while retrieving the data. Please report to administrator to fix the problem immediately.</div>");
                   }
               });
    }
</script>
@stop 