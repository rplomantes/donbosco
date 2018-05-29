<?php
use App\Http\Controllers\Registrar\Assessment\StudentAssessment as Assessment;
?>
@extends('app')
@section('content')
<div class="container-fluid">
    <div class="col-md-5">
        {!!Assessment::info($idno)!!}
        <br>
        {!!Assessment::viewOption($idno)!!}
    </div>
</div>

@stop