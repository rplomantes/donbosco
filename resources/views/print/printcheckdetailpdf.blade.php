<html>
    <head>
        <style>
            .words{font-size:10pt; padding: 13px}
            .date{font-size:10pt;padding-left: 40px}
            .amount{font-size:10pt;padding-left: 30px}
            .payee{font-size:10pt;font-weight: bold}
        </style>
    </head>    
    <body>
        <table border ="0" width="95%">
            <tr><td width="50" colspan="3">&nbsp;</td><td  align="left"><span class="date">{{date('M d, Y',strtotime($date))}}</span></td></tr>
            <tr><td></td><td></td><td height ="20" width="340" valign="bottom"><span class="payee">{{strtoupper($payee)}}</span></td><td valign="bottom" align="left"><span class="amount">*{{number_format($amount,2)}}*</span></td></tr>
            <tr><td></td><td height="20" colspan="3" valign="bottom"><span class="words">**{{$amountinwords}} Only**</span></td></tr>
        </table>
        
    </body>
</html>

