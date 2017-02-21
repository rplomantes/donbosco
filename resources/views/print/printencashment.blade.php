<div class="container">
<div class="col-md-12">    
<h5> Encashment </h5>
<table class="table table-striped" width="100%"><tr><td>Payee</td><td>On-us</td><td>Bank</td><td>Check Number</td><td>Amount</td><td>Remarks</td><td></td></tr>
<?php
$totalcancell = 0;
$totalcbc = 0;
$totalbpi1=0;
$totalbpi2=0;
$totalcbcother=0;
$totalothers=0;
?>
    @foreach($encashmentreports as $encashmentreport)
    <?php
    switch($encashmentreport->whattype){
    case "China Bank":
        if($encashmentreport->isreverse=="1"){
            $totalcancell = $totalcancell + $encashmentreport->amount;
        }else{
            $totalcbc = $totalcbc + $encashmentreport->amount;
        }
        break;
     case "BPI 1":
        if($encashmentreport->isreverse=="1"){
            $totalcancell = $totalcancell + $encashmentreport->amount;
        }else{
            $totalbpi1 = $totalbpi1 + $encashmentreport->amount;
        }
        break;  
        case "BPI 2":
        if($encashmentreport->isreverse=="1"){
            $totalcancell = $totalcancell + $encashmentreport->amount;
        }else{
            $totalbpi2 = $totalbpi2 + $encashmentreport->amount;
        }
        break; 
         case "China Bank Other":
        if($encashmentreport->isreverse=="1"){
            $totalcancell = $totalcancell + $encashmentreport->amount;
        }else{
            $totalcbcother = $totalcbcother + $encashmentreport->amount;
        }
        break;
         case "Other Check":
        if($encashmentreport->isreverse=="1"){
            $totalcancell = $totalcancell + $encashmentreport->amount;
        }else{
            $totalothers = $totalothers + $encashmentreport->amount;
        }
        break;
    }
    ?>
    <tr><td>{{$encashmentreport->payee}}</td>
    <td>{{$encashmentreport->whattype}}</td>
    <td>{{$encashmentreport->bank_branch}}</td>
    <td>{{$encashmentreport->check_number}}</td>
    <td>{{$encashmentreport->amount}}</td>
    <td>
    @if($encashmentreport->isreverse =="0")
    Ok
    @else
    Cancelled
    @endif
    </td>
    <td>
       
    </td>
@endforeach
</table>
</div>
<div class="col-md-4">
<h5>Encashment Breakdown</h5>
<table class="table table-striped">
    <tr><td sty>China Bank (On-us)</td><td style="text-align: right"><?php echo number_format($totalcbc,2);?></td></tr>
    <tr><td>BPI 1 (On-us)</td><td  style="text-align: right"><?php echo number_format($totalbpi1,2);?></td></tr>
    <tr><td>BPI 2 (On-us)</td><td style="text-align: right"><?php echo number_format($totalbpi2,2);?></td></tr>
    <tr><td>Other China Bank</td><td  style="text-align: right"><?php echo number_format($totalcbcother,2);?></td></tr>
    <tr><td>Other Checks</td><td  style="text-align: right"><?php echo number_format($totalothers,2);?></td></tr>
    <tr><td>Total</td><td style="text-align: right;font-weight: bold"><?php echo number_format($totalcbc + $totalbpi1 + $totalbpi2 +  $totalcbcother + $totalothers,2);?></td></tr>
    <tr><td>Cancelled Encashment</td><td  style="text-align: right; color: red;font-weight: bold"><?php echo number_format($totalcancell,2);?></td></tr>
</table>
   
</div>

</div>

