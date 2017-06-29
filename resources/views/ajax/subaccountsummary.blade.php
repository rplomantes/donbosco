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
                <th>Transaction Date</th>
                <th>Reference No.</th>
                <th>Sub Account</th>
                <th>Entry</th>
                <th>Debit</th>
                <th>Credit</th>
            </thead>
            @foreach($accounts as $account)
                @if(strcmp($account->description,$subaccount) == 0)
                <?php $total = SubAccountSummarryController::getaccttotal($account->credit,$account->debit,$account->accountingcode);?>
                <tr>
                    <td>{{$account->transactiondate}}</td>
                    <td>{{$account->receiptno}}</td>
                    <td>{{$account->description}}</td>
                    <td>{{SubAccountSummarryController::getEntrytype($account->entry_type)}}</td>
                    <td align="right">{{number_format($account->debit,2)}}</td>
                    <td align="right">{{number_format($account->credit,2)}}</td>
                </tr>
                <?php
                $totalamount = $totalamount + $total;
                $accounttotal = $accounttotal + $total;
                
                $debitaccounttotal = $debitaccounttotal + $account->debit;
                $creditaccounttotal = $creditaccounttotal + $account->credit;
                
                $debittotalamount = $debittotalamount + $account->debit;
                $credittotalamount = $credittotalamount + $account->credit;
                
                ?>
                @endif
            @endforeach
            <tr style="background-color:#8fb461">
                <td colspan="4" align="left"><b>Sub Total</b></td>
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
            <th>Transaction Date</th>
            <th>Reference No.</th>
            <th>Sub Account</th>
            <th>Entry</th>
            <th>Amount</th>
        </thead>
        @foreach($accounts as $account)
            @if(!in_array($account->description,$subaccounts))
            <?php $total = SubAccountSummarryController::getaccttotal($account->credit,$account->debit,$account->accountingcode);?>
                <tr>
                    <td>{{$account->transactiondate}}</td>
                    <td>{{$account->receiptno}}</td>
                    <td>{{$account->description}}</td>
                    <td>{{SubAccountSummarryController::getEntrytype($account->entry_type)}}</td>
                    <td align="right">{{number_format($account->debit,2)}}</td>
                    <td align="right">{{number_format($account->credit,2)}}</td>
                </tr>
                <?php
                $totalamount = $totalamount + $total;
                $accounttotal = $accounttotal + $total;
                
                $debitaccounttotal = $debitaccounttotal + $account->debit;
                $creditaccounttotal = $creditaccounttotal + $account->credit;
                
                $debittotalamount = $debittotalamount + $account->debit;
                $credittotalamount = $credittotalamount + $account->credit;
                
                ?>
            @endif
        @endforeach
        <tr style="background-color:#8fb461">
            <td colspan="4" align="left"><b>Sub Total</b></td>
            <td align="right">{{number_format($debitaccounttotal,2)}}</td>
            <td align="right">{{number_format($creditaccounttotal,2)}}</td>
        </tr>
    </table>

    <table class="table table-borderless">
        <tr align="left">
            <td colspan="4" align="left">
                <b>Grand Total</b>
            </td>
            <td align="right">{{number_format($debittotalamount,2)}}</td>
            <td align="right">{{number_format($credittotalamount,2)}}</td>
        </tr>
        
    </table>

    
@endif