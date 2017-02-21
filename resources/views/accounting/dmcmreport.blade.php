@extends('appaccounting')
@section('content')
<div class="container">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
<table class="table table-striped"><tr><td>Issued To</td><td>Amount</td><td align="center"> Debit Entry</td><td>Status</td><td>View</td></tr>
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


<tr><td>{{$collection->lastname}}, {{$collection->firstname}}</td><td align="right">{{number_format($collection->amount,2)}}</td><td>{{$collection->acctcode}}</td><td><?php echo $remarks;?></td><td>
    <a href="{{url('/viewdm',array($collection->refno,$collection->idno))}}">View</a>
    </td></tr>

@endforeach
</table>
    <div class="col-md-4">
        <table class="table">
    <tr><td>Total DM Issued</td><td align="right"><b><?php echo number_format($totalcash,2);?></b></td></tr>  
     <tr><td>Total DM Cancelled</td><td align="right"><b><?php echo number_format($totalcancelled,2);?></b></td></tr>  
</table>
   
    <div class="form form-group">
    <a href = "{{url('printdmcmreport',\Auth::user()->idno)."/".$transactiondate}}" class="btn btn-primary"> Print DM Report</a>
</div>
        </div> 
</div> 

</div>
@stop

