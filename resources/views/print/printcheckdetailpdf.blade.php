<html>
    <head>
        <style>
            .words{font-size:10pt; padding: 13px}
            .date{font-size:12pt;padding-left: 40px}
            .amount{font-size:12pt;padding-left: 30px}
            .payee{font-size:10pt;font-weight: bold}
	    body{height:100%;}
		html{margin:0px;}
            @page { 
              size: portrait; 
            }
        </style>
    </head>
    <body style="vertical-align:middle;margin-left:312.8px;margin-top:20px;">
        <table border ="0" width="576px" style="margin-top:275px;margin-bottom:auto;">
            <tr><td width="50" height="30px" colspan="3">&nbsp;</td><td  align="left" valign="bottom" ><b><span class="date">{{date('M d, Y',strtotime($date))}}</span></b></td></tr>
            <tr><td></td><td width="45px">&nbsp;</td><td height ="20" width="455px" valign="bottom"><span class="payee" style="font-size:13px">{{strtoupper($payee)}}</span></td><td valign="bottom" align="left" width="200px"><span class="amount">*{{number_format($amount,2)}}*</span></td></tr>
            <tr><td></td><td height="20" colspan="3" valign="bottom"><span class="words" style="font-size:16px">**{{$amountinwords}} Only**</span></td></tr>
        </table>
        
    </body>
</html>

