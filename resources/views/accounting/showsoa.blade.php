@extends('appcashier')
@section('content')
<style>
    @media print{
        a {visibility: hidden}
        .print_col {display:none}
        table{width:100%}
        table>tr>td{width:25%}
        
    }
</style>
<div class="container">
    <h5> Statement of Account</h5>
    <table class="table table-stripped">
                <tr><td>Grade/Level : {{$level}}</td><td>Section : {{$section}}</td><td>
            @if($strand != "none")
            Strand/Shop : {{$strand}}
            @endif
            </td> </tr>
   </table>             
    <table class="table table-stripped"><tr><td>Student No</td><td>Name</td><td>Plan</td><td>Level</td><td>Section</td><td>Balance</td><td></td></tr>
       @foreach($soasummary as $soa)
       @if($soa->amount > 0)
       <tr><td>{{$soa->idno}}</td><td>{{$soa->lastname}}, {{$soa->firstname}} {{$soa->middlename}}</td>
           <td>{{$soa->plan}}</td><td>{{$soa->level}}</td><td>{{$soa->section}}</td><td align="right">{{number_format($soa->amount,2)}}</td><td class="print_col" align="center">
               <a href="{{url('printsoa', array($soa->idno,$trandate))}}" >Print</a>
           </td></tr>
       @endif
       @endforeach
 </table>       
    <div class="col-md-6">
        <a href="{{url('printsoasummary',array($level,$strand,$section,$trandate,$amtover))}}" class="btn btn-primary">Print Summary</a>
        <a href="{{url('printallsoa',array($level,$strand,$section,$trandate,$amtover))}}" class="btn btn-primary">View SOA</a>
    <?php session()->reflash(); ?>
    </div>    
</div>
@stop
