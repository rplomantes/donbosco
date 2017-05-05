@extends('appaccounting')
@section('content')
<div class="container-fluid">
    <table class="table table-stripped">
        <thead>
            <tr>
                <td>DATE</td>
                <td>BANK_CHECK_NO</td>
                <td>VOUCHER_NO</td>
                <td>PAYEE</td>
                <td>ACCOUNT TITLE</td>
                <td>UNIT NAME</td>
                <td>ACCOUNT_AMOUNT</td>
                <td>DEBIT</td>
                <td>CANCEL_VOUCHER</td>
                <td>EXPLANATION</td>
            </tr>
        </thead>
        <tbody>
            @foreach($vouchers as $voucher)
                <?php $accounts = \App\Accounting::where('refno',$voucher->refno)->get();?>

                @foreach($accounts as $account)
                <tr>
                    <td>{{$voucher->transactiondate}}</td>
                    <td>{{$voucher->checkno}}</td>
                    <td>{{$voucher->voucherno}}</td>
                    <td>{{$voucher->payee}}</td>
                    <td>{{$account->accountname}}</td>
                    <td>{{$account->sub_department}}</td>
                    @if($account->cr_db_indic == 1)
                    <td>{{$account->credit}}</td>
                    @else
                    <td>{{$account->debit}}</td>
                    @endif
                    <td>FALSE</td>
                    @if($account->cr_db_indic == 1)
                    <td>FALSE</td>
                    @else
                    <td>TRUE</td>
                    @endif
                    <td>{{$voucher->remarks}}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <a href="" class="col-md-12 btn btn-default">Export</a>
</div>
@stop