@extends('app')
@section('content')
<div class="container">
    <div class='col-md-3'>
        <h5> {{$studentname->idno}} - {{$studentname->lastname}}, {{$studentname->firstname}} {{$studentname->middlename}}</h5>
        <ul>
            @foreach($syissued as $sy)
            <li class="btn btn-default form-control"><a href="#" onclick="displaygrade('{{$idno}}','{{$sy->schoolyear}}')">{{$sy->schoolyear}} - {{$sy->schoolyear +1 }}</li>
            @endforeach
        </ul>
    </div>    
    <div  class="col-md-9">
        <div id="displaygrade">
        </div>     
    </div>    
</div>    

<script>
function displaygrade(idno,sy){
    var arrays ={} ;
    arrays["sy"] = sy;
    arrays["idno"]= idno;
    
    $.ajax({
            type: "GET", 
            url: "/displaygrade" ,
            data : arrays,
            success:function(data){
                $('#displaygrade').html(data); 
                }
            }); 
}
</script>
@stop
