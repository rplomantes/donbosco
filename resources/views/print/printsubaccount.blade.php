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
        .content td {font-size:10pt}
        .subtitle{font-size: 10pt;font-weight: bold}
        </style>
    <style media="print">    
    @page { margin:10px;padding:0px;margin-top: 100px;margin-bottom: 30px;}
    #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
    #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); } 
    </style>
</head>
<body>
<?php
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Accounting\SubAccountSummarryController;

$totalamount = 0;
$debittotalamount = 0;
$credittotalamount = 0;
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
@foreach($subaccounts as $subaccount)
    <?php
    $accounttotal = 0;
    $debitaccounttotal = 0;
    $creditaccounttotal = 0;
    ?>
    @if(SubAccountSummarryController::isvisible($accounts,$subaccount)>0)
        <h4>{{$subaccount}}</h4>
        <table class="table table-borderless">
            <thead>
                <th width="150px">Transaction Date</th>
                <th width="150px">Reference No.</th>
                <th width="320px">Name</th>
                <th width="150px">Sub Account</th>
                <th width="150px">Entry</th>
                <th width="150px">Debit</th>
                <th width="150px">Credit</th>
            </thead>
            @foreach($accounts as $account)
                @if(strcmp($account->description,$subaccount) == 0)
                    @if($account->debit != 0 || $account->credit != 0)
                        <?php 
                        $payee = "";
                        if($account->entry_type == 4){   
                            $disremark  = \App\Disbursement::where('refno',$account->refno)->first();
                            if(count($disremark)>0){
                                $payee = $disremark->payee;
                            }
                        }else{
                            $elseremark  = \App\Dedit::where('refno',$account->refno)->first();
                            if(count($elseremark)>0){
                                $payee = $elseremark->receivefrom;   
                            }
                        }
                        ?>
                        <tr>
                            <td>{{$account->transactiondate}}</td>
                            <td>{{$account->receiptno}}</td>
                            <td>{{$payee}}</td>
                            <td>{{$account->description}}</td>
                            <td>{{SubAccountSummarryController::getEntrytype($account->entry_type)}}</td>
                            <td align="right">{{number_format($account->debit,2)}}</td>
                            <td align="right">{{number_format($account->credit,2)}}</td>
                        </tr>
                        <?php


                        $debitaccounttotal = $debitaccounttotal + $account->debit;
                        $creditaccounttotal = $creditaccounttotal + $account->credit;

                        $debittotalamount = $debittotalamount + $account->debit;
                        $credittotalamount = $credittotalamount + $account->credit;

                        ?>
                    @endif
                @endif
            @endforeach
            <tr style="background-color:#8fb461">
                <td colspan="5" align="left"><b>Sub Total</b></td>
                <td align="right">{{number_format($debitaccounttotal,2)}}</td>
                <td align="right">{{number_format($creditaccounttotal,2)}}</td>
            </tr>
        </table>
    @endif
    <table class="table table-borderless">
        <tr><td colspan="7"></td></tr>
    </table>
@endforeach
<table class="table table-borderless">
    <tr><td colspan="7"></td></tr>
</table>
@if(SubAccountSummarryController::notinlist($accounts,$subaccounts) > 0)
    <?php
    $accounttotal = 0;
    $debitaccounttotal = 0;
    $creditaccounttotal = 0;
    ?>
    <table class="table table-borderless">
        <thead>
            <th width="150px">Transaction Date</th>
            <th width="150px">Reference No.</th>
            <th width="320px">Name</th>
            <th width="150px">Sub Account</th>
            <th width="150px">Entry</th>
            <th width="150px">Debit</th>
            <th width="150px">Credit</th>
        </thead>
        @foreach($accounts as $account)
            @if(!in_array($account->description,$subaccounts))
            <?php 
                $payee = "";
                if($account->entry_type == 4){   
                    $disremark  = \App\Disbursement::where('refno',$account->refno)->first();
                    if(count($disremark)>0){
                        $payee = $disremark->payee;
                    }
                }else{
                    $elseremark  = \App\Dedit::where('refno',$account->refno)->first();
                    if(count($elseremark)>0){
                        $payee = $elseremark->receivefrom;   
                    }
                }
            ?>
                <tr>
                    <td>{{$account->transactiondate}}</td>
                    <td>{{$account->receiptno}}</td>
                    <td>{{$payee}}</td>
                    <td>{{$account->description}}</td>
                    <td>{{SubAccountSummarryController::getEntrytype($account->entry_type)}}</td>
                    <td align="right">{{number_format($account->debit,2)}}</td>
                    <td align="right">{{number_format($account->credit,2)}}</td>
                </tr>
                <?php
                
                $debitaccounttotal = $debitaccounttotal + $account->debit;
                $creditaccounttotal = $creditaccounttotal + $account->credit;
                
                $debittotalamount = $debittotalamount + $account->debit;
                $credittotalamount = $credittotalamount + $account->credit;
                
                ?>
            @endif
        @endforeach
        <tr style="background-color:#8fb461">
            <td colspan="5" align="left"><b>Sub Total</b></td>
            <td align="right">{{number_format($debitaccounttotal,2)}}</td>
            <td align="right">{{number_format($creditaccounttotal,2)}}</td>
        </tr>
    </table>
    <table class="table table-borderless">
        <tr><td colspan="7"></td></tr>
    </table>
    <table class="table table-borderless">
        <tr align="left">
            <td colspan="5" align="left">
                <b>Grand Total</b>
            </td>
            <td align="right">{{number_format($debittotalamount,2)}}</td>
            <td align="right">{{number_format($credittotalamount,2)}}</td>
        </tr>
        
    </table>

@endif
    </body>
    </html>
