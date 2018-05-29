<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

?>
@extends('app')
@section('content')
<style>
    input[type=checkbox]{
        margin-left: 0px!important;
    }

</style>
<div class="container">
    <div class='col-md-3'>
        <h5> {{$studentname->idno}} - {{$studentname->lastname}}, {{$studentname->firstname}} {{$studentname->middlename}}</h5>
        <ul>
            @foreach($syissued as $sy)
            <li class="btn btn-default form-control">
                <a href="#" onclick="displaygrade('{{$idno}}','{{$sy->schoolyear}}')">{{$sy->schoolyear}} - {{$sy->schoolyear +1 }}</a>
                <a class="pull-right" href="{{url('changegrade',array($studentname->idno,$sy->schoolyear))}}">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
            </li>
            @endforeach
        </ul>
        <ul>
            @if($shspermanentRec)
            <li class="btn btn-default form-control"><a onclick="permanentRec()">SHS Permanent Record</a></li>
            @endif
        </ul>
        <ul style="list-style: none;">
            
            <li>
                <button class="btn btn-default form-control" data-toggle="collapse" data-target="#demo">JHS Permanent Record</button>
                <div class="collapse" id="demo">
                    <form method="POST" action="{{ url('/juniorpermanentrec',$studentname->idno) }}">
                        {!! csrf_field() !!}    
                        <div class="checkbox checkbox-circle">
                            <input id="header" name="header" type="checkbox">
                            <label for="header">
                                Header
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade7" name="grade7" type="checkbox">
                            <label for="grade7">
                                Grade 7
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade8" name="grade8" type="checkbox">
                            <label for="grade8">
                                Grade 8
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade9" name="grade9" type="checkbox">
                            <label for="grade9">
                                Grade 9
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade10" name="grade10" type="checkbox">
                            <label for="grade10">
                                Grade 10
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <button value="submit" class="btn bt">Print</button>
                        </div>
                    </form>
                </div>
            </li>

        </ul>
        
        <ul style="list-style: none;">
            
            <li>
                <button class="btn btn-default form-control" data-toggle="collapse" data-target="#elem">Elementary Permanent Record</button>
                <div class="collapse" id="elem">
                    <form method="POST" action="{{ url('/elempermanentrec',$studentname->idno) }}">
                        {!! csrf_field() !!}    
                        <div class="checkbox checkbox-circle">
                            <input id="header" name="header" type="checkbox">
                            <label for="header">
                                Header
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade1" name="grade1" type="checkbox">
                            <label for="grade1">
                                Grade 1
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade2" name="grade2" type="checkbox">
                            <label for="grade2">
                                Grade 2
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade3" name="grade3" type="checkbox">
                            <label for="grade3">
                                Grade 3
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade4" name="grade4" type="checkbox">
                            <label for="grade4">
                                Grade 4
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade5" name="grade5" type="checkbox">
                            <label for="grade5">
                                Grade 5
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <input id="grade6" name="grade6" type="checkbox">
                            <label for="grade6">
                                Grade 6
                            </label>
                        </div>
                        <div class="checkbox checkbox-circle">
                            <button value="submit" class="btn bt">Print</button>
                        </div>
                    </form>
                </div>
            </li>

        </ul>
        
        <ul>
            <li class="btn btn-default form-control"><a href="{{url('createrec',$studentname->idno)}}" >Create Student Record</a></li>
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

function permanentRec(){
    window.open("{{url('permanentrec',array($idno,Info::get_LevelSy($studentname->idno, 'Grade 11')))}}");
    window.open("{{url('permanentrecint',array($idno,Info::get_LevelSy($studentname->idno, 'Grade 11')))}}");
}
</script>
@stop

