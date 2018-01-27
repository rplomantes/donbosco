<?php
ini_set('max_execution_time', 0);

$curr = $records->where('isreverse',0);

$total= $curr->where('transactiondate',$transactiondate);


$forwaded = $curr->filter(function($item) use($transactiondate){
        return data_get($item, 'transactiondate') !== $transactiondate;
        });
$receipts= $records->where('transactiondate',$transactiondate);
?>
<div style="page-break-after: always;border: 1px solid">
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>Receipt No.</th>
                <th>Name</th>
                <th>Debit<br>Cash / Check</th>
                <th>Debit<br>Discount</th>
                <th>Debit<br>FAPE</th>
                <th>Debit<br>Reservation</th>
                <th>Debit<br>Deposit</th>
                <th>E - Learning</th>
                <th>Misc</th>
                <th>Book</th>
                <th>Department<br> Facilities</th>
                <th>Registration Fee</th>
                <th>Tuition Fee</th>
                <th>Credit<br>Reservation</th>
                <th>Credit<br>Sundry</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">Balance brought forward</td>
                <td>{{number_format($forwaded->sum('cash'),2)}}</td>
                <td>{{number_format($forwaded->sum('discount'),2)}}</td>
                <td>{{number_format($forwaded->sum('fape'),2)}}</td>
                <td>{{number_format($forwaded->sum('dreservation'),2)}}</td>
                <td>{{number_format($forwaded->sum('deposit'),2)}}</td>
                
                <td>{{number_format($forwaded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwaded->sum('misc'),2)}}</td>
                <td>{{number_format($forwaded->sum('book'),2)}}</td>
                <td>{{number_format($forwaded->sum('dept'),2)}}</td>
                <td>{{number_format($forwaded->sum('registration'),2)}}</td>
                <td>{{number_format($forwaded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwaded->sum('creservation'),2)}}</td>
                <td>{{number_format($forwaded->sum('csundry'),2)}}</td>
                <td></td>
            </tr>
            @foreach($receipts as $receipt)
            <tr>
                <td>{{$receipt->receiptno}}</td>
                <td>{{$receipt->from}}</td>
                <td>{{number_format($receipt->cash,2)}}</td>
                <td>{{number_format($receipt->discount,2)}}</td>
                <td>{{number_format($receipt->fape,2)}}</td>
                <td>{{number_format($receipt->dreservation,2)}}</td>
                <td>{{number_format($receipt->deposit,2)}}</td>
                
                <td>{{number_format($receipt->elearning,2)}}</td>
                <td>{{number_format($receipt->misc,2)}}</td>
                <td>{{number_format($receipt->book,2)}}</td>
                <td>{{number_format($receipt->dept,2)}}</td>
                <td>{{number_format($receipt->registration,2)}}</td>
                <td>{{number_format($receipt->tuition,2)}}</td>
                <td>{{number_format($receipt->creservation,2)}}</td>
                <td>{{number_format($receipt->csundry,2)}}</td>
                <td>
			@if($receipt->isreverse == 0)
			OK
			@else
			Cancelled
			@endif
		</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td>{{number_format($total->sum('cash'),2)}}</td>
                <td>{{number_format($total->sum('discount'),2)}}</td>
                <td>{{number_format($total->sum('fape'),2)}}</td>
                <td>{{number_format($total->sum('dreservation'),2)}}</td>
                <td>{{number_format($total->sum('deposit'),2)}}</td>
                
                <td>{{number_format($total->sum('elearning'),2)}}</td>
                <td>{{number_format($total->sum('misc'),2)}}</td>
                <td>{{number_format($total->sum('book'),2)}}</td>
                <td>{{number_format($total->sum('dept'),2)}}</td>
                <td>{{number_format($total->sum('registration'),2)}}</td>
                <td>{{number_format($total->sum('tuition'),2)}}</td>
                <td>{{number_format($total->sum('creservation'),2)}}</td>
                <td>{{number_format($total->sum('csundry'),2)}}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan='16'><br></td>
            </tr>
            <tr>
                <td colspan="2">Current Balance</td>
                <td>{{number_format($curr->sum('cash'),2)}}</td>
                <td>{{number_format($curr->sum('discount'),2)}}</td>
                <td>{{number_format($curr->sum('fape'),2)}}</td>
                <td>{{number_format($curr->sum('dreservation'),2)}}</td>
                <td>{{number_format($curr->sum('deposit'),2)}}</td>
                
                <td>{{number_format($curr->sum('elearning'),2)}}</td>
                <td>{{number_format($curr->sum('misc'),2)}}</td>
                <td>{{number_format($curr->sum('book'),2)}}</td>
                <td>{{number_format($curr->sum('dept'),2)}}</td>
                <td>{{number_format($curr->sum('registration'),2)}}</td>
                <td>{{number_format($curr->sum('tuition'),2)}}</td>
                <td>{{number_format($curr->sum('creservation'),2)}}</td>
                <td>{{number_format($curr->sum('csundry'),2)}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>