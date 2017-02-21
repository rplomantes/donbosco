@extends('appaccounting')
@section('content')
<div class="container">
<table class="table table-striped"><tr><td>Receipt No</td><td>Received From</td><td align="center">Cash Amount</td><td align="center"> Check Amount</td><td align="center">Total</td><td>Status</td><td>View</td><td>Posted By</td></tr>
<?php  $totalchecks = 0; $totalcash=0; $totalcheck=0; $totalcancelled=0;?>
@foreach($collections as $collection)
<?php $remarks = "Ok";
if($collection->isreverse == '1'){
    $remarks = "Cancelled";
    $totalcancelled = $totalcancelled + $collection->amount + $collection->checkamount;
} else {
    $totalcheck = $totalcheck + $collection->checkamount;
    $totalcash = $totalcash + $collection->amount;
}
?>


<tr><td>{{$collection->receiptno}}</td><td>{{$collection->lastname}}, {{$collection->firstname}}</td><td align="right">{{number_format($collection->amount,2)}}</td><td align="right">{{number_format($collection->checkamount,2)}}</td><td align="right">{{number_format($collection->checkamount + $collection->amount,2)}}</td><td><?php echo $remarks;?></td><td>
    <a href="{{url('/viewreceipt',array($collection->refno,$collection->idno))}}">View</a>
    </td><td>{{$collection->postedby}}</td></tr>

@endforeach
</table>
    <div class="col-md-4">
        <table class="table">
    <tr><td>Total Cash Collection</td><td align="right"><b><?php echo number_format($totalcash,2);?></b></td></tr>  
    <tr><td>Total Check Collection</td><td align="right"><b><?php echo number_format($totalcheck,2);?></b></td></tr> 
    <tr><td>Total Collection</td><td align="right"><b><?php echo number_format($totalcash+$totalcheck,2);?></b></td></tr> 
    <tr><td>Total Cancelled</td><td align="right"><b><?php echo number_format($totalcancelled,2);?></b></td></tr>  
</table>
   
    <div class="form form-group">
    <a href = "{{url('printcollection',\Auth::user()->idno)."/".$transactiondate}}" class="btn btn-primary"> Print Collection Report</a>
</div>
        </div> 
</div>    
@stop
