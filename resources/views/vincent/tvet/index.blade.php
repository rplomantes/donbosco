@extends('app')
@section('content')

<div class="container">
    <div class="col-lg-8">
        <input type="text" name="search" id= "search" class="form-control" onkeypress="handle(event)" autofocus="autofocus">
        <script>
         function handle(e){
            if(e.keyCode === 13){
            search();
            //alert($("#search").val());
        } else
        {
            return false;
        }
         }
         
         function search(){
//alert($("#search").val())
             $.ajax({
            type: "GET", 
            url: "/getsearchtvet/" +  $("#search").val(), 
            success:function(data){
                $('#searchbody').html(data);  
                }
            });
         }
         </script>
    </div>   
    <divclass="col-lg-4">
        <div class="btn btn-primary" onclick = "search()">Search</div>
    </div>
    <div class="container">
  <div id="searchbody">   
            <table class="table table-striped"><thead>
                    <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>Plan</th></tr>        
            </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr><td>{{$student->idno}}</td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}
                    {{$student->extensionname}}</td><td>{{$student->gender}}</td><td>@if($student->stat == 2)<a href="{{url('planset',$student->idno)}}">view</a>@endif</td></tr>
            @endforeach
            </tbody>
            </table>
                 </div>
  </div>
</div>



@stop