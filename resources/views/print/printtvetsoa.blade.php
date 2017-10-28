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
    font-size: 10pt;
}
html{
    margin-top: 0px;
    margin-left:.1in;
    margin-right:.1in;
}
body{
    margin:0px;
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
    height: 15px;
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
    font-size: 10pt;
    font-weight: bold;
}
        </style>
	<!-- Fonts -->
        <style media="print">
            html{
                margin-right: 0px;
            }
        </style>
        </head>
<body style="max-height: 6.3in;height: 1px;position:relative;padding-top:25px;padding-bottom:25px;page-break-after:always;">

    <table border = '0' cellpacing="0" cellpadding = "0" width="100%" align="center">
        <tr><td rowspan="3" width="50">
        <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/logo.png" width="60">
        </td><td width="70%"><span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span></td><td align="right"><span style="font-size:14pt; font-style:italic; font-weight: bold;">STATEMENT OF ACCOUNT</span></td></tr>
        <tr><td style="font-size:10pt;">Chino Roces Ave., Makati City </td><td align="right">Date : {{date('M d, Y')}}</td></tr>
        <tr><td style="font-size:10pt;">Tel No : 892-01-01 to 08</td><td align="right">Plan : {{$statuses->plan}}</td></tr>
    </table>
    
    
<table style="margin-top: 20px;">
    <tr>
        <td width="70%" valign="top" style="padding-right:0px;">
            <table style="font-size:10pt">
                <tr><td width="30%">Student No :</td><td><b>{{$users->idno}}</b></td></tr>  
                    <tr><td>Name :</td><td><b>{{$users->lastname}}, {{$users->firstname}} {{$users->middlename}}</b></td></tr>
                    @if(count($statuses)>0)
            
                        <tr><td>Batch/Section :</td><td>Batch {{$statuses->period}} - {{$statuses->section}}</td></tr>
                        <tr>
                            <td>Course :</td> <td>{{$statuses->course}}</td>
                        </tr>         
       
        @endif
        </table>
            

       <?php
       $totamount = 0; $totdiscount=0; $totalsponsor=0; $totsubsidy=0;
       $totpayment = 0;
       $total_receipt = 0;
       ?>
       @foreach($balances as $balance)
       <?php
       $totamount = $totamount + $balance->amount;
       $totdiscount = $totdiscount + $balance->discount;
       $totalsponsor = $totalsponsor + $balance->sponsor;
       $totsubsidy = $totsubsidy+$balance->subsidy;
       $totpayment = $totpayment+$balance->payment;
       
       ?>
       @endforeach
    
   <table style="font-size: 9pt;">
       <tr style="text-align: right">
           <td width="100px">Total Training Fee</td><td>TraineesContribution</td><td>Sponsor</td><td>Payment</td><td>Subsidy</td><td>Balance</td>
       </tr>
       <?php
       $totamount = 0; $totdiscount=0; $totalsponsor=0; $totsubsidy=0;
       $totpayment = 0;
       
       ?>
       @foreach($balances as $balance)
       <?php
       $totamount = $totamount + $balance->amount;
       $totdiscount = $totdiscount + $balance->discount;
       $totalsponsor = $totalsponsor + $balance->sponsor;
       $totsubsidy = $totsubsidy+$balance->subsidy;
       $totpayment = $totpayment+$balance->payment;
       
       ?>
       
        <tr>
           <td align="right">{{number_format($balance->amount,2)}}</td><td  align="right">{{number_format($balance->trainees,2)}}</td>
           </td><td align="right">{{number_format($balance->sponsor,2)}}</td>
           <td align="right">{{number_format($balance->payment,2)}}</td><td align="right">{{number_format($balance->subsidy+$balance->discount,2)}}</td><td align="right">{{number_format($balance->amount-$balance->discount-$balance->payment-$balance->subsidy-$balance->sponsor,2)}}</td>
        </tr>
       
       @endforeach
       
       @if(count($others)>0)
        <tr  style="text-align: right"><td>Account Description</td><td>Amount</td><td width="100px">Less: Discount</td><td>Payment</td><td>DM</td><td>Balance</td></tr>
       @endif
       @foreach($others as $other)
            @if($other->categoryswitch > 6 && $other->categoryswitch < 10)

		@if($other->amount-($other->discount+$other->debitmemo+$other->payment) > 0)
            <tr><td>{{$other->receipt_details}}</td><td align="right">{{number_format($other->amount,2)}}</td>
                <td align="right">{{number_format($other->discount,2)}}</td><td align="right">{{number_format($other->payment,2)}}</td>
                <td align="right">{{number_format($other->debitmemo,2)}}</td><td align="right">{{number_format($other->amount-$other->discount-$other->payment-$other->debitmemo,2)}}</td></tr>
		@endif
            @endif
       @endforeach
    </table>
    <table style="font-size: 9pt;margin-top: 20px">
        <thead>
            <tr><td colspan="3" style="font-size: 9pt;font-weight: bold"><b><u>Transaction History</u></b></td></tr>
            <tr><td>Receipt No.</td><td>Transaction Date</td><td>Amount</td></tr></thead>
        @foreach($transactionreceipts as $transactionreceipt)
        <tr>
            <?php $total_receipt = $total_receipt + $transactionreceipt->amount; ?>
            <td>{{$transactionreceipt->receiptno}}</td>
            <td>{{$transactionreceipt->transactiondate}}</td>
            <td>{{number_format($transactionreceipt->amount,2)}}</td>
        </tr>
        @endforeach
        <tr><td colspan="2" style="text-align: right;border-top: 1px solid;">Total</td><td style="border-top: 1px solid;">{{number_format($total_receipt,2)}}</td></tr>
    </table>
        
        </td>
        <td valign="top" style="padding-right:0px;padding-left:0px;">
    <h5></h5>
    <table style="font-size:10pt;border:thin" border="1" cellpadding="1" cellspacing='0'>
    <tr><td><b>Total Amount</b></td><td align="right">{{number_format($totamount,2)}}</tr>
    

    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sponsor</td><td align="right">({{number_format($totalsponsor,2)}})</tr>
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subsidy</td><td align="right">({{number_format($totsubsidy+$totdiscount,2)}})</tr>
    <tr><td><b>Trainees Contribution</b></td><td align="right">({{number_format($balance->trainees,2)}})</tr>
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment</td><td align="right">({{number_format($totpayment,2)}})</tr>

    <tr><td style="border: 1px solid black;">Total Balance</td><td align="right" style="border: 1px solid black;">{{number_format($totamount-$totdiscount-$totpayment-$totsubsidy-$totalsponsor,2)}}</tr>
    <tr style="font-size:11pt;font-weight:bold"><td>Total Due</td><td align="right">{{number_format($totaldue,2)}}</tr>
    </table>
    <br>
</td>
</table>
    <!--table style="position: absolute;bottom:5.5in"-->
    <table style="margin-top:20px">
        <tr><td width="70%">
    <p style="font-size: 8pt;">
        @if(!ctype_space($reminder))
        <b>Reminder:</b><br>
        @endif
        @if(strlen($reminder) == 0)
        Please disregard this statement if payment has been made. Last day of payment is <b>{{date('M d, Y',strtotime($trandate))}}</b>. Payments made after due date is subject 
        to penalty of 5% or P250.00 whichever is higher. ADMINISTRATION
        @else
            {{$reminder}}
        @endif
    </P>
    </td>
    <td><img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/frsonny.png" height="80" style="position:absolute;margin-left:20"><br><br>
        <p align="center; font-size:9pt;">Fr. Sonny F. Arevalo, SDB<br>
            Administrator</p>
    </tr>
    </table>

</body>
</html>
