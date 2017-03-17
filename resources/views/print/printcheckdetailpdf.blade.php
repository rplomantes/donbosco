<html>
    <head>
        <style>
            .words{font-size:12pt;}
            .date{font-size:10pt;}
            .payee{font-size:10pt;font-weight: bold}
        </style>
    </head>    
    <body>
        <table border ="0">
            <tr><td width="50">&nbsp;</td><td></td><td  align="center"><span class="date">{{date('M d, Y',strtotime($date))}}</span></td></tr>
            <tr><td></td><td height ="20" width="340" valign="bottom"><span class="payee">{{strtoupper($payee)}}</span></td><td valign="bottom" align="center"><span id="amount">{{number_format($amount,2)}}</span></td></tr>
            <tr><td></td><td height="30" colspan="2" valign="bottom"><span class="words">{{strtoupper($amountinwords)}} PESOS</span></td></tr>
        </table>
        
    </body>
</html>

