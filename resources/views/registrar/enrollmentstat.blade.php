@extends('app')

@section('content')


<div class="container">
  <h2>ENROLLMENT STATISTICS</h2>
  <div class='col-md-6'><p>Grade Level: </p></div>
  <div class='col-md-6'><p>TVET</p></div>
</div>
<div class="container">  
  <div class='col-md-6'>
   <table class="table">
    <thead>
      <tr>
        <th>Level</th>
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
     <tr><td>{{$stat->level}} </td><td>{{$stat->strand}}</td><td align="right">{{$stat->count}}</td></tr>
     @endforeach

     <tr><td colspan="2">Total</td><td align="right">{{number_format($mycount,2)}}</td></tr>
    </tbody>
  </table>
  </div>
  <div class='col-md-6'>
      @foreach($tvets as $batch)
      <button type="button" class="btn col-md-12" data-toggle="collapse" data-target="#{{$batch->period}}">Batch {{$batch->period}}</button>
      <?php
      $tvetstudents = DB::Select("select count(id) as count, period, department, level, strand, course from statuses where period = '$batch->period' and status = '2' and department='TVET'"
            . " group by course");
      $count = 0;
      ?>
        <table class="table collapse" id='{{$batch->period}}'>
         <thead>
           <tr>
             <th>Course</th>
             <th>No. of Enrollees</th>
           </tr>
         </thead>
         <tbody>
          @foreach($tvetstudents as $stat)
          <?php
          $count = $count + $stat->count;
          ?>
          <tr><td>{{$stat->course}}</td><td align="right">{{$stat->count}}</td></tr>
          @endforeach
	  <?php $tvet = $tvet + $count;?>
          <tr><td>Total</td><td align="right">{{$count}}</td></tr>
         </tbody>
        </table>
      <br><br>
      @endforeach
  </div>
</div>


<div class="container">
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
            <tr><td>K12 Grand Total</td><td>{{$gradelevel}}</td></tr>
            <tr><td>TVET</td><td>{{$tvet}}</td></tr>
    </table>
   </div>

</div>
@endsection

