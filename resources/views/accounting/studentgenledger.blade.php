@extends('appaccounting')
@section('content')
<div class="container">
    <table class="table table-striped">
        <tr><td>Student Id</td><td>Student Name</td><td>Level</td><td>Amount</td><td>(Payment)</td><td>(Debit Memo)</td><td>Plan discount</td><td>Other Discount</td><td>Balance</td></tr>
       @foreach($ledgers as $ledger)
       <tr>
           <td>{{$ledger->idno}}</td>
           <td>{{$ledger->lastname}}, {{$ledger->firstname}} {{$ledger->middlename}}</td>
           <td>{{$ledger->level}} {{$ledger->course}} {{$ledger->strand}}</td>
           <td align="right">{{number_format($ledger->amount,2)}}</td>
           <td align="right">{{number_format($ledger->payment,2)}}</td>
           <td align="right">{{number_format($ledger->debitmemo,2)}}</td>
           <td align="right">{{number_format($ledger->plandiscount,2)}}</td>
           <td align="right">{{number_format($ledger->otherdiscount,2)}}</td>
           <td align="right">{{number_format($ledger->amount-$ledger->payment - $ledger->debitmemo- $ledger->plandiscount - $ledger->otherdiscount,2)}}</td>
       </tr>
       @endforeach
    </table>   
</div>
@stop

