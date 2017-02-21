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
        <?php $mycount=0;?>
     @foreach($stats as $stat)
     <?php $mycount=$mycount + $stat->count;?>
     <tr><td>{{$stat->department}}</td><td>{{$stat->level}} </td><td>{{$stat->course}}</td><td>{{$stat->strand}}</td><td align="right">{{$stat->count}}</td></tr>
     @endforeach
     <tr><td colspan="4">Total</td><td align="right">{{number_format($mycount,2)}}</td></tr>
    </tbody>
  </table>
</div>

</table>

@endsection