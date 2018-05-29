@extends('app')
@section('content')

<script src="{{asset('/js/ballon.js')}}"></script>

<div class='container'>
    @foreach($sectionlist->groupBy('level') as $level=>$sections)
    <div class="col-md-3">
        <h3>{{$level}}</h3>
        <table class='table table-striped'>
            @foreach($sections as $section)
            <tr id='{{$section->id}}' class='clickable-row'><td><div>{{$section->section}}</div></td></tr>
            @endforeach
        </table>
    </div>
    @endforeach
</div>

<script>
$(function() {
@foreach($sectionlist as $level)
  
var shown{{$level->id}} = false;
  $('#{{$level->id}}').on("click", function() {
    shown{{$level->id}} ? $(this).hideBalloon() : $(this).showBalloon({ html:true,contents:'<i class="fa fa-3x fa-spinner fa-pulse"></i>',ajax:'{{route("subjectgradestat",array($level->level,$level->section))}}'});
    shown{{$level->id}} = !shown{{$level->id}};
  });
@endforeach
});    

function gettext(){
    
}

function getgrades(){
    @foreach($sectionlist as $level)
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