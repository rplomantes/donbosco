@extends('app')

@section('content')


<div class="container">
  <h2>ENROLLMENT STATISTICS</h2>
  <p>Enrollment Period: </p>
   <table class="table">
    <thead>
      <tr>
        <th>Department</th>  
        <th>Level</th>
        <th>Course</th>
        <th>Strand</th>
        <th>No. of Enrollees</th>
      </tr>
    </thead>
    <tbody>
        <?php 
        $mycount=0;
        $gradelevel = 0;
        $tvet = 0;
        
        $elem = 0;
        $jhs = 0;
        $shs = 0;
        ?>
     @foreach($stats as $stat)
     <?php 
        $mycount=$mycount + $stat->count;
        $gradelevel = $gradelevel + $stat->count;
        
        switch($stat->department){
            case 'Kindergarten':
                $elem = $elem + $stat->count;
                break;
            case 'Elementary':
                $elem = $elem + $stat->count;
                break;
            case 'Junior High School':
                $jhs = $jhs + $stat->count;
                break;
            case 'Senior High School':
                $shs = $shs + $stat->count;
                break;
        }
     ?>
     <tr><td>{{$stat->department}}</td><td>{{$stat->level}} </td><td>{{$stat->course}}</td><td>{{$stat->strand}}</td><td align="right">{{$stat->count}}</td></tr>
     @endforeach
     @foreach($tvets as $stat)
     <?php 
        $mycount=$mycount + $stat->count;
        $tvet = $tvet + $stat->count;
        ?>
     <tr><td>{{$stat->department}}</td><td>{{$stat->level}} </td><td>{{$stat->course}}</td><td>{{$stat->strand}}</td><td align="right">{{$stat->count}}</td></tr>
     @endforeach
     <tr><td colspan="4">Total</td><td align="right">{{number_format($mycount,2)}}</td></tr>
    </tbody>
  </table>
  <div class="col-md-4">
    <table class="table table-condensed">
            <thead>
            <tr><td>Department</td><td>Count</td></tr>
            </thead>
            <tr><td>Elementary</td><td>{{number_format($elem)}}</td></tr>
            <tr><td>Junior High School</td><td>{{number_format($jhs)}}</td></tr>
            <tr><td>Senior High School</td><td>{{number_format($shs)}}</td></tr>
    </table>
   </div>
  <div class="col-md-4">
    <table class="table table-stripped">
            <thead>
            <tr><td>Department</td><td>Count</td></tr>
            </thead>
            <tr><td>Grade School</td><td>{{$gradelevel}}</td></tr>
            <tr><td>TVET</td><td>{{$tvet}}</td></tr>
    </table>
   </div>

</div>

@endsection