@extends('app')
@section('content')
<div class='container'>
    <div id='grades'>
        
    </div>
</div>

<script>
$(document).ready(function(){
    getgrades();
})    

function getgrades(){
    @foreach($levels as $level)
        $.ajax({
               type: "GET", 
               url: "submittedReport_grade/{{$level->level}}",
               data : arrays,
               
                error:function(data){
                    $('#grades').html(data)
                   }
               });
    @endforeach
}
</script>
@stop