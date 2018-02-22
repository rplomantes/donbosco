<?php
$plans = $accounts->where('level',$level,false)->groupBy('plan');
?>
@extends('appaccounting')
@section('content')
<div class='container'>
    @foreach($plans as $plan)
    <?php
    $accts = $plan->groupBy('accountingcode');
    ?>
    <table class='table table-bordered'>
        <tr style='font-weight: bold'>
            <td>{{$plan->pluck('plan')->last()}}</td>
            @foreach($courses as $course)
            <td>{{$course->strand}}</td>
            @endforeach
        </tr>
        
        @foreach($accts as $account)
        
        <tr>
            <td>{{$account->pluck('acctcode')->last()}}</td>
            
            @foreach($courses as $course)
            <td>
                @if(in_array($level,array('Grade 11','Grade 12')))
                {{number_format($account->where('course',$course->strand,false)->where('duetype',0,false)->sum('amount'),2)}}
                @else
                {{number_format($account->where('strand',$course->strand,false)->where('duetype',0,false)->sum('amount'),2)}}
                @endif
                
            </td>
            @endforeach
        </tr>
        @endforeach
        <tr style='font-weight: bold'>
            <td>Total</td>
            @foreach($courses as $course)
            <td>        
                @if(in_array($level,array('Grade 11','Grade 12')))
                {{number_format($plan->where('course',$course->strand,false)->where('duetype',0,false)->sum('amount'),2)}}
                @else
                {{number_format($plan->where('strand',$course->strand,false)->where('duetype',0,false)->sum('amount'),2)}}
                @endif
                
            </td>
            @endforeach
        </tr>
        <tr>
            <td>Less: Reservation:</td>
            @foreach($courses as $course)
            <td>        
                (1,000.00)
            </td>
            @endforeach
        </tr>
        <tr>
            <td>Less: Discount on Tuition Fee</td>
            @foreach($courses as $course)
            <td>        
                @if(in_array($level,array('Grade 11','Grade 12')))
                {{number_format($plan->where('course',$course->strand,false)->where('duetype',0,false)->sum('discount'),2)}}
                
                @else
                {{number_format($plan->where('strand',$course->strand,false)->where('duetype',0,false)->sum('discount'),2)}}
                @endif
                
                
            </td>
            @endforeach
        </tr>
        
        <?php
        $otherpayments = $plan->where('duetype',1,false)->groupBy('duedate');    
        ?>
        <tr>
            <td></td>
            @foreach($courses as $course)
            <td></td>
            @endforeach
        </tr>
        @if(in_array($plan->pluck('plan')->last(),array('Monthly 1','Monthly 2')))
        

        <tr>
            <td>{{$otherpayments->first()->pluck('duedate')->last()}}</td>
            
            @foreach($courses as $course)
            <td>
                @if(in_array($level,array('Grade 11','Grade 12')))
                {{number_format($otherpayments->first()->where('course',$course->strand,false)->sum('amount'),2)}}
                @else
                {{number_format($otherpayments->first()->where('strand',$course->strand,false)->sum('amount'),2)}}
                @endif
                
            </td>
            @endforeach
        </tr>
        
        @else
        @foreach($otherpayments as $otherpayment)
        <tr>
            <td>{{$otherpayment->pluck('duedate')->last()}}</td>
            
            @foreach($courses as $course)
            <td>
                @if(in_array($level,array('Grade 11','Grade 12')))
                {{number_format($otherpayment->where('course',$course->strand,false)->sum('amount'),2)}}
                @else
                {{number_format($otherpayment->where('strand',$course->strand,false)->sum('amount'),2)}}
                @endif
                
            </td>
            @endforeach
        </tr>
        @endforeach
        @endif
    </table>
    @endforeach
</div>
@stop