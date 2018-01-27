@extends('appaccounting')
@section('content')
<?php
ini_set('max_execution_time', 0);

$curr_debit = $debits->where('isreverse','0');
$curr_credit = $credits->where('isreverse','0');

$total_debit = $curr_debit->where('transactiondate',$transactiondate);
$total_credit = $curr_credit->where('transactiondate',$transactiondate);

$forwaded_debit = $curr_debit->filter(function($item) use($transactiondate){
        return data_get($item, 'transactiondate') !== $transactiondate;
        });
$forwaded_credit = $curr_credit->filter(function($item) use($transactiondate){
        return data_get($item, 'transactiondate') !== $transactiondate;
        });

$receipts = $debits->where('transactiondate',$transactiondate)->unique('refno');

$sundries = $total_credit->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    })->groupBy('accountingcode');
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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Balance brought forward</td>
                <td>{{number_format($forwaded_debit->where('paymenttype','1')->sum('amount')+$forwaded_debit->where('paymenttype','1')->sum('checkamount'),2)}}</td>
                <td>{{number_format($forwaded_debit->where('paymenttype','4')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_debit->where('paymenttype','7')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_debit->where('paymenttype','5')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_debit->where('paymenttype','8')->sum('amount'),2)}}</td>
                
                <td>{{number_format($forwaded_credit->where('accountingcode','420200')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_credit->where('accountingcode','420400')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_credit->where('accountingcode','440400')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_credit->where('accountingcode','420100')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_credit->where('accountingcode','420000')->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_credit->whereIn('accountingcode',array('120100','410000'))->sum('amount'),2)}}</td>
                <td>{{number_format($forwaded_credit->where('accountingcode','210400')->sum('amount'),2)}}</td>
                <td>
                    {{number_format($forwaded_credit->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    })->sum('amount'),2)}}
                </td>
                <td></td>
            </tr>
            @foreach($receipts as $receipt)
            <?php
            $receipt_debit = $debits->where('refno',$receipt->refno);
            $receipt_credit = $credits->where('refno',$receipt->refno);
            ?>
            <tr style='text-align: right'>
                <td style='text-align: left'>{{$receipt->receiptno}}</td>
                <td style='text-align: left'>{{$receipt->receivefrom}}</td>
                
                <td>{{number_format($receipt_debit->where('paymenttype','1')->sum('amount')+$receipt_debit->where('paymenttype','1')->sum('checkamount'),2)}}</td>
                <td>{{number_format($receipt_debit->where('paymenttype','4')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_debit->where('paymenttype','7')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_debit->where('paymenttype','5')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_debit->where('paymenttype','8')->sum('amount'),2)}}</td>
                
                <td>{{number_format($receipt_credit->where('accountingcode','420200')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_credit->where('accountingcode','420400')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_credit->where('accountingcode','440400')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_credit->where('accountingcode','420100')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_credit->where('accountingcode','420000')->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_credit->whereIn('accountingcode',array('120100','410000'))->sum('amount'),2)}}</td>
                <td>{{number_format($receipt_credit->where('accountingcode','210400')->sum('amount'),2)}}</td>
                <td>
                    {{number_format($receipt_credit->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    })->sum('amount'),2)}}
                </td>
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
                <td>{{number_format($total_debit->where('paymenttype','1')->sum('amount')+$total_debit->where('paymenttype','1')->sum('checkamount'),2)}}</td>
                <td>{{number_format($total_debit->where('paymenttype','4')->sum('amount'),2)}}</td>
                <td>{{number_format($total_debit->where('paymenttype','7')->sum('amount'),2)}}</td>
                <td>{{number_format($total_debit->where('paymenttype','5')->sum('amount'),2)}}</td>
                <td>{{number_format($total_debit->where('paymenttype','8')->sum('amount'),2)}}</td>
                
                <td>{{number_format($total_credit->where('accountingcode','420200')->sum('amount'),2)}}</td>
                <td>{{number_format($total_credit->where('accountingcode','420400')->sum('amount'),2)}}</td>
                <td>{{number_format($total_credit->where('accountingcode','440400')->sum('amount'),2)}}</td>
                <td>{{number_format($total_credit->where('accountingcode','420100')->sum('amount'),2)}}</td>
                <td>{{number_format($total_credit->where('accountingcode','420000')->sum('amount'),2)}}</td>
                <td>{{number_format($total_credit->whereIn('accountingcode',array('120100','410000'))->sum('amount'),2)}}</td>
                <td>{{number_format($total_credit->where('accountingcode',210400)->sum('amount'),2)}}</td>
                <td>
                    {{number_format($total_credit->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    })->sum('amount'),2)}}
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan='16'><br></td>
            </tr>
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Current Balance</td>
                <td>{{number_format($curr_debit->where('paymenttype','1')->sum('amount')+$curr_debit->where('paymenttype','1')->sum('checkamount'),2)}}</td>
                <td>{{number_format($curr_debit->where('paymenttype','4')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_debit->where('paymenttype','7')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_debit->where('paymenttype','5')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_debit->where('paymenttype','8')->sum('amount'),2)}}</td>
                
                <td>{{number_format($curr_credit->where('accountingcode','420200')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_credit->where('accountingcode','420400')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_credit->where('accountingcode','440400')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_credit->where('accountingcode','420100')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_credit->where('accountingcode','420000')->sum('amount'),2)}}</td>
                <td>{{number_format($curr_credit->whereIn('accountingcode',array('120100','410000'))->sum('amount'),2)}}</td>
                <td>{{number_format($curr_credit->where('accountingcode','210400')->sum('amount'),2)}}</td>
                <td>
                    {{number_format($curr_credit->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    })->sum('amount'),2)}}
                </td>
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
                @foreach($sundries as $sundry)
                <tr>
                    <td>{{$sundry->pluck('acctcode')->last()}}</td>
                    <td>{{number_format($sundry->sum('amount'),2)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td>Total</td>
                    <td style='text-align: right'>{{number_format($total_credit->filter(function($item){
                    return !in_array(data_get($item, 'accountingcode'), array('420200','420400','440400','420100','420000','120100','410000','210400'));
                    })->sum('amount'),2)}}</td>
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