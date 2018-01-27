@extends('app')
@section('content')
<div class='container'>
    <div class='col-md-3'>
        <form class='form-horizontal' method='POST' action="{{url('uploadconductecr')}}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class='form-group'>
                <label class='control-label' for='level'>Level</label>
                <select class='form-control' id='level' name='level'>
                    <option value='' hidden='hidden'> --Select Level---</option>
                    @foreach($levels as $level)
                    <option value='{{$level->level}}'>{{$level->level}}</option>
                    @endforeach
                </select>
            </div>                
            <div class="form form-group">
                <input type="file" name="import_file" class="btn btn-primary"/>
            </div>
            
            <div class="form form-group">
                <button class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
    <div class="col-md-9">
        @foreach($sections->groupBy('level') as $reportlevels)
        <div class="col-md-4">
            <div class='panel panel-default'>
                <div class='panel-heading'>{{$reportlevels->pluck('level')->last()}}</div>
                <div class='panel-body'>
                    <table class='table'>
                        @foreach($reportlevels as $sections)
                        <tr>
                            <td class='label-success'><big class='label'>{{$sections->section}}</big></td>
                        </tr>
                        @endforeach   
                    </table>           
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@stop