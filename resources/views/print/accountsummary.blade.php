<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       
        
<style>
        .header{font-size:14pt;font-weigh:bold}
        .title{font-size:14pt; font-style: italic; text-decoration: underline}
        .content td {font-size:11pt}
        .subtitle{font-size: 11pt;font-weight: bold}
        html body{
            margin: -20px;
            padding: 0px;
        }
        </style>
</head>
<body>
<?php
use App\Http\Controllers\AjaxController;

$tdebit = 0;
$tcredit = 0;
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
        <table border = "1" cellspacing = "0" width="100%" style="font-size: 11pt">
            <thead>
                <tr style="text-align: center">
                    <td>Tran. Date</td>
                    <td>Ref. No</td>
                    <td>Name</td>
                    <td>Debit</td>
                    <td>Credit</td>
                    <td>Entry</td>
                    <td>Department</td>
                    <td>Office</td>
                    <td>Remarks</td>
                </tr>
            </thead>
            <tbody style="font-size: 10pt">
                @foreach($accounts as $account)
                <?php 
                $remark = "";
                $payee = "";
                if($account->entry_type == 4){   
                    $disremark  = \App\Disbursement::where('refno',$account->refno)->first();
                    if(count($disremark)>0){
                        $remark = $disremark->remarks;
                        $payee = $disremark->payee;
                    }
                }else{
                    $elseremark  = \App\Dedit::where('refno',$account->refno)->first();
                    if(count($elseremark)>0){
                        $remark =$elseremark->remarks;
                        $payee = $elseremark->receivefrom;   
                    }
                }
                ?>

                <tr>
                    <td width="6.5%" align="center">{{$account->transactiondate}}</td>
                    <td width="6%" align="center">{{$account->receiptno}}</td>
                    <td width="15%">{{$payee}}</td>
                    <td width="7%" align="right">{{number_format($account->debit,2,' .',',')}}</td>
                    <td width="7%" align="right">{{number_format($account->credit,2,' .',',')}}</td>
                    <?php
                        $tdebit = $tdebit+ $account->debit;
                        $tcredit = $tcredit+ $account->credit;
                    ?>
                    <td width="8%" align="center">
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
                    <td width="8%">{{$account->acct_department}}</td>
                    <td width="8%">{{$account->sub_department}}</td>
                    <td width="17%">{{$remark}}</td>
                    
                </tr>
                @endforeach
        <tr>
            <td colspan="3">Amount</td>
            <td align="right">{{number_format($tdebit,2,' .',',')}}</td>
            <td align="right">{{number_format($tcredit,2,' .',',')}}</td>
            <td colspan="4"></td>
        </tr>
            </tbody>
        </table>
    </body>
    </html>

