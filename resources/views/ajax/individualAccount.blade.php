<table class="table table-stripped">
    <thead>
        <tr>
            <td>Tran. Date</td>
            <td>Reference No</td>
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
            <td>{{$account->transactiondate}}</td>
            <td>{{$account->receiptno}}</td>
            <td>{{$account->debit}}</td>
            <td>{{$account->credit}}</td>
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
            <td width="45%">{{$remark}}</td>
        </tr>
        @endforeach
    </tbody>
</table>