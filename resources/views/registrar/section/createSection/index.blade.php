<?php
use App\Http\Controllers\Registrar\Section\SectionController;
?>

@extends('app')
@section('content')
<div class="container-fluid">
    <div class='row col-md-12'>
        <h3>Section Control <small>for 2018 - 2019</small></h3>
    </div>
    <div class='row'>
        <div class='col-md-3'>
            {!!SectionController::renderForm()!!}
            @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul type='disc'>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <br>
            @include('registrar.section.createSection.upload')
            <a class='col-md-12 btn btn-info' href=''>Section Students</a>
        </div>
        <div class='col-md-8'>
            {!!SectionController::renderSectionList()!!}
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    </div>
    <!--End Modal-->
</div>
@stop