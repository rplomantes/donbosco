@extends('app')
@section('content')
<?php use App\Http\Controllers\Registrar\Helper; ?>
<div class='container'>
    <div class='col-md-12'>
        <form class='form-horizontal' method='POST' action="{{url('saveconductupload')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class='col-md-8'>
                <dl class="row">
                  <dt class="col-sm-3">Level</dt>
                  <dd class="col-sm-9">{{$level}}</dd>

                  <dt class="col-sm-3">Section<small>(Detected)</small></dt>
                  <dd class="col-sm-9">{{$section}}</dd>
                </dl>

            </div>
            <div class='col-md-4'>
                <input type="submit" class='btn btn-success'>
            </div>
            <table class='table table-bordered' width='100%'>
                <tr>
                    <td>Idno</td>
                    <td>Name</td>
                    @foreach($conducts as $conduct)
                    <td>{{$conduct->subjectname}}</td>
                    @endforeach
                </tr>
                <tr>
                    @foreach($uploads as $key=>$conducts)
                    <?php $idno = $key;?>
                <tr align='center'>
                    <td>{{$idno}}</td>
                    <td  align='left'>{{Helper::getName($idno)}}</td>
                    @foreach($conducts as $key=>$conduct)
                    <td>{{$conduct}}<input value='{{$conduct}}' type='hidden' name="conduct[{{$idno}}][{{$key}}]"></td>
                    @endforeach
                </tr>
                    @endforeach
            </table>
        </form>
    </div>
</div>

@stop