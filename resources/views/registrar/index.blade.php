@extends('app')
@section('content')

<div class="container">
    <div class="col-lg-8">
        <input type="text" name="search" id= "search" class="form-control" onkeypress="handle(event)">
        <script>
         function handle(e){
            if(e.keyCode === 13){
            search();
            //alert($("#search").val())
        } else
        {
            return false;
        }
         }
         
         function search(){
             $.ajax({
            type: "GET", 
            url: "/getsearch/" +  $("#search").val(), 
            success:function(data){
                $('#searchbody').html(data);  
                }
            });
         }
         </script>
    </div>   
    <div class="col-lg-4">
        <div class="btn btn-primary" onclick = "search()">Search</div> <a class="btn btn-warning" href="{{url('/registrar/show')}}">Add New Enrollee</a>
    </div>
    <div class="col-lg-12">
  <div id="searchbody">   
            <table class="table table-striped"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>Assessment</th><th>Student Info</th><th>Student Grade</th></tr>        
            </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr><td>{{$student->idno}}</td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}
                    {{$student->extensionname}}</td><td>{{$student->gender}}</td><td><a href = "{{url('/registrar/evaluate',$student->idno)}}">Assess</a></td><td><a href = "{{url('/studentinfokto12',$student->idno)}}" target="_blank">View Info</a></td><td><a href="{{url('seegrade',$student->idno)}}">View Grade</a></td></tr>
            @endforeach
            </tbody>
            </table>
                 </div>
  </div>
</div>



@stop