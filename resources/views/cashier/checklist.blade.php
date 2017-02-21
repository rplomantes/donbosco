@extends('appcashier')
@section('content')
<div class = "container">
    <div class="col-md-2">
    </div>
    <div class="col-md-7">
<h3>List of checks</h3>
<p>Received by : {{Auth::user()->lastname}}, {{Auth::user()->firstname}} <br>
    Date:<input type="text" id="trandate" value="{{date('Y-m-d')}}" class="form"><a href="#" onclich="showchecks()" class="btn btn-primary">Show List</a>
</p>
<table class="table table-striped"><tr><td>OR No</td><td>Name</td><td>Bank</td><td>Check No</td><td align="right">Amount</td></tr>
    <?php $total = 0;?>
    @foreach($checklists as $checklist)
    @if($checklist->checkamount>0)
    <tr><td>{{$checklist->receiptno}}</td><td>{{$checklist->receivefrom}}</td><td>{{$checklist->bank_branch}}</td><td>{{$checklist->check_number}}</td><td align="right">{{number_format($checklist->checkamount,2)}}</td></tr>
    <?php $total = $total + $checklist->checkamount; ?>
    @endif
    @endforeach
    <tr><td colspan="4">Total</td><td align="right"><strong><?php echo number_format($total, 2);?></strong></td></tr>
</table>
</div>
</div>
<script>
    function showchecks(){
        var showdate = document.getElementById('trandate').value
        document.location= "checklist/" + showdate;
    }
</script>    
@stop
