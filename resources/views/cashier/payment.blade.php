@extends("appcashier")
@section("content")

<div class="container-fluid"> 
    <div class="col-md-3">
        <a href="{{url('/cashier',$idno)}}" class="btn btn-primary form-control">Back</a>
    </div>
    <div class="col-md-6" style="background-color: #C6C6FF">    
        <table class="table table-responsive">
        </tr><td colspan="3"><h5>Main Account</h5></td></tr>
        <tr align="center"><td>Total Balance</td><td>Due Today</td><td>Amount To Be Paid</td></tr>
        <tr><td></td><td align="right"><span class="form-control">{{number_format($totaldue,2)}}</span></td><td><input style="text-align:right" class="form-control" type="text" name="duepayment" value="{{$totaldue}}"></td></tr> 
        @if(count($previous) > 0 )
        <tr><td colspan="3"><h5>Previous Balance</h5></td></tr>
        <tr align="center"><td>School Year</td><td>Amount</td><td>Amount To Be Paid</td></tr>
        @foreach($previous as $previou)
        <tr><td>{{$previou->schoolyear}} - {{$previou->schoolyear+1}}</td><td align="right"><span class="form-control">{{number_format($previou->balance,2)}}</span></td><td><input style="text-align:right" class="form-control" type="text" name="previous['{{$previou->schoolyear}}']" value="{{$previou->balance}}"></td></tr>
        @endforeach
        @endif
        <tr><td colspan="3"><h5>Other Payment</h5></td></tr>
        <tr align="center"><td>Description</td><td>Amount</td><td>Amount To Be Paid</td></tr>
        <tr align=><td>Add</td><td>Penalty</td><td align="right"><span class="form-control">{{number_format($totalpenalty,2)}}</span></td></tr>
        <tr align=><td>Less</td><td>Reservation</td><td align="right"><span class="form-control">{{number_format($reservation,2)}}</span></td></tr>
        <tr align=><td></td><td>Discount</td><td>Amount To Be Paid</td></tr>
        </table>
    </div>
    <div class="col-md-3">
    </div>    
</div>
@stop
