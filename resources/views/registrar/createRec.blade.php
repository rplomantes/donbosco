@extends('app')
@section('content')
  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
  <script>
   $( function() {
    var schools = [<?php echo '"'.implode('","', $schools).'"' ?>];
    $( "#school" ).autocomplete({
      source: schools
    });
    });
  </script>
<div class="col-md-offset-4 col-md-4">
    <form action="{{url('createrec')}}" method="POST" class="form-horizontal">
        {!! csrf_field() !!} 
        <input type="hidden" id="idno" name="idno" value="{{$idno}}"/>
        <div class="form-group">
            <label for='school'>School:</label>
            <input type="text" id="school" name="school" class="form-control"/>
        </div>
        <div class='form-group'>
            <label for='schoolyear'>School Year:</label>
            <input type="text" name="schoolyear" class="form-control"/>
            <span style='font-size: 8px'></span>
        </div>
        <div class='form-group'>
        <label for='level'>Level:</label>
            <select id='level' name='level' class='form-control'>
                <option value="Kindergarten" selected="">Kindergarten</option>
                <option value="Grade 1">Grade 1</option>
                <option value="Grade 2">Grade 2</option>
                <option value="Grade 3">Grade 3</option>
                <option value="Grade 4">Grade 4</option>
                <option value="Grade 5">Grade 5</option>
                <option value="Grade 6">Grade 6</option>
                <option value="Grade 7">Grade 7</option>
                <option value="Grade 8">Grade 8</option>
                <option value="Grade 9">Grade 9</option>
                <option value="Grade 10">Grade 10</option>
                <option value="Grade 11">Grade 11</option>
                <option value="Grade 12">Grade 12</option>
            </select>
        </div>
        
        <div class='form-group'>
            <label for='grade'>Final Grade:</label>
            <input type="text" name="grade" class="form-control"/>
            <span style='font-size: 8px'></span>
        </div>
        <div class='form-group'>
            <label for='dayp'>Days present:</label>
            <input type="text" name="dayp" class="form-control"/>
            <span style='font-size: 8px'></span>
        </div>
        
        <button class="col-md-12 btn btn-danger">Save</button>

    </form>
</div>
@stop