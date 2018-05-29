<?php
use App\Http\Controllers\Cashier\ReceiptController;
use App\Http\Controllers\Administrator\Receipt\UpdateReceipt;

?>
@extends('app')
@section('content')

<div class="container-fluid">
    <div class="col-md-7">
        {!!UpdateReceipt::updateForm($refno)!!}
    </div>
    <div class="col-md-5">
        {!!ReceiptController::receiptOverview($refno)!!}
    </div>
</div>
@stop