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
             <tr><td colspan="2"><hr /></td></tr>  
             <tr><td>Bank</td><td>{{$encashment->bank_branch}}</td></tr> 
             <tr><td>Check Number</td><td>{{$encashment->check_number}}</td></tr>  
             <tr><td>Amount</td><td>{{$encashment->amount}}</td></tr>  
                     
         </table>
    </div>    
    
</div>    



@stop
