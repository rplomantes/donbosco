<?php
$plans = $accounts->groupBy('plan');
?>
@extends('appaccounting')
@section('content')
<div class='container'>
    @foreach($plans as $plan)
    <?php
    $accts = $plan->groupBy('accountingcode');
    ?>
    <table class='table table-bordered'>
        <tr>
            <td>{{$plan->pluck('plan')->last()}}</td>
            @foreach($levels as $level)
            <td>{{$level->level}}</td>
            @endforeach
        </tr>
        
        @foreach($accts as $account)
        
        <tr>
            <td>{{$account->pluck('acctcode')->last()}}</td>
            
            @foreach($levels as $level)
            <td>        
                {{number_format($account->where('level',$level->level,false)->where('duetype',0,false)->sum('amount'),2)}}
            </td>
            @endforeach
        </tr>
        @endforeach
        <tr style='font-weight: bold'>
            <td>Total</td>
            @foreach($levels as $level)
            <td>        
                {{number_format($plan->where('level',$level->level,false)->where('duetype',0,false)->sum('amount'),2)}}
            </td>
            @endforeach
        </tr>
        <tr>
            <td>Less: Reservation:</td>
            @foreach($levels as $level)
            <td>        
                (1,000.00)
            </td>
            @endforeach
        </tr>
        <tr>
            <td>Less: Discount on Tuition Fee</td>
            @foreach($levels as $level)
            <td>        
                {{number_format($plan->where('level',$level->level,false)->where('duetype',0,false)->sum('discount'),2)}}
                
            </td>
            @endforeach
        </tr>
        
        <?php
        $otherpayments = $plan->where('duetype',1,false)->groupBy('duedate');    
        ?>
        <tr>
            <td></td>
            @foreach($levels as $level)
            <td></td>
            @endforeach
        </tr>
        @foreach($otherpayments as $otherpayment)
        <tr>
            <td>{{$otherpayment->pluck('duedate')->last()}}</td>
            
            @foreach($levels as $level)
            <td>        
                {{number_format($otherpayment->where('level',$level->level,false)->sum('amount'),2)}}
                
            </td>
            @endforeach
        </tr>
        @endforeach
    </table>
    @endforeach
</div>
@stop