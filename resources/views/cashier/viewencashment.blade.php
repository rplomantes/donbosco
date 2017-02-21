@extends("appcashier")

@section("content")

<div class="container">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
         <h5>Encashment Details</h5>
         <table class="table table-striped">
             <tr><td>Payee</td><td>{{$encashment->payee}}</td></tr>
             <tr><td>On-us</td><td>{{$encashment->whattype}}</td></tr>  
             
             <tr><td>Bank</td><td>{{$encashment->bank_branch}}</td></tr> 
             <tr><td>Check Number</td><td>{{$encashment->check_number}}</td></tr>  
             <tr><td>Amount</td><td>{{$encashment->amount}}</td></tr>  
                     
         </table>
         @if($encashment->isreverse == '0')
         <a href="{{url('reverseencashment',$encashment->refno)}}" class="btn btn-danger">Cancell</a>
         @else
         <a href="{{url('reverseencashment',$encashment->refno)}}" class="btn btn-danger">Restore</a
         @endif
    </div>    
    
</div>    



@stop
