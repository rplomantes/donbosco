@extends('appcashier')
@section('content')

<div class="container">
    <h5> Statement of Account</h5>
    <table class="table table-stripped">
                <tr><td>Batch: Batch {{$request->batch}}</td><td>Section : {{$request->section}}</td><td>Course : {{$request->course}}</td></tr>
   </table>             
    <div class="col-md-12">
        <label class="radio-inline">
            <input onclick="viewacct(this.value)" type="radio" name="acct" id="acct" value="1">Only Negative Accounts
        </label>
        <label class="radio-inline">
          <input onclick="viewacct(this.value)" type="radio" name="acct" id="acct" value="2">Only Positive Accounts
        </label>
        <label class="radio-inline">
          <input onclick="viewacct(this.value)" type="radio" name="acct" id="acct" value="3">All Accounts
        </label>
    </div>
    <table class="table table-stripped"><tr><td>Student No</td><td>Name</td><td>Batch</td><td>Section</td><td colspan="2" align="center">Balance</td><td></td></tr>
       @foreach($soasummary as $soa)
       @if($soa->amount != 0)
       <tr
           @if($soa->amount > 0)
           class="positive"
           @else
           class="negative"
           @endif
           >
           <td>{{$soa->idno}}</td>
           <td>{{$soa->lastname}}, {{$soa->firstname}} {{$soa->middlename}}</td>
           <td>{{$soa->period}}</td>
           <td>{{$soa->section}}</td>
           <td align="right">
            @if($soa->amount > 0)
            {{number_format($soa->amount,2)}}
            @endif
           </td>
           <td align="right">
            @if($soa->amount < 0)
            {{number_format($soa->amount,2)}}
            @endif
           </td>
           <td class="print_col" align="center">
               <a href="{{url('printsoa', array($soa->idno,$trandate))}}" >Print</a>
           </td></tr>
       @endif
       @endforeach
    </table>
    <div class="col-md-6">
        <a id="printlist" href="{{url('printtvetsoasummary',array($request->batch,$request->course,$request->section,$trandate,3))}}" class="btn btn-primary">Print Summary</a>
        <a id="printsoa" href="{{url('printtvetallsoa',array($request->batch,$request->course,$request->section,$trandate,3))}}" class="btn btn-primary">Print SOA</a>
        
    </div>    
</div>

<script>
function viewacct(view){
    if(view == 1){
        $(".negative").show();
        $(".positive").hide();
        $("#printlist").prop("href","{{url('printtvetsoasummary',array($request->batch,$request->course,$request->section,$trandate,1))}}");
        $("#printsoa").prop("href","{{url('printtvetallsoa',array($request->batch,$request->course,$request->section,$trandate,1))}}");
    }else if(view == 2){
        $(".negative").hide();
        $(".positive").show();
        $("#printlist").prop("href","{{url('printtvetsoasummary',array($request->batch,$request->course,$request->section,$trandate,2))}}");
        $("#printsoa").prop("href","{{url('printtvetallsoa',array($request->batch,$request->course,$request->section,$trandate,2))}}");
    }else{
        $(".negative").show();
        $(".positive").show();
        $("#printlist").prop("href","{{url('printtvetsoasummary',array($request->batch,$request->course,$request->section,$trandate,3))}}");
        $("#printsoa").prop("href","{{url('printtvetallsoa',array($request->batch,$request->course,$request->section,$trandate,3))}}");
    }
}
</script>
@stop
