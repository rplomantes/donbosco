@extends('appcashier')
@section('content')
<style media="print">
    .no-print{
        display: none;
    }
    .text-muted{
        display: none;
    }
    
    input[type="checkbox"]{
        display: none;
    }
    
    table{
        font-size:10px;
    }
</style>
<div class="container">
    <h5> Statement of Account - <b>({{$plan}})</b> for the month of <b> {{$forthemonth}}</b></h5>
    <form method="post" action="{{url('/postpenalties')}}" onsubmit="return confirm('ARE YOU SURE TO POST PENALTIES FOR THIS MONTH?')">
        {!! csrf_field() !!} 
        
        <table class = "table table-responsive"><tr><td>Student No</td><td>Name</td><td>Level</td><td>Section</td><td>Strand/Shop</td><td>Balance</td><td>Penalty</td><td></td></tr>
      <?php $index=0;?>
       @foreach($soasummary as $soa)
       @if($soa->amount > 0)
       <tr><td>{{$soa->idno}}</td><td>{{$soa->lastname}}, {{$soa->firstname}} {{$soa->middlename}}</td><td>{{$soa->level}}</td>
           <td>{{$soa->section}}</td><td>{{$soa->strand}}</td><td align="right">{{number_format($soa->amount,2)}}</td><td align="right">
               <?php if($soa->amount >= 1000){
                        $penalty = $soa->amount * 0.05;
                            if($penalty < 250){
                                $penalty=250;
                            }
                            
                      }else{
                          $penalty=0;
                          
                      } echo number_format($penalty,2);?>
           </td><td><input type="checkbox" name="idnumber[]" value="{{$soa->idno}}" 
                           @if($soa->amount>=1000)
                           checked="checked"
                           @endif
                           >
       
       </td></tr>
       @endif
       
       @endforeach
       
 </table>       
    
        <div class="col-md-6 no-print">
            <input type="hidden" name="trandate" value="{{date('Y-m-d')}}">
            <input type="hidden" name="plan" value="{{$plan}}">
            <input type="hidden" name="duemonth" value="{{$forthemonth}}">
        
        <input type="submit" name="submit" value="Post Penalties!" class="btn btn-warning form form-control"></div>    
            <div class="col-md-6 no-print">
            <h5>Date of Posting</h5>
            <table class="table table-responsive"><tr><td>Date</td><td>Posted By </td></tr>
                @if(count($postings)>0)
                @foreach($postings as $posting)
                <tr><td>{{$posting->dateposted}}</td><td>{{$posting->postedby}}</td></tr>
                @endforeach
                @else
                <tr><td colspan="2">No Posting Yet</td></tr>
                @endif
            </table>    
        </div>    
       </form>  
</div>

@stop
