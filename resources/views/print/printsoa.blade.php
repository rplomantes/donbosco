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
        <tr><td style="font-size:10pt;">Tel No : 892-01-01</td><td align="right">Plan : {{$statuses->plan}}</td></tr>
    </table>
    
    
<table><tr><td width="70%" valign="top" style="padding-right:0px;">
            <table style="font-size:10pt">
       <tr><td width="30%">Student No :</td><td><b>{{$users->idno}}</b></td></tr>  
       <tr><td>Name :</td><td><b>{{$users->lastname}}, {{$users->firstname}} {{$users->middlename}}</b></td></tr>
       @if(count($statuses)>0)
       <tr><td>Level/Section :</td><td> {{$statuses->level}} - {{$statuses->section}}</td></tr>
      
           @if($statuses->level == "Grade 9" || $statuses->level == "Grade 10" || $statuses->level == "Grade 11" || $statuses->level == "Grade 12" )
            <tr><td>
           Strand/Shop : <td>{{$statuses->strand}}</td>
            </td> </tr>         
           @endif
       
       @endif
    </table>
            
    <span style="font-size: 9pt;font-weight: bold"><u>ACCOUNT DETAILS</u></span>     
   <table style="font-size: 9pt;"><tr><td width='200px'>Account Description</td><td>Amount</td><td>Less: Discount</td><td>Payment</td><td>DM</td><td>Balance</td></tr>
       <?php
       $totamount = 0; $totdiscount=0; $totdm=0; $totpayment=0;
       $header = 0;
       
       $maintotamount = 0; $maintotdiscount=0; $maintotdm=0; $maintotpayment=0;
       $mainheader = 0;
       ?>
       @foreach($balances as $balance)
       <?php
       $totamount = $totamount + $balance->amount;
       $totdiscount = $totdiscount + $balance->discount;
       $totdm = $totdm + $balance->debitmemo;
       $totpayment = $totpayment+$balance->payment;
       
       $maintotamount = $maintotamount + $balance->amount;
       $maintotdiscount = $maintotdiscount + $balance->discount;
       $maintotdm = $maintotdm + $balance->debitmemo;
       $maintotpayment = $maintotpayment+$balance->payment;
       ?>
       @if($balance->categoryswitch <= 6)
       <tr><td>{{$balance->receipt_details}}</td><td align="right">{{number_format($balance->amount,2)}}</td>
           <td align="right">{{number_format($balance->discount,2)}}</td><td align="right">{{number_format($balance->payment,2)}}</td>
           <td align="right">{{number_format($balance->debitmemo,2)}}</td><td align="right">{{number_format($balance->amount-$balance->discount-$balance->payment-$balance->debitmemo,2)}}</td></tr>
       @endif
       @endforeach
       
       <!--Main Account Total-->
       <tr style="font-weight:bold"><td>Sub total</td><td align="right">{{number_format($maintotamount,2)}}</td>
           <td align="right">{{number_format($maintotdiscount,2)}}</td><td align="right">{{number_format($maintotpayment,2)}}</td>
           <td align="right">{{number_format($maintotdm,2)}}</td><td align="right">{{number_format($maintotamount-$maintotdiscount-$maintotpayment-$maintotdm,2)}}</td></tr>
       
       @if(count($others)>0)
       <tr><td><b><u>Additional Charges</u></b></td></tr>
       @endif
       <?php
       $prevtotamount = 0; $prevtotdiscount=0; $prevtotdm=0; $prevtotpayment=0;
       $prevheader = 0;
       
       $othertotamount = 0; $othertotdiscount=0; $othertotdm=0; $othertotpayment=0;
       $otherheader = 0;
       ?>
       @foreach($others as $balance)
            @if($balance->categoryswitch > 10)
            <?php
            $totamount = $totamount + $balance->amount;
            $totdiscount = $totdiscount + $balance->discount;
            $totdm = $totdm + $balance->debitmemo;
            $totpayment = $totpayment+$balance->payment;
            
            $prevtotamount = $prevtotamount + $balance->amount;
            $prevtotdiscount = $prevtotdiscount + $balance->discount;
            $prevtotdm = $prevtotdm + $balance->debitmemo;
            $prevtotpayment = $prevtotpayment+$balance->payment;
            
            $othertotamount = $othertotamount + $balance->amount;
            $othertotdiscount = $othertotdiscount + $balance->discount;
            $othertotdm = $othertotdm + $balance->debitmemo;
            $othertotpayment = $othertotpayment+$balance->payment;
            ?>
            @endif
       @endforeach
       
       @if($prevtotamount > 0)
            <tr><td>Previous Balance</td><td align="right">{{number_format($prevtotamount,2)}}</td>
                <td align="right">{{number_format($prevtotdiscount,2)}}</td><td align="right">{{number_format($prevtotpayment,2)}}</td>
                <td align="right">{{number_format($prevtotdm,2)}}</td><td align="right">{{number_format($prevtotamount-$prevtotdiscount-$prevtotpayment-$prevtotdm,2)}}</td></tr>
       @endif
       
       @foreach($others as $balance)
            @if(($balance->categoryswitch > 6 && $balance->categoryswitch < 10) && strpos($balance->description,'Penalty') === false)
            <?php
            $totamount = $totamount + $balance->amount;
            $totdiscount = $totdiscount + $balance->discount;
            $totdm = $totdm + $balance->debitmemo;
            $totpayment = $totpayment+$balance->payment;
            
            $othertotamount = $othertotamount + $balance->amount;
            $othertotdiscount = $othertotdiscount + $balance->discount;
            $othertotdm = $othertotdm + $balance->debitmemo;
            $othertotpayment = $othertotpayment+$balance->payment;
            ?>
            <tr><td>{{$balance->receipt_details}}</td><td align="right">{{number_format($balance->amount,2)}}</td>
                <td align="right">{{number_format($balance->discount,2)}}</td><td align="right">{{number_format($balance->payment,2)}}</td>
                <td align="right">{{number_format($balance->debitmemo,2)}}</td><td align="right">{{number_format($balance->amount-$balance->discount-$balance->payment-$balance->debitmemo,2)}}</td></tr>
            @endif
       @endforeach
       
       @foreach($others as $balance)
            @if(strpos($balance->description,'Penalty') !== false)
            <?php
            $totamount = $totamount + $balance->amount;
            $totdiscount = $totdiscount + $balance->discount;
            $totdm = $totdm + $balance->debitmemo;
            $totpayment = $totpayment+$balance->payment;
      
            $othertotamount = $othertotamount + $balance->amount;
            $othertotdiscount = $othertotdiscount + $balance->discount;
            $othertotdm = $othertotdm + $balance->debitmemo;
            $othertotpayment = $othertotpayment+$balance->payment;
            ?>
            <tr><td>{{$balance->receipt_details}}</td><td align="right">{{number_format($balance->amount,2)}}</td>
                <td align="right">{{number_format($balance->discount,2)}}</td><td align="right">{{number_format($balance->payment,2)}}</td>
                <td align="right">{{number_format($balance->debitmemo,2)}}</td><td align="right">{{number_format($balance->amount-$balance->discount-$balance->payment-$balance->debitmemo,2)}}</td></tr>
            @endif
       @endforeach
       @if(count($others)>0)
       <tr style="font-weight:bold"><td>Sub total</td><td align="right">{{number_format($othertotamount,2)}}</td>
           <td align="right">{{number_format($othertotdiscount,2)}}</td><td align="right">{{number_format($othertotpayment,2)}}</td>
           <td align="right">{{number_format($othertotdm,2)}}</td><td align="right">{{number_format($othertotamount-$othertotdiscount-$othertotpayment-$othertotdm,2)}}</td></tr>
       @endif
       <tr><td colspan="5"><br></td></tr>
       <tr style="font-weight:bold"><td>Total</td><td align="right">{{number_format($totamount,2)}}</td>
           <td align="right">{{number_format($totdiscount,2)}}</td><td align="right">{{number_format($totpayment,2)}}</td>
           <td align="right">{{number_format($totdm,2)}}</td><td align="right">{{number_format($totamount-$totdiscount-$totpayment-$totdm,2)}}</td></tr>
       
  </table>     
   
        
        </td><td valign="top" style="padding-right:0px;padding-left:0px;">
    <h5></h5>
    <table style="font-size:10pt;border:thin" border="1" cellpadding="1" cellspacing='0'>
    <tr><td>Total Amount</td><td align="right">{{number_format($totamount,2)}}</tr>
    <tr><td>Less : Discount</td><td align="right">({{number_format($totdiscount,2)}})</tr>
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Debit Memo</td><td align="right">({{number_format($totdm,2)}})</tr>
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Payment</td><td align="right">({{number_format($totpayment,2)}})</tr>
    <tr><td>Total Balance</td><td align="right">{{number_format($totamount-$totdiscount-$totdm-$totpayment,2)}}</tr>
    <tr style="font-size:11pt;font-weight:bold"><td>Due Date</td><td align="right">{{date('M d, Y',strtotime($trandate))}}</tr>
    <tr style="font-size:11pt;font-weight:bold"><td>Total Due</td><td align="right">{{number_format($totaldue,2)}}</tr>
    </table>
    <br>
  
 @if(count($schedules)>0)
 <span style="font-size:8pt;font-style: italic">Installment Schedule</span>
 <table style="font-size: 8pt;"><tr><td>For the month of</td><td align="center">Due Amount</td>
      @foreach($schedules as $schedule)
      
      <tr><td>@if(date('M',strtotime($schedule->duedate))=="Apr")
              Upon Enrollment
              @else
              {{date('M  Y',strtotime($schedule->duedate))}}
              @endif
          </td><td align="right">{{number_format($schedule->amount-($schedule->discount+$schedule->payment+$schedule->debitmemo),2)}}</td></tr>
      
      @endforeach
 </table>
 @endif

</td>
</table>
    <table style="position: absolute;bottom:5.5in"><tr><td width="70%">
    <p style="font-size: 8pt;"><b>Reminder:</b><br>
        @if(strlen($reminder) == 0)
        Please disregard this statement if payment has been made. Last day of payment is <b>{{date('M d, Y',strtotime($trandate))}}</b>. Payments made after due date is subject 
        to penalty of 5% or P250.00 whichever is higher. ADMINISTRATION
        @else
            {{$reminder}}
        @endif
    </P>
    </td>
    <td><img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/frbocsignature.png" height="80" style="position:absolute;margin-left:20"><br><br>
        <p align="center; font-size:9pt;">Fr. Manuel H. Nicholas, SDB<br>
            Administrator</p>
    </tr>
    </table>

</body>
</html>
