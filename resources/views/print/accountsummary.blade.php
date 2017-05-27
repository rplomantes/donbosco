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
<?php
use App\Http\Controllers\AjaxController;


?>
 <table  width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"></i></td></tr>
            <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
                    <tr><td colspan="4" align="center">
                <span class="title">Individual Account Summary</span>
                </td></tr>
            <tr><td colspan="2" align="center">{{date('M d, Y', strtotime($fromdate))}} to {{date('M d, Y', strtotime($todate))}} </td></tr>
 </table>
    <hr>
    <div><b>{{AjaxController::getaccountname($account)}}</b></div>
    <div><b>{{$account}}</b></div>
        <table border = "1" cellspacing = "0" width="100%">
            <thead>
                <tr style="text-align: center">
                    <td>Tran. Date</td>
                    <td>Ref. No</td>
                    <td>Debit</td>
                    <td>Credit</td>
                    <td>Entry</td>
                    <td>Particular</td>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <?php 
                if($account->entry_type == 4){   
                    $disremark  = \App\Disbursement::where('refno',$account->refno)->first();
                    $remark = $disremark->remarks;
                }else{
                    $elseremark  = \App\Dedit::where('refno',$account->refno)->first();
                    $remark = $elseremark->remarks;
                }
                ?>

                <tr>
                    <td width="12%">{{$account->transactiondate}}</td>
                    <td width="12%">{{$account->receiptno}}</td>
                    <td width="13%">{{number_format($account->debit,2,' .',',')}}</td>
                    <td width="13%">{{number_format($account->credit,2,' .',',')}}</td>
                    <td width="14%">
                        @if($account->entry_type == 1)
                        Cash Receipt
                        @elseif($account->entry_type == 2)
                        Debit Memo
                        @elseif($account->entry_type == 3)
                        General Journal
                        @elseif($account->entry_type == 4)
                        Disbursement
                        @elseif($account->entry_type == 5)
                        System Generated
                        @endif
                    </td>
                    <td>{{$remark}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
    </html>