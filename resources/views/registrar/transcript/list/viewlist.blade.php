<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
?>
@extends('app')
@section('content')
<style>
    .card{
        border:1px solid grey;
        border-radius: 5px;
    }
    .card-header{
        padding:5px;
        background-color: #e8e6e6;
    }
</style>
<div class='container'>
    <div class='row'>
        <div class="form-group col-md-4">
            <label>Schoolyear</label>
            <select class="form-control" id="schoolyear" name="schoolyear">
                @for ($i = 2016; $i <= $currSY; $i++)
                    <option value="{{$i}}"
                            @if($i==$schoolyear)
                            selected
                            @endif
                            >{{$i}}</option>
                @endfor            
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Level</label>
            <select class="form-control" id="level" name="level">
                <option value='' 
                        @if($grade=="")
                        selected='selected'
                        @endif
                        hidden='hidden'>--Select level--</option>
                @foreach($levels as $getlevel)
                <option value="{{$getlevel->level}}"
                            @if($getlevel->level==$grade)
                            selected='selected'
                            @endif
                            >{{$getlevel->level}}</option>
                @endforeach            
            </select>
        </div>
        <div class='col-md-4'><button class='btn btn-danger' onclick='options()'>Generate</button></div>
    </div>
    
    <div class='row'>
        
        @foreach($students->groupBy('section') as $section)
        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingTwo">
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#{{$section->pluck('id')->last()}}" aria-expanded="false" aria-controls="collapseTwo">
                      {{$section->pluck('id')->last()}}
                    </button>
                  </h5>
                </div>
                <div id="{{$section->pluck('section')->last()}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                  <div class="card-body">
                      <table class='table table-bordered'>
                            <tr>
                              <td>Name</td>
                              <td>View</td>
                            </tr>
                        @foreach($section as $student)
                            <tr>
                                <td>{{Info::get_name($student->idno)}} @if($student->idno == 3)<span color='red'>DROPPED</span>@endif</td>
                                <td><a href="{{route('transcriptshs',array($student->idno))}}" target="_blank">View</a></td>
                            </tr>
                        @endforeach
                      </table>

                  </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<script>
    function options(){
        
        window.location.href = "/transcript/list/"+$('#schoolyear').val()+"/"+$('#level').val();
    }
</script>
@stop