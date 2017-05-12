@extends('appcashier')
@section('content')
<div class="container">
    <div class="col-md-offset-2 col-md-8">
        <h3>{{$students[0]->fullname}}</h3>
        <h4>Transaction History</h4>
        <table class="table table-striped">
            <tr>
                <td>Receipt No.</td>
                <td>Tran. Date</td>
                <td>Amount</td>
                <td>Details</td>
                <td>Status</td>
            </tr>
            @foreach($students as $student)
            <tr>
            <?php $trans = \App\Dedit::where('idno',$student->idno)->first();?>
                <td>{{$trans->receiptno}}</td>
                <td>{{$trans->transactiondate}}</td>
                <td>{{number_format($trans->amount+$trans->checkamount,2,'.',',')}}</td>
                <td><a href="{{url('viewreceipt',array($trans->refno,$trans->idno))}}">view</a></td>
                <td>
                    @if($trans->isreverse == 0)
                    Ok
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@stop