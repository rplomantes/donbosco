<?php

$disbursements = DB::Select(" Select transactiondate, voucherno, payee,  voucheramount, isreverse, sum(advances_employee) as advances, sum(cost_of_goods) as cost, sum(instructional_materials) as instructional, "
        . "sum(salaries_allowances) as salaries,sum(personnel_dev) as dev,sum(other_emp_benefit) as benefit, sum(office_supplies) as supplies,"
        . "sum(travel_expenses) as travel, sum(sundry_debit) as sundrydebit,sum(sundry_credit) as sundrycredit from rpt_disbursement_books where totalindic = '0' "
        . "group by transactiondate, voucherno, payee, isreverse");

$totalmonths = DB::Select("Select sum(advances_employee)advances, sum(cost_of_goods)as cost, sum(instructional_materials) as instructional, "
        . "sum(salaries_allowances) as salaries,sum(personnel_dev) as dev,sum(other_emp_benefit) as benefit, sum(office_supplies) as supplies,"
        . "sum(travel_expenses) as travel, sum(sundry_debit)  as sundrydebit,sum(sundry_credit) as sundrycredit from rpt_disbursement_books where totalindic = '1' and transactiondate <> '$trandate' and isreverse = '0'");

$tvoucher=0;$tadvances=0;$tcost=0;$tinstructional=0;
$tsalaries=0;$tdev=0;$tbenefit=0;$tsupplies=0;
$ttravel=0;$tdebit=0;$tcredit=0;

$stvoucher=0;$stadvances=0;$stcost=0;$stinstructional=0;
$stsalaries=0;$stdev=0;$stbenefit=0;$stsupplies=0;
$sttravel=0;$stdebit=0;$stcredit=0;

$nooflines=20;

?>
@extends('appaccounting')
@section('content')
<div class="container">
<div class="col-md-12">    
<h3>CASH DISBURSEMENT BOOK</h3>
    <div class="col-md-2">
        <label>From</label>
        <input type="text" class="form-control" value="{{substr_replace($trandate, "01", 8)}}" readonly>
    </div>
    <div class="col-md-2">
        <label>To</label>
        <input type="text" class="form-control" id="trandate" value="{{$trandate}}">
    </div> 
    <div class="col-md-2">
        <label></label>
        <button id="processbtn" class="btn btn-primary form-control">View</button>
    </div>     
</div>  
<div class="col-md-12"> 
    <div class="col-md-12">
        <hr>
    </div>    
        <table class="table table-bordered table-striped">
        <tr><th>Voucher No</th><th>Payee</th><th>Voucher Amount</th><th>Advance To Employee</th><th>Cost of Sales</th>
        <th>Instructional  Materials</th><th>Salaries / Allowances</th><th>Personnel <br>Development</th>
        <th>Other Employee Benefit</th><th>Office Supplies</th><th>Travel Expenses</th>
        <th>Sundries Debit</th><th>Sundies Credit</th><th>Status</th></tr>
@foreach($totalmonths as $totalmonth)
<tr><td colspan="2">Forwarded Balance :</td><td align="right"></td>
            <td align="right"> {{number_format($totalmonth->advances,2)}}</td><td align="right">{{number_format($totalmonth->cost,2)}}</td>
        <td align="right">{{number_format($totalmonth->instructional,2)}}</td><td align="right">{{number_format($totalmonth->salaries,2)}}</td><td align="right">{{number_format($totalmonth->dev,2)}}</td>
        <td align="right">{{number_format($totalmonth->benefit,2)}}</td><td align="right">{{number_format($totalmonth->supplies,2)}}</td><td align="right">{{number_format($totalmonth->travel,2)}}</td>
        <td align="right">{{number_format($totalmonth->sundrydebit,2)}}</td><td align="right">{{number_format($totalmonth->sundrycredit,2)}}</td><td></td></tr>
@endforeach
@foreach($disbursements as $disbursement)
        <tr><td>{{$disbursement->voucherno}}</td><td>{{$disbursement->payee}}</td><td align="right">{{number_format($disbursement->voucheramount,2)}}</td>
            <td align="right"> {{number_format($disbursement->advances,2)}}</td><td align="right">{{number_format($disbursement->cost,2)}}</td>
        <td align="right">{{number_format($disbursement->instructional,2)}}</td><td align="right">{{number_format($disbursement->salaries,2)}}</td><td align="right">{{number_format($disbursement->dev,2)}}</td>
        <td align="right">{{number_format($disbursement->benefit,2)}}</td><td align="right">{{number_format($disbursement->supplies,2)}}</td><td align="right">{{number_format($disbursement->travel,2)}}</td>
        <td align="right">{{number_format($disbursement->sundrydebit,2)}}</td><td align="right">{{number_format($disbursement->sundrycredit,2)}}</td><td>@if($disbursement->isreverse=='0') OK @else Cancelled @endif</td></tr>
<?php
if($disbursement->isreverse == '0'){
$tvoucher=$tvoucher + $disbursement->voucheramount;$tadvances=$tadvances+$disbursement->advances;
$tcost=$tcost + $disbursement->cost ;$tinstructional=$tinstructional+$disbursement->instructional;
$tsalaries=$tsalaries + $disbursement->salaries;$tdev=$tdev + $disbursement->dev;
$tbenefit=$tbenefit + $disbursement->benefit;$tsupplies=$tsupplies+ $disbursement->supplies;
$ttravel=$ttravel + $disbursement->travel;$tdebit=$tdebit + $disbursement->sundrydebit;
$tcredit=$tcredit+ $disbursement->sundrycredit;}
?>
@endforeach 

  <tr>
      <td colspan="2">Sub Total</td><td align="right">{{number_format($tvoucher,2)}}</td>
            <td align="right"> {{number_format($tadvances,2)}}</td><td align="right">{{number_format($tcost,2)}}</td>
        <td align="right">{{number_format($tinstructional,2)}}</td><td align="right">{{number_format($tsalaries,2)}}</td><td align="right">{{number_format($tdev,2)}}</td>
        <td align="right">{{number_format($tbenefit,2)}}</td><td align="right">{{number_format($tsupplies,2)}}</td><td align="right">{{number_format($ttravel,2)}}</td>
        <td align="right">{{number_format($tdebit,2)}}</td><td align="right">{{number_format($tcredit,2)}}</td><td></td></tr>

</table>
</div>    
</div>    
<script>
     $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('disbursementbook')}}" + "/" + $("#trandate").val();
    })
    
</script>    
@stop