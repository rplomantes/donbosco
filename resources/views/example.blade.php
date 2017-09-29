@extends('appmisc')
@section('content')
<div class="container">
<h3>Production Income</h3>
<div style="width:30%;">
    {!!$chart->render()!!}
</div>
</div>
@stop