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
        <div class="col-sm-6" style="padding-left: 0px;padding-right: 20px;">
            <div class='form-group'>
                <label for='entered'>Date Entered:</label>
                <input type="text" name="entered" class="form-control"/>
                <span style='font-size: 8px'></span>
            </div>
        </div>
        <div class="col-sm-6" style="padding-right: 0px;padding-left: 20px;">
            <div class='form-group'>
                <label for='left'>Date Left:</label>
                <input type="text" name="left" class="form-control"/>
                <span style='font-size: 8px'></span>
            </div>
        </div>
        
        <div class='form-group'>
        <label for='level'>Level:</label>
            <select id='level' name='level' class='form-control'>
                <option value="Prep" selected="">Preparatory</option>
                <option value="Kindergarten">Kindergarten</option>
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
        <div class="col-sm-6" style="padding-left: 0px;padding-right: 20px;">
            <div class='form-group'>
                <label for='grade'>Final Grade:</label>
                <input type="text" name="grade" class="form-control"/>
            </div>
        </div>
        <div class="col-sm-6" style="padding-left: 20px;padding-right: 0px;">
            <div class='form-group'>
                <label for='dayp'>Days present:</label>
                <input type="text" name="dayp" class="form-control"/>
            </div>
        </div>
        <div class='form-group'>
        <label for='action'>Action:</label>
            <select id='level' name='action' class='form-control'>
                <option value="PROM" selected="">PROM</option>
                <option value="RET">RET</option>
            </select>
        </div>
        
        <button class="col-md-12 btn btn-danger">Save</button>

    </form>
</div>
@stop