@extends('appaccounting')
@section('content')

<?php      
        $sys = App\CtrSchoolYear::get();
?>
<div class="container">
<h4>Summary of Main Account</h4>


<?php 
    $students = App\Status::where('schoolyear',$schoolyear)->whereIn('status',array(2,3))->get()->implode(',','idno');
    
    $totalmains = DB::Select("select acctcode as accountname, sum(amount) as amount, sum(payment) as payment, sum(debitmemo) as debitmemo, "
     . " sum(plandiscount) as plandiscount, "
     . " sum(otherdiscount) as otherdiscount from ledgers where categoryswitch <= '6' and idno IN($students) and schoolyear = '$schoolyear' and payment+debitmemo+plandiscount+otherdiscount <= amount group by acctcode");
    
    $allamount = 0;
    $allpayment = 0;
    $alldebitmemo = 0;
    $allplandiscount = 0;
    $allotherdiscount=0;
?>

<table class="table table-striped">
    <tr><td>Account Name</td><td>Amount</td><td>(Payment)</td><td>(Debit Memo)</td><td>(Plan Discount)</td><td>(Other Discount)</td><td>Balance</td></tr>
    <?php $totalamount = 0; $totalpayment=0; $totaldebitmemo=0; $totalplandiscount=0; $totalotherdiscount=0; ?>
    @foreach($totalmains as $totalmain)
    <?php 
    $totalamount = $totalamount + $totalmain->amount; 
    $totalpayment=$totalpayment + $totalmain->payment; 
    $totaldebitmemo=$totaldebitmemo + $totalmain->debitmemo; 
    $totalplandiscount=$totalplandiscount + $totalmain->plandiscount; 
    $totalotherdiscount=$totalotherdiscount + $totalmain->otherdiscount;
    ?>
    <tr><td>{{$totalmain->accountname}}</td><td align="right">{{number_format($totalmain->amount,2)}}</td><td align="right">{{number_format($totalmain->payment,2)}}</td>
        <td align="right">{{number_format($totalmain->debitmemo,2)}}</td><td align="right">{{number_format($totalmain->plandiscount,2)}}</td>
        <td align="right">{{number_format($totalmain->otherdiscount,2)}}</td><td align="right">
        {{number_format($totalmain->amount-$totalmain->payment-$totalmain->debitmemo-$totalmain->plandiscount-$totalmain->otherdiscount,2)}}    
        </td></tr>
    @endforeach   
    <tr><td>Total</td><td align="right">{{number_format($totalamount,2)}}</td><td align="right">{{number_format($totalpayment,2)}}</td><td align="right">{{number_format($totaldebitmemo,2)}}</td>
        <td align="right">{{number_format($totalplandiscount,2)}}</td><td align="right">{{number_format($totalotherdiscount,2)}}</td><td align="right">{{number_format($totalamount-$totalpayment-$totaldebitmemo-$totalotherdiscount-$totalplandiscount,2)}}</td></tr>
</table>    


</div>    
@stop
