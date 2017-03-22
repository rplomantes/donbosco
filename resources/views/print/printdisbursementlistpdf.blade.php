<?php
$lists = \App\Disbursement::where('transactiondate',$trandate)->where('postedby',\Auth::user()->idno)->get();
$banktotal = DB::Select("Select sum(amount) as amount, bank from disbursements where transactiondate = '$trandate' and isreverse = '0' group by bank");
$total=0;
$totalbank=0;
?>

<html>
</head>
 <style>
        .header{font-size:16pt;font-weigh:bold}
        .title{font-size:16pt; font-style: italic; text-decoration: underline}
        .content td {font-size:10pt}
        .subtitle{font-size: 10pt;font-weight: bold}
        </style>
</head>
<body>
    
 <table width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"><span class="title">Disbursement Daily Summary</span></i></td></tr>
            <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
            <tr><td colspan="2">Date: {{date('M d, Y', strtotime($trandate))}}</td></tr>
        </table>  
<hr>    
<br>

 <table border ="1"  cellspacing="0" class="content" width="100%">
     <tr><td>Voucher No</td><td>Check No</td><td>Account No</td><td>Payee</td><td>Amount</td><td>Status</td></tr>
     @foreach($lists as $list)
     <tr><td>{{$list->voucherno}}</td><td>{{$list->checkno}}</td><td>{{$list->bank}}</td><td>{{$list->payee}}</td><td align="right">{{number_format($list->amount,2)}}</td>
         <td>
         @if($list->isreverse == 0)
         OK
         @else
         Cancelled
         @endif
         </td></tr>
     <?php
     if($list->isreverse == '0'){
         $total = $total + $list->amount;
     }
     ?>
     @endforeach
     <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($total,2)}}</b></td><td>&nbsp;</td></tr>
 </table>
 <br>
 <div class="subtitle">Bank Account Summary</div>
     <table width="50%" border="1" cellspacing="0">
     <tr><td>Bank Account</td><td>Amount</td></tr>
     @foreach($banktotal as $bt)
     <tr><td>{{$bt->bank}}</td><td align="right">{{number_format($bt->amount,2)}}</td></tr>
     <?php $totalbank = $totalbank + $bt->amount; ?>
     @endforeach
     <tr><td>Total</td><td align="right"><b>{{number_format($totalbank,2)}}</b></td></tr>
     
 </table>
 <br>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
            <tr valign="top"><td width="33%">Prepared By:<br><br><i>{{\Auth::user()->firstname}} {{\Auth::user()->lastname}}</i></td><td width="33%">Checked By:</td><td>Approved By:</td></tr>
</table>   
     
</body>
</html>

