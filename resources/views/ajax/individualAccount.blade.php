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
        $remark = $account->remarks;
        
        if($account->entry_type == 4){   
            $disremark  = \App\Disbursement::where('refno',$account->refno)->first();
            $remark = $disremark->remarks;
            
        }
        ?>

        <tr>
            <td>{{$account->transactiondate}}</td>
            <td>{{$account->receiptno}}</td>
            <td>{{$account->debit}}</td>
            <td>{{$account->credit}}</td>
            <td>{{$account->entry_type}}</td>
            <td>{{$remark}}</td>
        </tr>
        @endforeach
    </tbody>
</table>