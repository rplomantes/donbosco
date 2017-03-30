
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
        .title{font-size:16pt; font-style: italic; text-decoration: underline}
        .content td {font-size:10pt}
        .subtitle{font-size: 10pt;font-weight: bold}
        </style>
</head>
<body>
    
 <table  width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"></i></td></tr>
            <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
                    <tr><td colspan="4" align="center">
                <span class="title">
                        @if($entry == 1)
    Cash Receipt Debit/Credit Summary Report
    @elseif($entry == 2)
     Debit Memo Debit/Credit Summary Report
    @elseif($entry == 3)
     General Journal Debit/Credit Summary Report
    @elseif($entry == 4)
    Cash Disbursement Debit/Credit Summary Report
    @endif</span>
                </td></tr>
            <tr><td colspan="2" align="center">{{date('M d, Y', strtotime($fromtran))}} to {{date('M d, Y', strtotime($totran))}} </td></tr>
 </table>

    
    <hr>
   
        <table border = "1" cellspacing = "0" width="100%">
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            ?>
            <thead><tr><th>Acct No.</th><th>Account Title</th><th>Debit</th><th>Credit</th></tr></thead>
            @foreach($trials as $trial)
            <?php 
            $totaldebit = $totaldebit + $trial->debit;
            $totalcredit = $totalcredit + $trial->credits;
            ?>        
            <tr><td>{{$trial->accountingcode}}</td><td>{{$trial->accountname}}</td><td style="text-align: right">
                    @if($trial->debit > 0)
                    {{number_format($trial->debit, 2, '.', ', ')}}
                    @endif
                </td><td style="text-align: right">
                    @if($trial->credits > 0)
                    {{number_format($trial->credits, 2, '.', ', ')}}
                    @endif
                </td></tr>
            @endforeach
            <tr><td colspan="2" style="text-align: center"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>
    </body>
    </html>