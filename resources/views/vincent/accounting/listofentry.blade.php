@extends('appaccounting')
@section('content')
<div class="container_fluid">
    <div class="col-md-12 form form-group">
        <div class="col-md-2">
            <label for="entrydate">Journal Entry Date</label>
            <input type="text" name="entrydate" id="entrydate" value="{{date('Y-m-d',strtotime(Carbon\Carbon::now()))}}" class="form form-control">    
        </div>    
    </div>
    <div class="col-md-12">
        <table class="table table-stripped table-bordered">
            <thead>
            <tr><th>Ref Number</th><th>Entry Date</th><th>Particular</th><th>Amount</th><th>View</th><th>Encoded By</th></tr>
            </thead>
            <tbody>
            <div id="entrylist">
                <?php
                $remarks = \App\AccountingRemark::where('trandate',date('Y-m-d',strtotime(Carbon\Carbon::now())))->get();
         
                ?>
                @foreach($remarks as $remark)
                    <tr><td>{{$remark->refno}}</td><td>{{$remark->trandate}}</td><td>{{$remark->remarks}}</td><td align="right">{{number_format($remark->amount,2)}}</td><td><a href="{{url('/accountingview',$remark->refno)}}">View</a></td><td>{{$remark->posted_by}}</td></tr>
                @endforeach
            </div>  
            </tbody>
        </table>    
    </div>    
</div>
<script>
    
</script>    
@stop

