@extends('appcashier')
@section('content')
<style>
    .clickable-row{
        cursor:pointer;
    }
    .clickable-row:hover{
        background-color: #c9de55!important
    }
</style>

<div class="container">
    <div class="col-md-offset-2 col-lg-6">
        <input type="text" name="search" id= "search" class="form-control" onkeypress="handle(event)">      
    </div>   
    <div class="col-lg-4">
        <div class="btn btn-primary" onclick = "search()">Search</div> 
    </div>
    <div class="col-md-offset-2 col-md-8">
        <div id="searchbody">   
            <table class="table table-striped">
                <thead>
                    <tr><th>Student Number</th><th>Student Name</th></tr>        
                </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr class='clickable-row' data-href='{{url('books',$student->idno)}}'>
                <td>{{$student->idno}}</td>
                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</td>
            </tr>
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
        } else
        {
            return false;
        }
         }
         
         function search(){
             $.ajax({
            type: "GET", 
            url: "/getsearchbookstore/" +  $("#search").val(), 
            success:function(data){
                $('#searchbody').html(data);  
                }
            });
         }


    
$("#searchbody").on('click','table tbody .clickable-row', function () {
    window.location = $(this).data('href')
});
         </script>

@stop