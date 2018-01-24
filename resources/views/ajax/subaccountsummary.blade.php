<?php
use App\Http\Controllers\Accounting\SubAccountSummarryController;
$totalamount = 0;
$debittotalamount = 0;
$credittotalamount = 0;

?>
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
@endforeach

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
        <tr align="left">
            <td colspan="5" align="left">
                <b>Grand Total</b>
            </td>
            <td align="right">{{number_format($debittotalamount,2)}}</td>
            <td align="right">{{number_format($credittotalamount,2)}}</td>
        </tr>
        
    </table>

<a href="{{url('printsubaccountsummary',array($fromdate,$todate,$acct))}}" class="col-md-12 btn btn-danger">Print</a>
@endif