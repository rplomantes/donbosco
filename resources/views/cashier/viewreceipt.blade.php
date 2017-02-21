@extends('appcashier')

@section('content')
<div class="container">
    <div class="col-md-3">
    </div>    
    <div class="col-md-6">
       
      
       <h5>OFFICIAL RECEIPT : <span style="font-weight: bold; color: red">{{$tdate->receiptno}}</span></h5>
       <table class = "table table-responsive">
           <tr><td>Received From : {{$student->lastname}}, {{$student->firstname}} {{$student->extensionname}} {{$student->midddlename}}</td><td></td></tr>
         
           @if(isset($status->level))
           <tr><td>Grade/Sec : {{$status->level}} {{$status->strand}} {{$status->section}}</td><td>Date : {{$tdate->transactiondate}}  {{$timeis}}</td></tr>
           @endif
           <tr><td colspan="2"   valign="top">
           <table width="100%">        
           @foreach($credits as $credit)
           <tr><td>{{$credit->receipt_details}}-{{$credit->sub_department}}</td><td align="right">{{number_format($credit->amount,2)}}</td></tr>
           @endforeach
           @if(count($debit_discount)>0)
            <tr><td>Less Discount</td><td align="right">({{number_format($debit_discount->amount,2)}})</td></tr>
           @endif
            @if(count($debit_reservation)>0)
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Less Reservation</td><td align="right">({{number_format($debit_reservation->amount,2)}})</td></tr>
           @endif
           
            @if(count($debit_cash)>0)
            <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</td><td align="right"><b>{{number_format($debit_cash->amount + $debit_cash->checkamount ,2)}}</b></td></tr>
           @endif
           
           </table>
           </td></tr> 
  
          
            <tr><td colspan="2">
           <table width="60%" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt"> 
               <tr><td>Bank</td> <td>: </td><td align="right">{{$debit_cash->bank_branch}}</td><td rowspan="6" width="20">&nbsp;</td><td rowspan="6" valign="top">Particular: <br>{{$debit_cash->remarks}}</tr>
            <tr><td>Check No</td> <td>: </td><td align="right">{{$debit_cash->check_number}}</td></tr>       
          <tr><td>Check Amount<td> :</td> </td><td align="right">{{number_format($debit_cash->checkamount,2)}}</td></tr>
          <tr><td>Cash Amount <td> :</td> </td><td align="right">{{number_format($debit_cash->amount,2)}}</td></tr> 
          <tr><td>Cash Rendered <td> :</td> </td><td align="right">{{number_format($debit_cash->receiveamount,2)}}</td></tr> 
          <tr><td>Changed <td> :</td> </td><td align="right">{{number_format($debit_cash->receiveamount-$debit_cash->amount ,2)}}</td></tr> 
          </table>        
            </td></tr>        
            <tr><td></td><td>Received By</td></tr>
            <tr><td></td><td><b>{{$posted->firstname}} {{$posted->lastname}}</b></td></tr>
            <tr><td></td><td>&nbsp;&nbsp;&nbsp;Cashier</td></tr>
       </table>
            @if(Auth::user()->accesslevel==env('USER_ACCOUNTING')|| Auth::user()->accesslevel==env('USER_ACCOUNTING_HEAD'))
                <a href="{{url('/accounting',$student->idno)}}" class="btn btn-primary">See Ledger</a>
            @else
                <a href="{{url('/cashier',$student->idno)}}" class="btn btn-primary">See Ledger</a>
            @endif
             <a href="{{url('/printreceipt',array($tdate->refno,$student->idno))}}" id="printreceipt" class="btn btn-primary">Print Receipt</a>
             @if($tdate->transactiondate == date('Y-m-d') && Auth::user()->idno == $posted->idno)
                @if($tdate->isreverse == '0')
                <a href="{{url('/cancell',array($tdate->refno,$student->idno))}}" class="btn btn-danger pull-right" onclick="return confirm('Are you sure?')">Cancel</a>
                @else
                <a href="{{url('/restore',array($tdate->refno,$student->idno))}}" class="btn btn-danger pull-right" onclick="return confirm('Are you sure?')">Restore</a>
                @endif
              @endif
    </div>    
    </div>
<script>
    $(document).ready(function(){
        $('#printreceipt').focus();
    })
</script>    
@stop
