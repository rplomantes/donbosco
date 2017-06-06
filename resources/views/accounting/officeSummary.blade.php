<?php
use App\Http\Controllers\Accounting\OfficeSumController;

$currAccount = 0;
$total = 0;
?>
@extends('appaccounting')
@section('content')
<div class='container'>
<h4 style="text-align: left;">DEPARTMENTAL
    @if($acctcode == 4)
    INCOME
    @else
    EXPENSE
    @endif
    SUBSIDIARY LEDGER - {{$dept}}
</h4>
    <div class="col-md-3">
    <div class class="form form-group">
    <label>From :</label>
        <input type="text" id="fromtran" class="form-control" value="{{$fromdate}}">
    </div>   
    </div>    
    <div class="col-md-3">
    <div class="form form-group">
        <label>To :</label>
        <input type="text" id="totran"  value="{{$todate}}" class="form-control">
    </div>
    </div>

</div>
<div class="container">
    <div class="col-md-3">
    <div class="form form-group">
        <label>Department :</label>
        <select id='department' class="form-control">
            <option disabled hidden>-- Select --</option>
            @foreach($departments as $department)
            <option value='{{$department->main_department}}'
                    @if($department->main_department == $dept)
                    selected="selected"
                    @endif
                    >{{$department->main_department}}</option>
            @endforeach
        </select>
    </div>
    </div>
    <div class="col-md-3">
    <div class="form form-group">
        <br>    
        <button onclick="showtran()" class="btn btn-primary form-control">View Report</button>
    </div>    
    </div>
</div>
<div class="container" style="overflow-x: scroll" >
    <table class="table table-stripped" style="width:200%;max-width: 120%">
        <tr>
            <td>Account Title</td>
            <td>Total Amount</td>
            @foreach($offices as $office)
            <td>{{$office->sub_department}}</td>
            @endforeach
        </tr>
        
        @foreach($coas as $coa)
            
            <?php
            $acctTotal = OfficeSumController::accounttotal($accounts,$coa->acctcode,$acctcode);
            $total = $total + $acctTotal;
            ?>
            @if(OfficeSumController::showAcct($accounts,$offices,$coa->acctcode,$acctcode))
                <tr>
                    <td>{{$coa->accountname}}</td>

                    <td>{{number_format($acctTotal,2,' .',',')}}</td>
            
                    @foreach($offices as $office)
                    <td>
                        {{OfficeSumController::accountdepttotal($accounts,$office->sub_department,$coa->acctcode,$acctcode)}}
                    </td>
                    @endforeach            
                </tr>
            @endif
        @endforeach
        
        <tr>
            <td>Grand Total</td>
            <td>{{number_format($total,2,' .',',')}}</td>
            @foreach($offices as $office)
            <td>
                {{OfficeSumController::deptTotal($accounts,$office->sub_department,$acctcode)}}
            </td>
            @endforeach
        </tr>
    </table>
</div>
<div class="container">
    <a href="{{url('printdepartmentalsummary',array($fromdate,$todate,$dept,$acctcode))}}" class="col-md-12 btn btn-primary">Print</a>
</div>
<script>
function showtran(){
    var fromtran = document.getElementById('fromtran').value
    var totran = document.getElementById('totran').value
    var dept = document.getElementById('department').value
    document.location="/departmentalsummary/"+ fromtran + "/" + totran + "/" + dept + "/" + {{$acctcode}}
}
</script>
@stop