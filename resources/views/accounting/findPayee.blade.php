@extends('appcashier')
@section('content')
<script type='text/javascript'>
  
            <?php if(isset($noOR)){ ?>
                alert("OR with number {{$noOR}} does not exist or has been cancelled.");
            <?php } ?>
</script>
<div class="container">
    <div class="form-group">
        <form method="POST" action="{{url('/searchpayee')}}">    
            {!!csrf_field()!!}
            <label>OR Number</label>
            <input type="text" class="form form-control" id="or" name="payee" autofocus>
            <p></p>
            <input type="submit" class="btn btn-primary" value="Search">
        </form>
    </div>    
</div>

@stop
