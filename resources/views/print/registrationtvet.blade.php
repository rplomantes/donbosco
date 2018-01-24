<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       
        
 <style>
    .body table, th  , .body td{
    border: 1px solid black;
    font-size: 12pt;
}

td{
    padding-right: 10px;
    padding-left: 10px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 20px;
}

.notice{
    font-size: 10pt;
    padding:5px;
    border: 1px solid #000;
    text-indent: 10px;
    margin-top: 5px;
}
.footer{
  padding-top:10px;
    
}
.heading{
    padding-top: 10px;
    font-size: 12pt;
    font-weight: bold;
}
        </style>
	<!-- Fonts -->
	
        </head>
<body> 
    <table border = '0'celpacing="0" cellpadding = "0" width="550px" align="center"><tr><td width="10px">
        <img src = "{{ asset('/images/logo.png') }}" alt="DBTI" align="middle"  width="70px"/></td>
            <td width="530px"><p align="center"><span style="font-size:20pt;">Don Bosco Technical Institute</span><br>
        Chino Roces Ave., Makati City <br>
        Tel No : 892-01-01
        </p>
    </td>
    </tr>
    </table>
    <h3 align="center">REGISTRATION/ASSESSMENT FORM</h3>
<table width='80%'>
<tr><td>Student Id</td><td> : </td><td>{{$user->idno}}</td></tr>
<tr><td>Name</td><td> : </td><td>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}} {{$user->extensionname}}</td></tr>
@if($status->department != "TVET")
    <tr><td>Level</td><td> : </td><td>{{$status->level}}</td></tr>
    @if($status->level == 'Grade 9' || $status->level == 'Grade 10')
    <tr><td>Specialization</td><td> : </td><td>{{$status->strand}}</td></tr>
    @endif
    @if($status->department=="Senior High School")
    <tr><td>Track</td><td> : </td><td>{{$status->track}}</td></tr>
    <tr><td>Strand</td><td> : </td><td>{{$status->strand}}</td></tr>
    @endif
@else
    <tr><td>Course</td><td> : </td><td>{{$status->course}}</td></tr>
     <tr><td>Batch</td><td> : </td><td>{{$status->period}}</td></tr>
@endif
</table>

<div class="body">  
   
   <div class="heading">Breakdown of Fees</div>
    <table> 
    <thead>
        <tr><th width = "60%">Description</th><th>Amount</th></tr>
    </thead>    
    <tbody>
        <?php $totalamount=0; $totalplandiscount=0; $totalotherdiscount=0; ?>
        @foreach($breakdownfees as $breakdownfee)
        <tr><td>{{$breakdownfee->receipt_details}}</td><td align="right">{{number_format($breakdownfee->amount,2)}}</td></tr>
        <?php $totalamount = $totalamount + $breakdownfee->amount;
              $totalplandiscount = $totalplandiscount + $breakdownfee->plandiscount;
              $totalotherdiscount = $totalotherdiscount + $breakdownfee->otherdiscount;
              if(isset($reservation->amount)){
                  $reserve = $reservation->amount;
              }else{$reserve=0;}
              ?>
        @endforeach
        
        </tbody>
        <tfoot>
        <tr ><td class='footer' style="font-weight:bold">Total</td><td class='footer' align="right" ><strong>{{number_format($totalamount-$totalplandiscount-$totalotherdiscount-$reserve,2)}}</strong></td></tr>
        <tr><td style="font-weight:bold">Please pay the amount of</td><td style="font-weight: bold" align="right">
            @if($totalamount < 2000)
                {{number_format($totalamount,2)}}
            @else
            2,000.00
            @endif
            </td></tr>
        </tfoot>
    </table>
   <div class="heading">Sponsor/Subsidy</div>
   <?php
   $subsidies = \App\TvetSubsidy::where('idno',$user->idno)->first();
   ?>
   <table><tr><th>Sponsor</th><th>School Subsidy</th><th>Discount</th></tr>
       <tr><td align="right">{{number_format($subsidies->sponsor,2)}}</td>
           <td align="right">{{number_format($subsidies->subsidy,2)}}</td>
           <td align="right">{{number_format($subsidies->discount,2)}}</td>
       </tr>        
   </table>    
</div>
    
 <div class="notice">
    In accordance with the financial policies of the school as set out in the Students' Handbook, failure to meet the financial
    obligation to the school within the specified period may result in the withholding of transfer credentials.
    <br><br>
    <i style="font-weight: bold">*If check payment, write name of student and contact no at the back of the check.</i><br>
    <i style="font-weight: bold">*Failure to pay the remaining Trainee's Contribution before graduation may result the revocation of the school subsidy and the student pays the full training cost.</i>
</div>

<div class="notice">
    <table width = "100%">
        
        <tr><td>Assessed By:</td><td>Date:</td><td>Cashier:</td><td>OR#</td></tr>
        <tr><td style="height: 5px;"></td><td></td><td></td></tr>
        <tr><td></td><td></td><td></td></tr>
        <tr><td>{{$postedby->lastname}}, {{$postedby->firstname}}</td><td>{{$ledger->transactiondate}}</td><td><hr></td><td><hr /></td></tr>
        
    </table>    
                       
</div>    
   
