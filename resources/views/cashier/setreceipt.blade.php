@extends("appcashier")

@section("content")

<div class="container_fluid">
   
   <div class="col-md-3">
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
        <a href="{{url('/')}}" class="btn btn-primary">Back</a>
        </div>
        <div class="form-group">
         <form onsubmit="return confirm('Continue to set receipt ?');" class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/setreceipt') }}">
             {!! csrf_field() !!} 
             <input type="hidden" name="id" value="{{$id}}">
             <div class="form-group">
             <input type="text" name="receiptno" value="{{$receiptno}}" class="form form-control">
             </div>
              <div class="form-group">
             <input type="submit" value="Set Receipt Number" class="btn btn-danger form-control">
             </div>
         </form>    
        </div>
    </div>    
     <div class="col-md-3">
    </div>
</div>
@stop

