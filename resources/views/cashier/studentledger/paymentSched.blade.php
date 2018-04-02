<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
use App\Http\Controllers\StudentLedger\ViewController as Ledgers;


?>
<h4><b>Schedule of payment</b>({{Info::get_plan($idno, $accounts->pluck('schoolyear')->last())}})</h4>
<table class="table table-striped">
    <tr>
        <th>Due Date</th>
        <th>Amount</th>
    </tr>
    @foreach($accounts->groupBy('duedate') as $account)
    <tr>
        <td>{{$account->pluck('duetype')->last() == 0 ?"Upon Enrollment" : date('M d, Y',strtotime($account->pluck('duedate')->last()))}}</td>
        <td align="right">{{number_format(Ledgers::accountsdue($account),2)}}</td>
    </tr>
    @endforeach
</table>