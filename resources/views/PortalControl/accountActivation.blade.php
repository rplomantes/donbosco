<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

?>
@extends('app')
@section('content')
<div class='container'>
    <div class='col-md-6'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                {{Info::get_name($idno)}}
            </div>
            <div class='panel-body row'>
                <div class='form-group'>
                    <label for="idno" class="col-sm-4 col-form-label">Idno</label>
                    <div class="col-sm-8">
                      <input type="text" readonly class="form-control" id="idno" value="{{$idno}}">
                    </div>
                </div>
            </div>
            <div class='panel-body row'>
                <div class='form-group'>
                    <label for="email" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email" value="{{$email}}"><sup>Optional</sup>
                    </div>
                </div>
            </div>
            <div class='panel-body row'>
                <div class='form-group'>
                    <label for="pass" class="col-sm-4 col-form-label">Password</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control" id="pass" value="{{$password}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#email').bind('input', function (){
    var datum = {};
    datum['id'] = '{{$idno}}';
    datum['email'] = $(this).val();   
    
    $.ajax({
        type:"GET",
        url:"{{route('updateportalmail')}}",
        data:datum,
        error:function(){
                alert('Opps! Something went wrong.');
        }
    });
})
</script>
@stop