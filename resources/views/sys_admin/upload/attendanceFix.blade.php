@extends('app')
@section('content')
<div class='container'>
    <div class='col-md-3'>
        <form class='form-horizontal' method='POST' action="{{url('submitAttFix')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form form-group">
                <input type="file" name="import_file" class="btn btn-primary"/>
            </div>
            
            <div class="form form-group">
                <button class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
    <div class="col-md-9">
        @foreach($levels as $level)
        
        <div class='col-md-4'>
            <div>{{$level->level}}</div>
            <?php $submitted = \App\GradesStatus::where('level',$level->level)->where('quarter',$quarter)->where('schoolyear',$schoolyear)->where('status',2)->get();?>
            <ul>
            @foreach($submitted as $rec)
            <li>{{$rec->section}}</li>
            @endforeach
            </ul>
        </div>
        @endforeach
    </div>
</div>

@stop