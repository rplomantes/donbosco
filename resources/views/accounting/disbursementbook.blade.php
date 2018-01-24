<?php

$disbursements = DB::Select(" Select transactiondate, voucherno, payee,  sum(voucheramount) as voucheramount, isreverse, sum(advances_employee) as advances, sum(cost_of_goods) as cost, sum(instructional_materials) as instructional, "
        . "sum(salaries_allowances) as salaries,sum(personnel_dev) as dev,sum(other_emp_benefit) as benefit, sum(office_supplies) as supplies,"
        . "sum(travel_expenses) as travel, sum(sundry_debit) as sundrydebit,sum(sundry_credit) as sundrycredit from rpt_disbursement_books where totalindic = '0' "
        . "group by transactiondate, voucherno, payee, isreverse");

$totalmonths = DB::Select("Select sum(voucheramount)as voucheramount,sum(advances_employee)advances, sum(cost_of_goods)as cost, sum(instructional_materials) as instructional, "
        . "sum(salaries_allowances) as salaries,sum(personnel_dev) as dev,sum(other_emp_benefit) as benefit, sum(office_supplies) as supplies,"
        . "sum(travel_expenses) as travel, sum(sundry_debit)  as sundrydebit,sum(sundry_credit) as sundrycredit from rpt_disbursement_books where totalindic = '1' and transactiondate <> '$trandate' and isreverse = '0'");

  $totalvoucher=0;$totaladvances=0;$totalcost=0;$totalinstructional=0;
  $totalsalaries=0;$totaldev=0;$totalbenefit=0;$totalsupplies=0;
  $totaltravel=0;$totaldebit=0;$totalcredit=0;   
   foreach($totalmonths as $totalmonth){
   $totalvoucher=$totalvoucher + $totalmonth->voucheramount;
   $totaladvances=$totaladvances + $totalmonth->advances;
   $totalcost=$totalcost + $totalmonth->cost;
   $totalinstructional=$totalinstructional + $totalmonth->instructional;
   $totalsalaries=$totalsalaries + $totalmonth->salaries;
   $totaldev=$totaldev + $totalmonth->dev;
   $totalbenefit=$totalbenefit + $totalmonth->benefit;
   $totalsupplies=$totalsupplies = $totalmonth->supplies;
   $totaltravel=$totaltravel + $totalmonth->travel;
   $totaldebit=$totaldebit + $totalmonth->sundrydebit;
   $totalcredit=$totalcredit + $totalmonth->sundrycredit;   
}

$tvoucher=0;$tadvances=0;$tcost=0;$tinstructional=0;
$tsalaries=0;$tdev=0;$tbenefit=0;$tsupplies=0;
$ttravel=0;$tdebit=0;$tcredit=0;

$stvoucher=0;$stadvances=0;$stcost=0;$stinstructional=0;
$stsalaries=0;$stdev=0;$stbenefit=0;$stsupplies=0;
$sttravel=0;$stdebit=0;$stcredit=0;

$nooflines=20;
$countline=0;

?>
@extends('appaccounting')
@section('content')
<div class="container_fluid">
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

        <tr><td colspan="2">Beg. Balance ({{date('M Y',strtotime($trandate))}}) :</td><td align="right">{{number_format($totalvoucher,2)}}</td>
        <td align="right"> {{number_format($totaladvances,2)}}</td><td align="right">{{number_format($totalcost,2)}}</td>
        <td align="right">{{number_format($totalinstructional,2)}}</td><td align="right">{{number_format($totalsalaries,2)}}</td><td align="right">{{number_format($totaldev,2)}}</td>
        <td align="right">{{number_format($totalbenefit,2)}}</td><td align="right">{{number_format($totalsupplies,2)}}</td><td align="right">{{number_format($totaltravel,2)}}</td>
        <td align="right">{{number_format($totaldebit,2)}}</td><td align="right">{{number_format($totalcredit,2)}}</td><td></td></tr>

@foreach($disbursements as $disbursement)
    @if($countline == $nooflines)
    <td colspan="2"> Sub Total</td><td align="right">{{number_format($stvoucher,2)}}</td>
            <td align="right"> {{number_format($stadvances,2)}}</td><td align="right">{{number_format($stcost,2)}}</td>
        <td align="right">{{number_format($stinstructional,2)}}</td><td align="right">{{number_format($stsalaries,2)}}</td><td align="right">{{number_format($stdev,2)}}</td>
        <td align="right">{{number_format($stbenefit,2)}}</td><td align="right">{{number_format($stsupplies,2)}}</td><td align="right">{{number_format($sttravel,2)}}</td>
        <td align="right">{{number_format($stdebit,2)}}</td><td align="right">{{number_format($stcredit,2)}}</td><td></td></tr>
     </table>
        <table class="table table-bordered table-striped">
        <tr><th>Voucher No</th><th>Payee</th><th>Voucher Amount</th><th>Advance To Employee</th><th>Cost of Sales</th>
        <th>Instructional  Materials</th><th>Salaries / Allowances</th><th>Personnel <br>Development</th>
        <th>Other Employee Benefit</th><th>Office Supplies</th><th>Travel Expenses</th>
        <th>Sundries Debit</th><th>Sundies Credit</th><th>Status</th></tr>
        <?php $countline = 0;
        
        $stvoucher=0;$stadvances=0;$stcost=0;$stinstructional=0;
        $stsalaries=0;$stdev=0;$stbenefit=0;$stsupplies=0;
        $sttravel=0;$stdebit=0;$stcredit=0;
        ?>
    @else
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
$tcredit=$tcredit+ $disbursement->sundrycredit;

$stvoucher=$stvoucher + $disbursement->voucheramount;
$stadvances=$stadvances + $disbursement->advances;
$stcost=$stcost + $disbursement->cost;
$stinstructional=$stinstructional+$disbursement->instructional;
$stsalaries=$stsalaries+$disbursement->salaries;
$stdev=$stdev + $disbursement->dev;
$stbenefit=$stbenefit + $disbursement->benefit;
$stsupplies=$stsupplies + $disbursement->supplies;
$sttravel=$sttravel+$disbursement->travel;
$stdebit=$stdebit+$disbursement->sundrydebit;
$stcredit=$stcredit+$disbursement->sundrycredit;
$countline=$countline+1;
}
?>
@endif
@endforeach 
<td colspan="2"> Sub Total</td><td align="right">{{number_format($stvoucher,2)}}</td>
            <td align="right"> {{number_format($stadvances,2)}}</td><td align="right">{{number_format($stcost,2)}}</td>
        <td align="right">{{number_format($stinstructional,2)}}</td><td align="right">{{number_format($stsalaries,2)}}</td><td align="right">{{number_format($stdev,2)}}</td>
        <td align="right">{{number_format($stbenefit,2)}}</td><td align="right">{{number_format($stsupplies,2)}}</td><td align="right">{{number_format($sttravel,2)}}</td>
        <td align="right">{{number_format($stdebit,2)}}</td><td align="right">{{number_format($stcredit,2)}}</td><td></td></tr>
</table>
<table class="table table-bordered table-striped">
    <tr><th colspan="2"></th><th>Voucher Amount</th><th>Advance To Employee</th><th>Cost of Sales</th>
        <th>Instructional  Materials</th><th>Salaries / Allowances</th><th>Personnel <br>Development</th>
        <th>Other Employee Benefit</th><th>Office Supplies</th><th>Travel Expenses</th>
        <th>Sundries Debit</th><th>Sundies Credit</th><th></th></tr>
  <tr>
      <td colspan="2"> Total</td><td align="right">{{number_format($tvoucher,2)}}</td>
            <td align="right"> {{number_format($tadvances,2)}}</td><td align="right">{{number_format($tcost,2)}}</td>
        <td align="right">{{number_format($tinstructional,2)}}</td><td align="right">{{number_format($tsalaries,2)}}</td><td align="right">{{number_format($tdev,2)}}</td>
        <td align="right">{{number_format($tbenefit,2)}}</td><td align="right">{{number_format($tsupplies,2)}}</td><td align="right">{{number_format($ttravel,2)}}</td>
        <td align="right">{{number_format($tdebit,2)}}</td><td align="right">{{number_format($tcredit,2)}}</td><td></td></tr>
<tr>
      <td colspan="2">Grand Total</td><td align="right"><b>{{number_format($tvoucher+$totalvoucher,2)}}</b></td>
            <td align="right"> <b>{{number_format($tadvances+$totaladvances,2)}}</b></td><td align="right"><b>{{number_format($tcost+$totalcost,2)}}</b></td>
        <td align="right"><b>{{number_format($tinstructional+$totalinstructional,2)}}</b></td><td align="right"><b>{{number_format($tsalaries+$totalsalaries,2)}}</b></td><td align="right"><b>{{number_format($tdev+$totaldev,2)}}</b></td>
        <td align="right"><b>{{number_format($tbenefit+$totalbenefit,2)}}</b></td><td align="right"><b>{{number_format($tsupplies+$totalsupplies,2)}}</b></td><td align="right"><b>{{number_format($ttravel+$totaltravel,2)}}</b></td>
        <td align="right"><b>{{number_format($tdebit+$totaldebit,2)}}</b></td><td align="right"><b>{{number_format($tcredit+$totalcredit,2)}}</b></td><td></td></tr>
</table>
</div> 
    <div class="col-md-12">
        <a href="{{url('printdisbursementpdf',$trandate)}}" class="btn btn-primary" target="_blank">Print Disbursement Book</a>
    </div>    
</div>    
<script>
     $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('disbursementbook')}}" + "/" + $("#trandate").val();
    })
    
</script>    
@stop