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
                <th>Amount</th>
            </thead>
            @foreach($accounts as $account)
                @if(strcmp($account->description,$subaccount) == 0)
                <tr>
                    <td>{{$account->transactiondate}}</td>
                    <td>{{$account->receiptno}}</td>
                    <td>{{$account->description}}</td>
                    <td>{{$account->totalamount}}</td>
                </tr>
                <?php
                $totalamount = $totalamount + $account->totalamount;
                $accounttotal = $accounttotal + $account->totalamount;
                ?>
                @endif
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td>{{$accounttotal}}</td>
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
            <th>Amount</th>
        </thead>
        @foreach($accounts as $account)
            @if(!in_array($account->description,$subaccounts))
            <tr>
                <td>{{$account->transactiondate}}</td>
                <td>{{$account->receiptno}}</td>
                <td>{{$account->description}}</td>
                <td>{{$account->totalamount}}</td>
            </tr>
            <?php
            $totalamount = $totalamount + $account->totalamount;
            $accounttotal = $accounttotal + $account->totalamount;
            ?>
            @endif
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td>{{$accounttotal}}</td>
        </tr>
    </table>
@endif