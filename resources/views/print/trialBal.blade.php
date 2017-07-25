<?php use App\Http\Controllers\Accounting\Helper; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       
        
    <style>
        .header{font-size:16pt;font-weigh:bold}
        body{margin-top: 110px}
        #header { position: fixed; left: 0px; top: -10px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
        #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
        .title{font-size:16pt; font-style: italic; text-decoration: underline}
        .content td {font-size:10pt}
        .subtitle{font-size: 10pt;font-weight: bold}
        #maintable td,#maintable th { border:1px solid}
    </style>
	<!-- Fonts -->
	
        </head>
    <body> 
    <div id="footer">Page <span class="pagenum"></span></div>    
    <div  id="header">
        <table  width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"></i></td></tr>
            <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
                    <tr><td colspan="4" align="center">
                <span class="title">Trial Balance</span>
                </td></tr>
            <tr><td colspan="2" align="center">{{date('M d, Y', strtotime($fromtran))}} to {{date('M d, Y', strtotime($totran))}} </td></tr>
        </table> 
    <hr>
    </div>
        <table width="100%" border="1" cellspacing="0">

            <thead><tr><th>Acct No.</th><th>Account Title</th><th>Amount</th></tr></thead>
            <tbody>
            @foreach($trials as $trial)
            <tr>
                <td>{{$trial->accountingcode}}</td>
                <td>{{$trial->accountname}}</td>
                <td style="text-align: right">
                    <?php $accttotal = Helper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode);?>
                    @if($accttotal < 0)
                    ({{number_format(abs($accttotal),2)}})
                    @else
                    {{number_format($accttotal,2)}}
                    @endif
                </td>
            </tr>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format(Helper::allaccttotal($trials),2)}}</td></tr>
            </tbody>
        </table>
    </body>
</html>