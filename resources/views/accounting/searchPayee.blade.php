<?php 
$template = 'appaccounting';
if(in_array(Auth::user()->accesslevel,array(env('USER_ACCOUNTING'),env('USER_ACCOUNTING_HEAD')))){
    $template = 'appaccounting';
}elseif(in_array(Auth::user()->accesslevel,array(env('USER_ADMIN')))){
    $template = 'appadmin';
}
?>
@extends($template)
@section('content')
  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
  <script>
   $( function() {
    var payee = [<?php echo '"'.implode('","', $payees).'"' ?>];
    $( "#payee" ).autocomplete({
      source: payee
    });
    });
    
  </script>
<div class="container">
    <div class="form-group">
        <form method="POST" action="{{url('/searchPayee')}}">    
            {!!csrf_field()!!}
            <label>Payee</label>
            <input type="text" class="form form-control" id="payee" name="payee">
            <p></p>
            <input type="submit" class="btn btn-primary" value="Search">
        </form>
    </div>    
</div>
@stop