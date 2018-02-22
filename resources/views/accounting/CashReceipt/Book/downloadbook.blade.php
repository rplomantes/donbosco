<?php
$forwarded = $records->where('refno','forwarded',false);
$receipts = $records->where('transactiondate',$transactiondate,false);

$subtotal = $receipts->where('isreverse',0,false);
$grand_total = $records->where('isreverse',0,false);

$entrysundies = \App\RptCashReceiptBookSundries::with('RptCashreceiptBook')->where('idno',\Auth::user()->idno)->get();
?>
<style>
    td{
        border:1px solid #000000;
    }
</style>
<table class='table table-bordered'>
    <thead>
        <tr>
            <th>Receipt No.</th>
            <th>Name</th>
            <th>Debit<br>Cash / Check</th>
            <th>Debit<br>Discount</th>
            
            <th>E - Learning</th>
            <th>Misc</th>
            <th>Book</th>
            <th>Department<br> Facilities</th>
            <th>Registration Fee</th>
            <th>Tuition Fee</th>
            <th>Credit<br>Reservation</th>
            
            <th style="text-align: center">Sundry</th>
            <th></th>
            <th></th>
            <th>Status</th>
            
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Account</th>
            <th></th>
        </tr>
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
        
            <?php $receiptsundry = $entrysundies->where('refno',$receipt->refno,false);?>
        
        <tr style='text-align: right'>
            <td rowspan='{{$receipt->row_count}}' style='text-align: left'>{{$receipt->receiptno}}</td>
            <td rowspan='{{$receipt->row_count}}' style='text-align: left'>{{$receipt->from}}</td>
                
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->cash}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->discount}}</td>
                
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->elearning}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->misc}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->book}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->dept}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->registration}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->tuition}}</td>
            <td rowspan='{{$receipt->row_count}}'>{{$receipt->creservation}}</td>
            <td>@if(count($receiptsundry)>0 && $receiptsundry->first()->debit >0){{$receiptsundry->first()->debit}}@endif</td>
            <td>@if(count($receiptsundry)>0 && $receiptsundry->first()->credit >0){{$receiptsundry->first()->credit}}@endif</td>
            <td style="white-space: nowrap;" align='left'>@if(count($receiptsundry)>0){{$receiptsundry->first()->particular}}@endif</td>
            <td rowspan='{{$receipt->row_count}}' align='center'>
                @if($receipt->isreverse == 0)
                OK
                @else
                Cancelled
                @endif
            </td>
        </tr>
            
       @if(count($receiptsundry)>1)
            @foreach($receiptsundry as $sundryRec)
                @if($sundryRec != $receiptsundry->first())
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align='right'>@if($sundryRec->debit > 0) {{$sundryRec->debit}}@endif</td>
                    <td align='right'>@if($sundryRec->credit > 0){{$sundryRec->credit}}@endif</td>
                    <td align='left' style="white-space: nowrap;">{{$sundryRec->particular}}</td>
                    <td></td>
                </tr>
                @endif
            @endforeach
        @endif
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