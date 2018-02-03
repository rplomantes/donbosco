<?php
$forwarded = $records->where('refno','forwarded');
        
$receipts = $records->where('transactiondate',$transactiondate);
$subtotal = $receipts->where('isreverse',0);

$grand_total = $records->where('isreverse',0);
?>
<style>
    td{
        border:1px solid #000000;
    }
</style>
<table class='table table-bordered'>
    <thead>
        <tr><td colspan="14">CASH RECEIPT BOOK - {{$transactiondate}}</td></tr>
        <tr>
            <th>Receipt No.</th>
            <th>Name</th>
            <th>Debit<br>Cash / Check</th>
            <th>Debit<br>Discount</th>
            <th>Debit<br>Sundry</th>
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
        <tr style='text-align: right'>
            <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
            <td>{{$forwarded->sum('cash')}}</td>
            <td>{{$forwarded->sum('discount')}}</td>
            <td>{{$forwarded->sum('dsundry')}}</td>

            <td>{{$forwarded->sum('elearning')}}</td>
            <td>{{$forwarded->sum('misc')}}</td>
            <td>{{$forwarded->sum('book')}}</td>
            <td>{{$forwarded->sum('dept')}}</td>
            <td>{{$forwarded->sum('registration')}}</td>
            <td>{{$forwarded->sum('tuition')}}</td>
            <td>{{$forwarded->sum('creservation')}}</td>
            <td>{{$forwarded->sum('csundry')}}</td>
            <td></td>
        </tr>
        @foreach($receipts as $receipt)
        <tr style='text-align: right'>
            <td style='text-align: left'>{{$receipt->receiptno}}</td>
            <td style='text-align: left'>{{$receipt->from}}</td>

            <td>{{$receipt->cash}}</td>
            <td>{{$receipt->discount}}</td>
            <td style="white-space: nowrap">{!!rtrim($receipt->dsundry_account,"<br>")!!}</td>


            <td>{{$receipt->elearning}}</td>
            <td>{{$receipt->misc}}</td>
            <td>{{$receipt->book}}</td>
            <td>{{$receipt->dept}}</td>
            <td>{{$receipt->registration}}</td>
            <td>{{$receipt->tuition}}</td>
            <td>{{$receipt->creservation}}</td>
            <td style="white-space: nowrap">{!!rtrim($receipt->csundry_account,"<br>")!!}</td>
            <td>
                @if($receipt->isreverse == 0)
                OK
                @else
                Cancelled
                @endif
            </td>
        </tr>
        @endforeach
        <tr style='text-align: right'>
            <td colspan="2" style='text-align: left'>Total</td>
            <td>{{$subtotal->sum('cash')}}</td>
            <td>{{$subtotal->sum('discount')}}</td>
            <td>{{$subtotal->sum('dsundry')}}</td>

            <td>{{$subtotal->sum('elearning')}}</td>
            <td>{{$subtotal->sum('misc')}}</td>
            <td>{{$subtotal->sum('book')}}</td>
            <td>{{$subtotal->sum('dept')}}</td>
            <td>{{$subtotal->sum('registration')}}</td>
            <td>{{$subtotal->sum('tuition')}}</td>
            <td>{{$subtotal->sum('creservation')}}</td>
            <td>{{$subtotal->sum('csundry')}}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan='14'><br></td>
        </tr>
        <tr style='text-align: right'>
            <td colspan="2" style='text-align: left'>Current Balance</td>
            <td>{{$grand_total->sum('cash')}}</td>
            <td>{{$grand_total->sum('discount')}}</td>
            <td>{{$grand_total->sum('dsundry')}}</td>

            <td>{{$grand_total->sum('elearning')}}</td>
            <td>{{$grand_total->sum('misc')}}</td>
            <td>{{$grand_total->sum('book')}}</td>
            <td>{{$grand_total->sum('dept')}}</td>
            <td>{{$grand_total->sum('registration')}}</td>
            <td>{{$grand_total->sum('tuition')}}</td>
            <td>{{$grand_total->sum('creservation')}}</td>
            <td>{{$grand_total->sum('csundry')}}</td>
            <td></td>
        </tr>
    </tbody>
</table>