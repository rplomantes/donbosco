<?php
use App\Http\Controllers\Accounting\OfficeSumController;
$totalincome = 0;
$totalexpense = 0;
?>
@extends('appaccounting')
@section('content')
<div clas="container">
    <table class="table table-stripped">
        <tr>
            <td>Department</td>
            <td>Total Income</td>
            <td>Total Expense</td>
            <td>Balance</td>
        </tr>
        @foreach($departments as $department)
        <?php
        $income = OfficeSumController::deptTotal($incomeacct,$department->main_department,4);
        $expense = OfficeSumController::deptTotal($expenseacct,$department->main_department,5);
        
        $totalincome = $totalincome + preg_replace("/[\s+',]/","",$income);
        $totalexpense = $totalexpense + preg_replace("/[\s+',]/","",$expense);
        ?>
        <tr>
            <td>{{$department->main_department}}</td>
            <td>{{$income}}</td>
            <td>{{$expense}}</td>
            <td>{{number_format(preg_replace("/[\s+',]/","",$income)- preg_replace("/[\s+',]/","",$expense),2,' .',',')}}</td>
        </tr>
        @endforeach
        <tr>
            <td>Total</td>
            <td>{{number_format($totalincome,2,' .',',')}}</td>
            <td>{{number_format($totalexpense,2,' .',',')}}</td>
            <td>{{number_format($totalincome-$totalexpense,2,' .',',')}}</td>
        </tr>
    </table>
</div>
@stop