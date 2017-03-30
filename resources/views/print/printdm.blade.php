<?php
$journal =  \App\DebitMemo::where('refno',$refno)->first();
$accountings = \App\Accounting::where('refno',$refno)->get();
$totaldebit=0;
$totalcredit=0;
$iscancel = 0;
if($journal->isreverse =="1"){
    $iscancel = 1;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
        <style>
            #header{font-size: 16pt; font-weight: bold}
            #title{font-size:16pt; font-style: italic;text-decoration: underline}
        </style>       
        
</head>
<body> 
 <table width="100%">
   <tr><td colspan="2"><span id="header">Don Bosco Technical School - Makati</span></td><td align="right"><span id="title">Debit Memo Voucher</span></td></tr>
   <tr><td colspan="2">{{date('M d, Y', strtotime($journal->transactiondate))}}</td><td align="right">DM Number : {{$journal->voucherno}}</td></tr>
   <tr><td colspan="3">Student No : {{$journal->idno}}</td></tr>
   <tr><td colspan="3">Student Name : <b>{{$journal->fullname}}</b></td></tr>
   <tr><td colspan="3">Amount : {{number_format($journal->amount,2)}}</td></tr>
 </table>  
   <table width="100%" border="1" cellspacing="0">
   <tr><th>Accounting Code</th><th>Accounting Title</th><th>Subsidiary</th><th>Office</th><th>Debit</th><th>Credit</th></tr> 
    @foreach($accountings as $accounting)
    <tr><td>{{$accounting->accountcode}}</td><td>{{$accounting->accountname}}</td><td>{{$accounting->subsidiary}}</td><td>{{$accounting->sub_department}}</td><td align="right">{{number_format($accounting->debit,2)}}</td><td align="right">{{number_format($accounting->credit,2)}}</td></tr>
    <?php
    $totaldebit = $totaldebit + $accounting->debit;
    $totalcredit = $totalcredit + $accounting->credit;
    ?>
    @endforeach
    <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
    </table>
    <table border="1" cellspacing="0" cellpadding="2" width="100%"><tr><td><i>Explanation :</i><br>{{$journal->remarks}}</td></tr></table>
    <br><br>
    <table width="100%" border="1">
        <tr><td>Prepared By:<br><br>{{strtoupper(\App\User::where('idno',$journal->postedby)->first()->lastname)}}, {{strtoupper(\App\User::where('idno',$journal->postedby)->first()->firstname)}}</td><td valign="top">Checked By:</td><td valign="top">Approved By </td></tr>
    </table>    
</body>
</html>