<?php
//Replacement for the current ledger
use App\Http\Controllers\StudentLedger\ViewController as Ledg;
use App\Http\Controllers\Cashier\Payment\MainPayment as Payment;


?>
@extends('appcashier')
@section('content')
<div class='container-fluid'>
    <div class='col-md-6'>
        <div class='row'>
            
        </div>
        
            {!!Ledg::studentInfo($idno)!!}
            {!!Ledg::viewledger($idno)!!}
            {!!Ledg::paymentHistory($idno)!!}
            {!!Ledg::debitMemo($idno)!!}
    </div>
    
    <div class='col-md-3'>
            {!!Ledg::paymentSched($idno)!!}
            {!!Ledg::prevBalance($idno)!!}
            {!!Ledg::otherAccts($idno)!!}
    </div>
    <div class="col-md-3">
        @if(\Auth::user()->accesslevel==env('USER_CASHIER') || \Auth::user()->accesslevel == env('USER_CASHIER_HEAD'))
        {!!Payment::viewPayment($idno)!!}
        @endif
        
        @if(\Auth::user()->accesslevel==env('USER_ACCOUNTING') || \Auth::user()->accesslevel == env('USER_ACCOUNTING_HEAD'))
        {!!Ledg::debitMemo($idno)!!}
        @endif
        
    </div>
</div>
@stop