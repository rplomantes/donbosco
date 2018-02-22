@php
use App\Http\Controllers\Accounting\Student\TransanctionHistory\Helper;
@endphp
@extends('appaccounting')
@section('content')


<div class="container">
    <h3>Student's Payment History SY({{$schoolyear}} - {{$schoolyear+1}}) <small>Summary</small></h3>
    <table class="table ">
        <tr>
            <td>Student ID</td>
            <td>{{$idno}}</td>
            <td>Student ID</td>
            <td>{{Helper::get_department($idno,$schoolyear)}}</td>
        </tr>
        <tr>
            <td>Student Name</td>
            <td>{{Helper::get_name($idno)}}</td>
            <td>Level</td>
            <td>{{Helper::get_level($idno,$schoolyear)}}</td>
        </tr>
        <tr>
            <td>Gender</td>
            <td>{{Helper::get_gender($idno)}}</td>
            <td>Section</td>
            <td>{{Helper::get_section($idno,$schoolyear)}}</td>
        </tr>
    </table>
    
    <table class="table table-bordered">
        <tr>
            <th>Date</th>
            <th>OR Number</th>
            <th>Amount</th>
            <th>Payment Type</th>
            <td>Cashier</td>
        </tr>
        
        @foreach($receipts->groupBy('refno') as $receipt)
        <tr align='right'>
            <td>{{$receipt->pluck("transactiondate")->last()}}</td>
            <td><a href="{{url('viewreceipt',array($receipt->pluck('refno')->last(),$idno))}}">{{$receipt->pluck("receiptno")->last()}}</a></td>
            <td>{{number_format($receipt->sum('amount')+$receipt->sum('checkamount'),2)}}</td>
            <td align='left'>{{Helper::paymenttype($receipt)}}</td>
            <td align='left'>{{ucwords(strtolower(Helper::get_name($receipt->pluck("postedby")->last())))}}</td>
            
            
        </tr>
        @endforeach
        <tr align='right'>
            <td colspan='2'>TOTAL</td>
            <td>{{number_format($receipts->sum('amount')+$receipts->sum('checkamount'),2)}}</td>
        </tr>
    </table>
</div>
@stop