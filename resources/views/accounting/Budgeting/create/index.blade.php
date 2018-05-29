<?php
use App\Http\Controllers\Accounting\Budgeting\CreateBudget;

?>
@extends('appaccounting')
@section('content')

<div class='container-fluid'>
    <h3>Create Budget for {{$fiscalyear}}</h3>
</div>
<div class='container-fluid'>
    <div class="col-md-12 table-responsive">
        <table id="budget" class="table table-bordered">
            <tr align="center">
                <td class="headcol" rowspan="2">Accounts</td>
                <td colspan="4">Institutional Budget for {{$prev_fiscalyear}}</td>
                <td rowspan="2">Institutional Budget for {{$fiscalyear}}</td>
                <td colspan="{{$offices->count()}}">{{$department}}</td>
            </tr>
            <tr align="center">
                <td>Budget</td>
                <td>Actual</td>
                <td>Balance</td>
                <td>Utilization</td>
                @foreach($offices as $office)
                <td class="offices">{{$office->sub_department}}</td>
                @endforeach
            </tr>
            <!--REVENUE-->
            <tr>
                <td colspan="{{6+$office->count()}}"><b>REVENUE</b></td>
            </tr>
            @foreach($revenues->groupBy('group') as $group=>$groups)
            <?php
            $indent = 10;
            ?>
            @if($groups->count() > 1 && $group == $groups->first()->group)
            <?php
            $indent = 30;
            ?>
            <tr>
                <td colspan="{{6+$office->count()}}">{{$group}}</td>
            </tr>
            @endif
            @foreach($groups as $account)
            {!!CreateBudget::view_accountRow($account,$offices,$indent)!!}
            @endforeach
            @endforeach
            <!--END REVENUE-->
            
            
            <!--EXPENSE-->
            <tr>
                <td colspan="{{6+$office->count()}}"><b>EXPENSES</b></td>
            </tr>
            
            <!--PERSONNEL EXPENSE-->
            <tr>
                <td colspan="{{6+$office->count()}}"><b>Personnel Expenses</b></td>
            </tr>
            @foreach($personel_expense->groupBy('group') as $group=>$groups)
            <?php
            $indent = 10;
            ?>
            @if($groups->count() > 1 && $group == $groups->first()->group)
            <?php
            $indent = 30;
            ?>
            <tr>
                <td colspan="{{6+$office->count()}}">{{$group}}</td>
            </tr>
            @endif
            @foreach($groups as $account)
            {!!CreateBudget::view_accountRow($account,$offices,$indent)!!}
            @endforeach
            @endforeach
            <!--END PERSONNEL EXPENSE-->
            
            <!--OTHER EXPENSE-->
            <tr>
                <td colspan="{{6+$office->count()}}"><b>Other Expenses</b></td>
            </tr>
            @foreach($other_expense->groupBy('group') as $group=>$groups)
            <?php
            $indent = 10;
            ?>
            @if($groups->count() > 1 && $group == $groups->first()->group)
            <?php
            $indent = 30;
            ?>
            <tr>
                <td colspan="{{6+$office->count()}}">{{$group}}</td>
            </tr>
            @endif
            @foreach($groups as $account)
            {!!CreateBudget::view_accountRow($account,$offices,$indent)!!}
            @endforeach
            @endforeach
            <!--END PERSONNEL EXPENSE-->
            <!--END REVENUE-->
        </table>
        
    </div>
</div>
<script>
    $(".budgetField").keyup(function(){
        var office = $(this).data('office');
        var entry = $(this).data('account');
        
        var arrays={};
        arrays['account']=$(this).data('account');
        arrays['office']=$(this).data('office');
        arrays['amount']=$(this).val();
        
        $.ajax({
        type:"GET",
        url:"{{route('updateBudget')}}",
        data:arrays,
            success:function(data){
            $("#acctcode").val(data)
            popsubsidiary(data)
            $("#amountdetails").fadeIn();
            document.getElementById('subsidiary').focus();   
            }    
        })
    })
</script>
@stop