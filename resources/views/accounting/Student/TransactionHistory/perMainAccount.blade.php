@php
use App\Http\Controllers\Accounting\Student\TransanctionHistory\Helper;
@endphp
@extends('appaccounting')
@section('content')
<style>
    #printheader{
        display:none;
    }
</style>

<style media="print">
    #printheader{
        display:block;
    }
    #main_footer{
        display:none;
    }


</style>

<table border = '0' celpacing="0" cellpadding = "0" id="printheader">
    <tr>
        <td><img class ="img-responsive" style ="margin-top:10px;" src = "{{ asset('/images/logo.png') }}" alt="Don Bosco Technical School" /></td>
        <td>
            <p>
                <span style="font-size:12pt;">
                    Don Bosco Technical Institute of Makati, Inc.<br>
                    Chino Roces Ave., Makati City 
                </span>
            </p>
        </td>
    </tr>
</table>
<div class="container">
    <h3>Student's Payment History SY({{$schoolyear}} - {{$schoolyear+1}}) <small>Per Account</small></h3>
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
    <?php
    $mainaccount = $receipts->filter(function($item){
        if($item->categoryswitch < 9){
            return true;
        }
    });
    ?>
    <table width="60%" border="0">    
        @foreach($receipts->sortBy('categoryswitch')->groupBy('acctcode') as $accounts)
        <tr>
            <td colspan="3"><h4>{{$accounts->pluck('acctcode')->last()}}</h4></td>
            <td align='right'><b>Debit : </b>{{number_format($ledger->where('accountingcode',$accounts->pluck('accountingcode')->last(),false)->sum('amount'),2)}}</td>
            <td></td>
            <td></td>
        </tr>
        @foreach($accounts->sortBy('id')->groupBy('refno') as $receipt)
        <tr>
            <td width='20%'></td>
            <td>{{date('M d, Y',strtotime($receipt->pluck('transactiondate')->last()))}}</td>
            <td>{{$receipt->pluck('receiptno')->last()}}</td>
            <td align='right'>{{number_format($receipt->sum('amount'),2)}}</td>
            <td colspan='2'></td>
        </tr>
        @endforeach
        <tr>
            <td width='25%'></td>
            <td></td>
            <td></td>
            <td align='right' style="border-top: 2px solid">{{number_format($accounts->sum('amount'),2)}}</td>
            <td width="100px" align="center">Balance:</td>
            <td><b>{{number_format($accounts->sum('amount') - $ledger->where('accountingcode',$accounts->pluck('accountingcode')->last(),false)->sum('amount'),2)
                    }}</b></td>
        </tr>
        @endforeach
    </table>
</div>
@stop