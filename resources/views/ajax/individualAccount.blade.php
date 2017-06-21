<?php 
$tdebit = 0;
$tcredit = 0;
?>
<table class="table table-stripped">
    <thead>
        <tr>
            <td>Tran. Date</td>
            <td>Reference No</td>
            <td>Name</td>
            <td>Debit</td>
            <td>Credit</td>
            <td>Entry</td>
            <td>Department</td>
            <td>Office</td>
            <td>Particular</td>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
        <?php 
        if($account->entry_type == 4){   
            $disremark  = \App\Disbursement::where('refno',$account->refno)->first();
            $remark = $disremark->remarks;
            $payee = $disremark->payee;
        }else{
            $elseremark  = \App\Dedit::where('refno',$account->refno)->first();
            $remark =$elseremark->remarks;
            $payee = $elseremark->receivefrom;
        }
        ?>

        <tr>
            <td>{{$account->transactiondate}}</td>
            <td>{{$account->receiptno}}</td>
            <td>{{$payee}}</td>
            <td>{{number_format($account->debit,2)}}</td>
            <td>{{number_format($account->credit,2)}}</td>
            <?php
                $tdebit = $tdebit+ $account->debit;
                $tcredit = $tcredit+ $account->credit;
            ?>
            <td>
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
            <td width="5%">{{$account->acct_department}}</td>
            <td width="5%">{{$account->sub_department}}</td>
            <td width="35%">{{$remark}}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3">Amount</td>
            <td >{{number_format($tdebit,2)}}</td>
            <td >{{number_format($tcredit,2)}}</td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
        </tr>
    </tbody>
</table>
