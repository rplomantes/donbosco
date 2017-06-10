<html>
    <head>
        <style>
        .header{font-size:16pt;font-weigh:bold}
        .title{font-size:16pt; font-style: italic; text-decoration: underline}
        .content td {font-size:10pt}
        .subtitle{font-size: 10pt;font-weight: bold}
	#footer { position: fixed; bottom:120px;border-top:1px solid gray;width:746px;margin-left:201px;} .pagenum:before {content: counter(page); }
        </style>
    </head>
    <body style="margin-top:80px;margin-left:201px;width:749px;margin-bottom:120px;">
	<div id="footer">Page <span class="pagenum"></span></div>    
        <table width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"><span class="title">Cash Disbursement Voucher</span></i></td></tr>
            <tr><td>Chino Roces Avenue, Makati, Metro Manila</td><td align="right">Voucher No : {{$disbursement->voucherno}}</td></tr>
            <tr><td>&nbsp;</td><td align="right">Date Issued: {{date('M d, Y', strtotime($disbursement->transactiondate))}}</td></tr>
        </table>   
        <hr />
        <table width="100%" class="content">
            <tr><td><b>Payee : </b></td><td colspan="3">{{strtoupper($disbursement->payee)}}</td></tr>
             <tr><td><b>Bank/Account No. :</b><td>{{$disbursement->bank}}</td><td align="right"><b>Check No : </b></td><td align="right">{{$disbursement->checkno}}</td></tr>    
             <tr valign="top"><td><b>Amount : </b></td><td colspan="2">{{strtoupper($amountinwords)}} </td><td align="right"><b>P {{number_format($disbursement->amount,2)}} </b></td>
             <tr><td><b>Particular :</b> </td><td colspan="3"><i>{{$disbursement->remarks}}</i></td></tr>    
        </table> 
        <hr />
        <?php
        $debit=0;$credit=0;
        ?>
        <span class="subtitle">ACCOUNTING ENTRIES</span>
        <table width="100%" border="1" cellspacing="0" class="content">
            <tr><td>Accounting Code</td><td>Accounting Title</td><td>Office</td><td align="center">Debit</td><td align="center">Credit</td></tr>
            @foreach($accountings as $accounting)
            <?php
            $debit = $debit+$accounting->debit;$credit=$credit+$accounting->credit;
            ?>
            <tr><td>{{$accounting->accountcode}}</td><td>{{$accounting->accountname}}</td><td>{{$accounting->sub_department}}</td><td align="right">{{number_format($accounting->debit,2)}}</td><td align="right">{{number_format($accounting->credit,2)}}</td></tr>
            @endforeach
            <tr><td colspan="3">Total</td><td align="right">{{number_format($debit,2)}}</td><td align="right">{{number_format($credit,2)}}</td></tr>
         </table>
        <br/>
        <table width="100%" border="1" cellspacing="0" cellpadding="5">
		<tr valign="top"><td width="25%">Prepared By:<br><br><i>{{\Auth::user()->firstname}} {{\Auth::user()->lastname}}</i></td><td width="25%">Checked By:</td><td width="25%">Approved By:</td><td width="25%">Received By:<div style="font-size: 10px;margin-top:25px;">SIGNATURE OVER PRINTED NAME</div></td></tr>
        </table>
        </body>
</html>

