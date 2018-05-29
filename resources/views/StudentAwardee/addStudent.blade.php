@extends('app')
@section('content')
<div class="container">
    <div class='col-md-6'>
        <h3><small>FOR SCHOOLYEAR</small> {{$enrollmentYear}} - {{$enrollmentYear+1}}</h3>
        <form method="POST" action="{{route('savestudent')}}">
            {!! csrf_field() !!} 
            <div class="form-group">
                <label>Idno</label>
                <input type="text" class="form-control" id="idno" name="idno">
            </div>
            <div class="form-group">
                <label>Level</label>
                <select class="form-control" id="level" name="level">
                    <option value="Grade 7">Grade 7</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                </select>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" id="amount" name="amount">
            </div>
            <button type='submit' class='btn btn-danger'>Save</button>
        </form>
    </div>
</div>
@stop