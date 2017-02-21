@extends("appcashier")
@section("content")


<div class="container">
    <div class="col-md-5">
    <form method="POST" action="{{url('postviewpenalty')}}">
     {!! csrf_field() !!}
     <div class="form form-group">
    <label>Select Plan to Post Penalty</label>
    <select name="plan" class="form form-control">
        @foreach($duemonths as $duemonth)
        <option value="{{$duemonth->plan}}">{{$duemonth->plan}}</option>
        @endforeach
    </select>    
    </div>
     <div class="form form-group">
         <input type="submit" name="submit" value="View Penalties" class="btn btn-primary">
         
     </div>    
    </form>
</div>
</div>
@stop
