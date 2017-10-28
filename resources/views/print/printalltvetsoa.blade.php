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
    font-size: 11pt;
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
	<script media="print">
            html{
                margin-left:.1in;
                margin-right:.1in;
            }
            html,body{
                margin-top:0px!important;
                padding-top:0px;
            }
        </script>
        </head>
<body style="margin-top:0px"> 
    
    <?php
       foreach($soasummary as $soasum){
       $idno = $soasum->idno;    
       $statuses = \App\Status::where('idno',$idno)->first();
       $users = \App\User::where('idno',$idno)->first(); 
        $balances = DB::Select("select sum(amount)+sum(s.discount)+sum(s.subsidy)+sum(sponsor) as amount,sum(amount) as trainees , sum(s.discount) as discount, sum(payment) as payment, sum(sponsor) as sponsor,"
              . "sum(s.subsidy) as subsidy ,receipt_details from ledgers join tvet_subsidies as s on s.idno=ledgers.idno and s.batch=ledgers.period where ledgers.idno = '$idno' and ledgers.receipt_details LIKE 'Trainee%' group by receipt_details, categoryswitch order by categoryswitch");

       $schedules=DB::Select("select sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount, "
               . "sum(payment) as payment, sum(debitmemo) as debitmemo, duedate  from ledgers  where "
               . " idno = '$idno' and (categoryswitch <= '6' or ledgers.receipt_details LIKE 'Trainee%') group by "
               . "duedate order by duedate");
       
       $others=DB::Select("select sum(amount) - sum(plandiscount) - sum(otherdiscount) - "
               . "sum(payment) - sum(debitmemo) as balance ,sum(amount) as amount , sum(plandiscount) + sum(otherdiscount) as discount,"
               . "sum(payment) as payment, sum(debitmemo) as debitmemo,description, receipt_details, categoryswitch from ledgers  where "
               . " idno = '$idno' and categoryswitch > '6' and ledgers.receipt_details NOT LIKE 'Trainee%'  group by "
               . "receipt_details, transactiondate order by LEFT(receipt_details, 4) ASC,id");
       
      $transactionreceipts = DB::Select("select transactiondate,receiptno,amount from "
              . "(select transactiondate,receiptno,sum(amount)+sum(checkamount) as amount from dedits where idno ='$idno' and paymenttype=1  and isreverse = 0 group by refno"
              . " UNION ALL "
              . "select transactiondate,receiptno,amount from old_receipts where idno ='$idno') allrec order by transactiondate, receiptno");
      
       $schedulebal = 0;
       if(count($schedules)>0){
           foreach($schedules as $sched){
               if($sched->duedate <= $trandate){
                    $schedulebal = $schedulebal + $sched->amount - $sched->discount -$sched->debitmemo - $sched->payment;
               }
           }
       }
       $otherbalance = 0;
       if(count($others)>0){
           foreach($others as $ot){
               $otherbalance = $otherbalance+$ot->balance;
           }
       }
       
       $totaldue = $schedulebal + $otherbalance;
    ?>
    
    <div style="max-height: 6.3in;height: 6.3in;position:relative;padding-top:25px;padding-bottom:25px;page-break-after:always;">
    <table border = '0' cellpacing="0" cellpadding = "0" width="100%" align="center">
        <tr><td rowspan="3" width="50" style="padding-right: 0px;">
        <img src="{{url('images','logo.png')}}" width="60">
        </td><td width="50%"><span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span></td><td align="right"><span style="font-size:14pt; font-style:italic; font-weight: bold;">STATEMENT OF ACCOUNT</span></td></tr>
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
                    <tr><td>
                   Course : <td>{{$statuses->course}}</td>
                    </td> </tr> 
                @endif
            </table>   


       <?php
       $totamount = 0; $totdiscount=0; $totalsponsor=0; $totsubsidy=0;
       $totpayment = 0;
       $total_receipt = 0;

       foreach($balances as $balance){
       $totamount = $totamount + $balance->amount;
       $totdiscount = $totdiscount + $balance->discount;
       $totalsponsor = $totalsponsor + $balance->sponsor;
       $totsubsidy = $totsubsidy+$balance->subsidy;
       $totpayment = $totpayment+$balance->payment;
       }
       ?>
    <br><br>
   <table style="font-size: 9pt;"><tr style="text-align: right"><td width="100px">Total Training Fee</td><td>TraineesContribution</td><td>Sponsor</td><td>Payment</td><td>Subsidy</td><td>Balance</td></tr>
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
       
       <tr><td align="right">{{number_format($balance->amount,2)}}</td><td  align="right">{{number_format($balance->trainees,2)}}</td>
           </td><td align="right">{{number_format($balance->sponsor,2)}}</td>
           <td align="right">{{number_format($balance->payment,2)}}</td><td align="right">{{number_format($balance->subsidy+$balance->discount,2)}}</td><td align="right">{{number_format($balance->amount-$balance->discount-$balance->payment-$balance->subsidy-$balance->sponsor,2)}}</td></tr>
       
       @endforeach
       
       @if(count($others)>0)
        <tr  style="text-align: right"><td>Account Description</td><td>Amount</td><td width="100px">Less: Discount</td><td>Payment</td><td>DM</td><td>Balance</td></tr>
       @foreach($others as $other)
            @if($other->categoryswitch > 6 && $other->categoryswitch < 10)

		@if($other->amount-($other->discount+$other->debitmemo+$other->payment) > 0)
            <tr><td>{{$other->receipt_details}}</td><td align="right">{{number_format($other->amount,2)}}</td>
                <td align="right">{{number_format($other->discount,2)}}</td><td align="right">{{number_format($other->payment,2)}}</td>
                <td align="right">{{number_format($other->debitmemo,2)}}</td><td align="right">{{number_format($other->amount-$other->discount-$other->payment-$other->debitmemo,2)}}</td></tr>
		@endif
            @endif
       @endforeach
       @endif
    </table>
         
        <br>
        
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
        <td valign="top" style="padding-left: 0px;padding-right: 0px;">
            <table style="font-size:10pt;border:thin" border="1" cellpadding="1" cellspacing='0'>
                <tr><td style="border: 1px solid black;"><b>Total Amount</b></td><td align="right" style="border: 1px solid black;">{{number_format($totamount,2)}}</tr>


                <tr><td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sponsor</td><td align="right" style="border: 1px solid black;">({{number_format($totalsponsor,2)}})</tr>
                <tr><td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subsidy</td><td align="right" style="border: 1px solid black;">({{number_format($totsubsidy+$totdiscount,2)}})</tr>
                <tr><td style="border: 1px solid black;"><b>Trainees Contribution</b></td><td align="right" style="border: 1px solid black;">({{number_format($balance->trainees,2)}})</tr>
                <tr><td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment</td><td align="right" style="border: 1px solid black;">({{number_format($totpayment,2)}})</tr>

                <tr><td style="border: 1px solid black;">Total Balance</td><td align="right" style="border: 1px solid black;">{{number_format($totamount-$totdiscount-$totpayment-$totsubsidy-$totalsponsor,2)}}</tr>
                <tr style="font-size:11pt;font-weight:bold"><td style="border: 1px solid black;">Total Due</td><td align="right" style="border: 1px solid black;">{{number_format($totaldue,2)}}</tr>
            </table>
            <br>
        </td>
    </tr>    
</table>
    
    <table style="margin-top: 20px;">
        <tr>
            <td width="70%">
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
                </p>
            </td>
            <td>
                <div style="height:50px"><img src="{{url('images','frsonny.png')}}" height="80" style=";margin-left:20"></div>
                <p style="align:center; font-size:9pt;margin-top: 0px">Fr. Manuel H. Nicholas, SDB<br>
                    Administrator</p>
            </td>
        </tr>
    </table>
  </div>
    <?php
       }
    ?>
</body>
</html>
