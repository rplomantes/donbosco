@extends('appaccounting')
@section('content')

<div class="col-md-3">
    </div>    
    <div class="col-md-6">
       
      
       <h5>Reference No : <span style="font-weight: bold; color: red">{{$tdate->refno}}</span></h5>
       <table class = "table table-responsive">
           <tr><td>Student Name : {{$student->lastname}}, {{$student->firstname}} {{$student->extensionname}} {{$student->midddlename}}</td><td></td></tr>
         
           @if(isset($status->level))
           <tr><td>Grade/Sec : {{$status->level}} {{$status->strand}} {{$status->section}}</td><td>Date : {{$tdate->transactiondate}}</td></tr>
           @endif
           <tr><td colspan="2"   valign="top">
           <h5 style="color: red"> Credit </h5>        
           <table width="100%">        
           @foreach($credits as $credit)
           <tr><td>{{$credit->receipt_details}}</td><td align="right">{{number_format($credit->amount,2)}}</td></tr>
           @endforeach
           </table>
           </td></tr> 
            <tr><td colspan="2">
           <h5 style="color: red">Debit</h5>        
           <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt"> 
            @foreach($debits as $debit)
               <tr><td>{{$debit->acctcode}}</td><td align="right">{{number_format($debit->amount,2)}}</td></tr>
           @endforeach
           </table>        
            </td></tr>        
            <tr><td></td><td>Prepared By</td></tr>
            <tr><td></td><td><b>{{$posted->firstname}} {{$posted->lastname}}</b></td></tr>
            <tr><td></td><td>&nbsp;&nbsp;&nbsp;Accounting</td></tr>
       </table>
             <a href="{{url('/accounting',$student->idno)}}" class="btn btn-primary">See Ledger</a>
             <a href="{{url('/printdmcm',array($tdate->refno,$student->idno))}}" class="btn btn-primary">Print DM/CM</a>
             
    </div>    
    </div>



@stop


