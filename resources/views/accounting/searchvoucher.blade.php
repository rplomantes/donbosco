@extends('appaccounting')
@section('content')
<div class="container">
    <div class="form-group">
        <form method="POST" action="{{url('/searchvoucher')}}">    
            {!!csrf_field()!!}
            <label>Voucher Number</label>
            <input type="text" class="form form-control" id="or" name="voucher">
            <p></p>
            <input type="submit" class="btn btn-primary" value="Search">
        </form>
    </div>    
</div>

@stop
