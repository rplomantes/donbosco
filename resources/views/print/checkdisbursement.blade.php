
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
<?php 
    $index = 0;
    $total = 0;
?>
<body>
    
 <table  width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"></i></td></tr>
            <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
                    <tr><td colspan="4" align="center">
                <span class="title">Check Disbursement Summary</span>
                </td></tr>
            <tr><td colspan="2" align="center">{{date('M d, Y', strtotime($from))}} to {{date('M d, Y', strtotime($to))}} </td></tr>
 </table>

    
    <hr>
   
    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 13px">
        <thead>
            <tr >
                <td width="5px"></td>
                <td width="90px"><b>VOUCHER<br>NO.</b></td>
                <td><b>TRANS. DATE</b></td>
                <td width="120px"><b>BANK-CHECK #</b></td>
                <td width="200px"><b>PAYEE</b></td>
                <td style="text-align: center"><b>RECONCILED AMOUNT</b></td>
            </tr>
        </thead>
        @foreach($accounts as $account)
            <?php 
                $vouchers = \App\Disbursement::where('bank',$account->bank)->where('isreverse',0)->whereBetween('transactiondate', array($from, $to))->orderBy('checkno','ASC')->get();
                $subamount = 0;
            ?>
            @if(count($vouchers)>0)
                @foreach($vouchers as $voucher)
                    <?php $index++; ?>
                    <tr>
                        <td width="40px">{{$index}}</td>
                        <td>{{$voucher->voucherno}}</td>
                        <td>{{$voucher->transactiondate}}</td>
                        <td>{{$voucher->checkno}}</td>
                        <td>{{$voucher->payee}}</td>
                        <td style="text-align: right">{{number_format($voucher->amount,2,'.',',')}}</td>
                    </tr>
                    <?php 
                        $subamount = $subamount+$voucher->amount; 
                        $total = $total+$voucher->amount;
                    ?>
                @endforeach
            @else
                    <tr>
                        <td></td>
                        <td colspan="5">None</td>

                    </tr>
            @endif
            <tr>
                <td style="text-align: center;padding-top: 10px;padding-bottom: 10px" colspan="2"><b>{{count($vouchers)}}</b></td>
                <td style="text-align: center" colspan="3"><b>{{$account->bank}}</b></td>
                <td style="text-align: right"><b>{{number_format($subamount,2,'.',',')}}</b></td>
            </tr>
        @endforeach
        

        <tr>
            <td colspan="5" style="text-align: right"><b>Grand Total</b></td>
            <td style="text-align: right"><b>{{number_format($total,2,'.',',')}}</b></td>
        </tr>
    </table>
    </body>
    </html>