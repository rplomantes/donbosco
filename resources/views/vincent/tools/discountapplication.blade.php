<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
use App\Http\Controllers\ReportGenerator as Generate;

?>

@extends('appaccounting')
@section('content')
<table class='table table-striped'>
    <tr>
        <td>Name</td>
        <td>Level</td>
        <td>Discount</td>
        <td>Tuition Fee</td>
        <td>Department Facilities</td>
        <td>Registration and Other Fees</td>
        
        <td>Total</td>
        
    </tr>
    <?php
    $students = $ledger->groupBy('idno');
    ?>
    @foreach($students as $student)
    <?php
    $discounts = App\CtrDiscount::where('discountcode','LIKE',$student->pluck('discountcode')->last())->first();
    ?>
    <tr>
        <td>{{Info::get_name($student->pluck('idno')->last())}}</td>
        <td>{{Info::get_level($student->pluck('idno')->last(), $student->pluck('schoolyear')->last())}}</td>
        <td>{{$discounts->description}}</td>
        <td>{{$student->where('categoryswitch',6)->sum('otherdiscount')}}</td>
        
        
        <td>{{$student->where('categoryswitch',4)->sum('otherdiscount')}}</td>
        <td>{{$student->where('categoryswitch',5)->sum('otherdiscount')}}</td>
        <td>{{$student->sum('otherdiscount')}}</td>
    </tr>
    @endforeach
</table>
@stop