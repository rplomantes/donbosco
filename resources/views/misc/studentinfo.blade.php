@extends('appmisc')
@section('content')
    <div class="conatainer">
        <div class="col-md-offset-3 col-md-6">
            <h4>{{$users->lastname}}, {{$users->firstname}} {{$users->middlename}} {{$users->extensionname}}  -  {{{{$users->idno}}}}</h4>
            <br>
            <>
        </div>
    </div>
@stop