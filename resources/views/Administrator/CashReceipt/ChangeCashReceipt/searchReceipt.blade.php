@extends('app')
@section('content')
<script type='text/javascript'>
  
            <?php if(isset($noOR)){ ?>
                alert("OR with number {{$noOR}} does not exist or has been cancelled.");
            <?php } ?>
</script>

<div class="container">
    <div class="form-group">
        <form method="POST" action="{{route('admin.searchreceiptrefno')}}">    
            {!!csrf_field()!!}
            <label>Ref Number</label>
            <input type="text" class="form form-control" id="or" name="refno" autofocus>
            <p></p>
            <input type="submit" class="btn btn-primary" value="Search">
        </form>
    </div>    
</div>
@stop