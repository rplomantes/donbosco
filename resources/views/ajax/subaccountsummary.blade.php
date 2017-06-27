<?php
use App\Http\Controllers\Accounting\SubAccountSummarryController;
$totalamount = 0;
?>
@foreach($subaccounts as $subaccount)
    <?php
    $accounttotal = 0;
    ?>
    @if(SubAccountSummarryController::isvisible($accounts,$subaccount)>0)
        <h4>{{$subaccount}}</h4>
        <table class="table table-borderless">
            <thead>
                <th>Transaction Date</th>
                <th>Reference No.</th>
                <th>Sub Account</th>
                <th>Entry</th>
                <th>Amount</th>
            </thead>
            @foreach($accounts as $account)
                @if(strcmp($account->description,$subaccount) == 0)
                <?php $total = SubAccountSummarryController::getaccttotal($account->credit,$account->debit,$account->accountingcode);?>
                <tr>
                    <td>{{$account->transactiondate}}</td>
                    <td>{{$account->receiptno}}</td>
                    <td>{{$account->description}}</td>
                    <td>{{SubAccountSummarryController::getEntrytype($account->entry_type)}}</td>
                    <td>{{number_format($total,2)}}</td>
                </tr>
                <?php
                $totalamount = $totalamount + $total;
                $accounttotal = $accounttotal + $total;
                ?>
                @endif
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td>{{number_format($accounttotal,2)}}</td>
            </tr>
        </table>
    @endif
@endforeach

@if(SubAccountSummarryController::notinlist($accounts,$subaccounts) > 0)
    <?php
    $accounttotal = 0;
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
                <td>{{number_format($total,2)}}</td>
            </tr>
            <?php
            $totalamount = $totalamount + $total;
            $accounttotal = $accounttotal + $total;
            ?>
            @endif
        @endforeach
        <tr>
            <td colspan="4"></td>
            <td>{{number_format($accounttotal,2)}}</td>
        </tr>
    </table>
@endif