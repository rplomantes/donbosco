@extends('appcashier')
@section('content')

<div class="container">
    <h5> Statement of Account</h5>
    <table class="table table-stripped">
                <tr><td>Batch: Batch {{$request->batch}}</td><td>Section : {{$request->section}}</td><td>Course : {{$request->course}}</td></tr>
   </table>             
    <table class="table table-stripped"><tr><td>Student No</td><td>Name</td><td>Batch</td><td>Section</td><td>Balance</td><td></td></tr>
       @foreach($soasummary as $soa)
       @if($soa->amount > 0)
       <tr><td>{{$soa->idno}}</td><td>{{$soa->lastname}}, {{$soa->firstname}} {{$soa->middlename}}</td>
           <td>{{$soa->period}}</td><td>{{$soa->section}}</td><td align="right">{{number_format($soa->amount,2)}}</td><td class="print_col" align="center">
               <a href="{{url('printsoa', array($soa->idno,$trandate))}}" >Print</a>
           </td></tr>
       @endif
       @endforeach
 </table>
    <div class="col-md-6">
        <a href="{{url('printtvetsoasummary',array($request->batch,$request->course,$request->section,$trandate))}}" class="btn btn-primary">Print Summary</a>
        <a href="{{url('printtvetallsoa',array($request->batch,$request->course,$request->section,$trandate))}}" class="btn btn-primary">Print SOA</a>
        
    </div>    
</div>
@stop
