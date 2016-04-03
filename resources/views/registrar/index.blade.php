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
        <div class="btn btn-primary" onclick = "search()">Search</div> <a class="btn btn-primary" href="{{url('/registrar/show')}}">New</a>
    </div>
    <div class="col-lg-12">
  <div id="searchbody">   
            <table class="table table-striped"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>View</th></tr>        
            </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr><td>{{$student->idno}}</td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlenamename}}
                    {{$student->extensionname}}</td><td>{{$student->gender}}</td><td><a href = "{{url('/registrar/evaluate',$student->idno)}}">view</a></td></tr>
            @endforeach
            </tbody>
            </table>
                 </div>
  </div>
</div>



@stop