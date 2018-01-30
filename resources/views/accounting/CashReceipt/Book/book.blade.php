@extends('appaccounting')
@section('content')
<?php
$forwarded = $records->where('refno','forwarded');
        
$receipts = $records->where('transactiondate',$transactiondate);
$subtotal = $receipts->where('isreverse',0);

$grand_total = $records->where('isreverse',0);

$sundries = $credits->where('isreverse',0)->where('transactiondate',$transactiondate)->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    });
?>
<h3>CASH RECEIPT BOOK</h3
<div class='container-fluid'>
    <div class="col-md-2">
        <label>Transaction Date</label>
        <input type="text" class="form-control" id="trandate" value="{{$transactiondate}}">
    </div>
    <div class="col-md-2">
        <button id="processbtn" class="btn btn-primary form-control">View</button>
    </div>
</div>
<div class='container-fluid'>
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
                <th>Sundry<br>Accounts</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
                <td>{{number_format($forwarded->sum('cash'),2)}}</td>
                <td>{{number_format($forwarded->sum('discount'),2)}}</td>
                <td>{{number_format($forwarded->sum('fape'),2)}}</td>
                <td>{{number_format($forwarded->sum('dreservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('deposit'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwarded->sum('misc'),2)}}</td>
                <td>{{number_format($forwarded->sum('book'),2)}}</td>
                <td>{{number_format($forwarded->sum('dept'),2)}}</td>
                <td>{{number_format($forwarded->sum('registration'),2)}}</td>
                <td>{{number_format($forwarded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwarded->sum('creservation'),2)}}</td>
                <td>{{number_format($forwarded->sum('csundry'),2)}}</td>
                <td></td>
                <td></td>
            </tr>
            @foreach($receipts as $receipt)
            <tr style='text-align: right'>
                <td style='text-align: left'>{{$receipt->receiptno}}</td>
                <td style='text-align: left'>{{$receipt->from}}</td>
                
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
                <td align="left" style="white-space: nowrap">{!!$receipt->csundry_account!!}</td>
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
                <td>{{number_format($subtotal->sum('cash'),2)}}</td>
                <td>{{number_format($subtotal->sum('discount'),2)}}</td>
                <td>{{number_format($subtotal->sum('fape'),2)}}</td>
                <td>{{number_format($subtotal->sum('dreservation'),2)}}</td>
                <td>{{number_format($subtotal->sum('deposit'),2)}}</td>
                
                <td>{{number_format($subtotal->sum('elearning'),2)}}</td>
                <td>{{number_format($subtotal->sum('misc'),2)}}</td>
                <td>{{number_format($subtotal->sum('book'),2)}}</td>
                <td>{{number_format($subtotal->sum('dept'),2)}}</td>
                <td>{{number_format($subtotal->sum('registration'),2)}}</td>
                <td>{{number_format($subtotal->sum('tuition'),2)}}</td>
                <td>{{number_format($subtotal->sum('creservation'),2)}}</td>
                <td>{{number_format($subtotal->sum('csundry'),2)}}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan='16'><br></td>
            </tr>
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Current Balance</td>
                <td>{{number_format($grand_total->sum('cash'),2)}}</td>
                <td>{{number_format($grand_total->sum('discount'),2)}}</td>
                <td>{{number_format($grand_total->sum('fape'),2)}}</td>
                <td>{{number_format($grand_total->sum('dreservation'),2)}}</td>
                <td>{{number_format($grand_total->sum('deposit'),2)}}</td>
                
                <td>{{number_format($grand_total->sum('elearning'),2)}}</td>
                <td>{{number_format($grand_total->sum('misc'),2)}}</td>
                <td>{{number_format($grand_total->sum('book'),2)}}</td>
                <td>{{number_format($grand_total->sum('dept'),2)}}</td>
                <td>{{number_format($grand_total->sum('registration'),2)}}</td>
                <td>{{number_format($grand_total->sum('tuition'),2)}}</td>
                <td>{{number_format($grand_total->sum('creservation'),2)}}</td>
                <td>{{number_format($grand_total->sum('csundry'),2)}}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<br>
<div class="col-md-5">
    <div class="panel">
        <div class="panel-heading">Sundries List</div>
        <div class="panel-body">
            <table class='table table-bordered'>
                <tr>
                    <td>Account</td>
                    <td>Amount</td>
                </tr>
                @foreach($sundries->groupBy('accountingcode') as $sundry)
                <tr>
                    <td>{{$sundry->pluck('acctcode')->last()}}</td>
                    <td style='text-align: right'>{{number_format($sundry->sum('amount'),2)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td>Total</td>
                    <td style='text-align: right'>{{number_format($sundries->sum('amount'),2)}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
     $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('cashreceipt')}}" + "/" + $('#trandate').val();
    });
       
    
</script>
@stop