@extends('appaccounting')
@section('content')
<div class="container">
    <form class="form-horizontal" method="POST" action="{{route('closeFiscal')}}">
        {!!csrf_field()!!}
        <button type='submit' class='btn btn-danger'>Alert</button>
    </form>
</div>
@stop 