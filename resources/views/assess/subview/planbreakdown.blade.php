<?php
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Helper as MainHelper;
use App\Http\Controllers\Assessement\ProcessReservation as Reserve;
use App\Http\Controllers\Assessement\ProcessDeposit as Deposit;

$sy = MainHelper::get_enrollmentSY();

$reservation = 0;
$deposit     = 0;
$awards      = 0;
?>
<table class='table table-bordered'>
    <tr>
        <td>Description</td>
        <td>Amount</td>
    </tr>
    @foreach($mainaccounts->groupBy('accountingcode') as $accounts)
    <tr>
        <td>{{$accounts->pluck('acctcode')->last()}}</td>
        <td align='right'>{{number_format($accounts->sum('amount'),2)}}</td>
    </tr>
    @endforeach
    <tr>
        <td>Total Fee</td>
        <td align='right' color="red">{{number_format($mainaccounts->sum('amount'),2)}}</td>
    </tr>
    <tr>
        <td>Less: Plan Discount</td>
        <td align='right'>{{number_format($mainaccounts->sum('discount')+$mainaccounts->sum('plandiscount'),2)}}</td>
    </tr>
    @if(Reserve::hasReservation($idno,0))
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reservation</td>
        <td align='right'>{{number_format(Reserve::remainingReservation($idno,0),2)}}</td>
    </tr>
    @endif
    @if(Deposit::hasDeposit($idno,1))
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Student Deposit</td>
        <td align='right'>{{number_format(Deposit::remainingDeposit($idno,1),2)}}</td>
    </tr>
    @endif
    <tr>
        <td>Total</td>
        <td align='right'>{{number_format($mainaccounts->sum('amount') - $mainaccounts->sum('discount')-$mainaccounts->sum('plandiscount')-$reservation-$deposit-Reserve::remainingReservation($idno,0) - Deposit::remainingDeposit($idno,1),2)}}</td>
    </tr>
</table>

@if(Info::get_status($idno, $sy) == 0)
<h5>Books</h5>
<table class="table table-bordered">
    <tr>
        <td></td>
        <td><div id="check_uncheck"><p  style="cursor:pointer" id="select_all" onclick="unselect_all()">Uncheck All</p></div></td>
        <td>Amount</td>
    </tr>
    @foreach($books as $book)
    <tr>
        <td><input type="checkbox" class="books" name="books[]" value="{{$book->id}}" checked="checked"></td>
        <td>{{$book->subsidiary}}</td>
        <td align='right'>{{number_format($book->amount,2)}}</td>
    </tr>
    @endforeach    
    <tr>
        <td></td>
        <td>Total</td>
        <td align='right'>{{number_format($books->sum('amount'),2)}}</td>
    </tr>
</table>

<button type="submit" class='col-md-12 btn btn-success'>Process</button>
@endif

@if(Info::get_status($idno, $sy) == 1)
<form method='POST' action='{{route("reassess",array($idno))}}' onSubmit="return confirm('Are you sure you wish to re-assess?')">
{!! csrf_field() !!}
<button type='submit' class='btn btn-success col-md-6'>Re-assess</button>
</form>
<a href="{{route('printassessment',array($idno))}}" class='btn btn-danger col-md-6'>Print Assessment</a>
@endif

<script>
function select_all(){
    $('.books').prop('checked', 'checked');
    $('#check_uncheck').html("<p style=\"cursor:pointer\" onclick=\"unselect_all()\">Uncheck All</p>")
}

function unselect_all(){
    //alert("hello")
    $('.books').prop('checked',false);
    $('#check_uncheck').html("<p style=\"cursor:pointer\" onclick=\"select_all()\">Select All</p>");
}
</script>