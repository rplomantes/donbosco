@extends('appaccounting')
@section('content')
<div class="container">
    <div class="col-md-6">
    <form method="POST" action="{{url('subsidiary')}}">
          {!! csrf_field() !!} 
     
    <div class='form-group'>
        <h5>Date Range :</h5>
        <label> From : </label>
        <input type = 'text' value="{{Date('Y-m-d')}}" id="from" name="from">
        <label> To : </label>
        <input type = "text" value="{{Date('Y-m-d')}}" id="from" name="to">
    </div>    
   <div class='form-group'>
        <input  type="checkbox" name="all" value="1"  id="all"> <label> All</label> 
    </div>  
    <div class="form-group">
        <h5>Account Title :</h5>
        <select name="accountname" id="accountname" class="form-control">
            @foreach($acctcodes as $acctcode)
            <option value="{{$acctcode->acctcode}}">{{$acctcode->acctcode}}</option>
            @endforeach
        </select>    
    </div>    
     <div class="form-group">
         <input type="submit" name="submit" id="submit" class="btn btn-primary">    
     </div>   
    </form>     
        </div>
</div>    
@stop

