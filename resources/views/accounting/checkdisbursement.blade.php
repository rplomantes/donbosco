@extends('appaccounting')
@section('content')
<?php 
    $index = 0;
    $total = 0;
?>
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <td></td>
                <td>VOUCHER NO.</td>
                <td>TRANS DATE</td>
                <td>BANK-CHECK #</td>
                <td>PAYEE</td>
                <td>RECONCILED AMOUNT</td>
            </tr>
        </thead>
        @foreach($accounts as $account)
            <?php 
                $vouchers = \App\Disbursement::where('bank',$account->bank)->where('isreverse',0)->whereBetween('transactiondate', array($from, $to))->orderBy('checkno','ASC')->get();
                $subamount = 0;
            ?>
            @if(count($vouchers)>0)
                @foreach($vouchers as $voucher)
                    <?php $index++; ?>
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{$voucher->voucherno}}</td>
                        <td>{{$voucher->transactiondate}}</td>
                        <td>{{$voucher->checkno}}</td>
                        <td>{{$voucher->payee}}</td>
                        <td style="text-align: right">{{number_format($voucher->amount,2,'.',',')}}</td>
                    </tr>
                    <?php 
                        $subamount = $subamount+$voucher->amount; 
                        $total = $total+$voucher->amount;
                    ?>
                @endforeach
            @else
                    <tr>
                        <td></td>
                        <td colspan="5">None</td>

                    </tr>
            @endif
            <tr>
                <td style="text-align: center" colspan="2"><b>{{count($vouchers)}}</b></td>
                <td style="text-align: center" colspan="3"><b>{{$account->bank}}</b></td>
                <td style="text-align: right"><b>{{number_format($subamount,2,'.',',')}}</b></td>
            </tr>
        @endforeach
        

        <tr>
            <td colspan="5" style="text-align: right"><b>Grand Total</b></td>
            <td style="text-align: right"><b>{{number_format($total,2,'.',',')}}</b></td>
        </tr>
    </table>
    
    <a href="{{url('printchecksummary',array($from,$to))}}" class="btn btn-info col-md-12">Print</a>
</div>
@stop
