@extends('appcashier')
@section('content')
<div class="container">
    <div class="col-lg-8">
        <input type="text" name="search" id= "search" class="form-control" onkeypress="handle(event)">      
    </div>   
    <div class="col-lg-4">
        <div class="btn btn-primary" onclick = "search()">Search</div> 
    </div>
    <div class="col-lg-12">
        <div id="searchbody">   
            <table class="table table-striped"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>View</th></tr>        
            </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr><td>{{$student->idno}}</td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}
                    {{$student->extensionname}}</td><td>{{$student->gender}}</td><td><a href = "{{url('/cashier',$student->idno)}}">view</a></td></tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
       $("#search").focus(); 
    });
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
            url: "/getsearchcashier/" +  $("#search").val(), 
            success:function(data){
                $('#searchbody').html(data);  
                }
            });
         }
         </script>

@stop