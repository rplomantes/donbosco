@extends('app')
@section('content')
<div class="col-md-offset-4 col-md-4">
    <form action="{{url('createRec',$idno)}}" method="POST" class="form-horizontal">
        <div class="form-group">
            <label for='school'>School:</label>
            <input type="text" name="school" class="form-control"/>
        </div>
        <div class='form-group'>
        <label for='level'>Level:</label>
            <select id='level' name='level' class='form-control'>
                <option value="Kindergarten" selected="">Kindergarten</option>
                <option value="Grade 1" >Grade 1</option>
                <option value="Grade 2" >Grade 2</option>
                <option value="Grade 3" >Grade 3</option>
                <option value="Grade 4" >Grade 4</option>
                <option value="Grade 5" >Grade 5</option>
                <option value="Grade 6" >Grade 6</option>
                <option value="Grade 7" >Grade 7</option>
                <option value="Grade 8" >Grade 8</option>
                <option value="Grade 9" >Grade 9</option>
                <option value="Grade 10" >Grade 10</option>
                <option value="Grade 11" >Grade 11</option>
                <option value="Grade 12" >Grade 12</option>
            </select>
        </div>
        <div class='form-group'>
            <label for='year'>School Year:</label>
            <input type="text" name="year" class="form-control"/>
            <span style='font-size: 8px'></span>
        </div>

    </form>
</div>
@stop