<?php
use App\Http\Controllers\Helper as MainHelper;

?>
<h5>OFFICIAL RECEIPT: <span style="font-weight: bold; color: red">{{$debits->first()->receiptno}}</span></h5>
<table class = "table table-responsive">
   <tr><td>Received From :{{$idno}} - {{$debits->first()->receivefrom}}</td><td></td></tr>
   <tr><td>Grade/Sec : {{MainHelper::get_level($idno,$schoolyear)}} {{MainHelper::get_strand($idno,$schoolyear)}} {{MainHelper::get_section($idno,$schoolyear)}}</td><td>Date : {{$tdate->transactiondate}}  {{$timeis}}</td></tr>
   <tr>
       <td colspan="2"   valign="top">
            <table width="100%">        
            @foreach($credits as $credit)
            <tr>
                <td>{{$credit->receipt_details}}</td>
                <td align="right">{{number_format($credit->amount,2)}}</td>
            </tr>
            @endforeach
            
    @if($debit_discount>0)
    <tr><td>Less Discount</td><td align="right">({{number_format($debit_discount,2)}})</td></tr>
    @endif
   @if($debit_fape > 0)
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Less FAPE</td><td align="right">({{number_format($debit_fape,2)}})</td></tr>
   @endif
    @if(count($debit_reservation)>0)
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Less Reservation</td><td align="right">({{number_format($debit_reservation,2)}})</td></tr>
   @endif
   
   @if(count($debit_awards)>0)
    @foreach($debit_awards as $debit_award)
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;     {{$debit_award->description}}</td><td align="right">({{number_format($debit_award->amount,2)}})</td></tr>
    @endforeach
   @endif
   
   @if($debit_deposit>0)
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Less Student Deposit</td><td align="right">({{number_format($debit_deposit,2)}})</td></tr>
   @endif
    @if($debit_cash)
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</td><td align="right"><b>{{number_format($debit_cash->amount + $debit_cash->checkamount ,2)}}</b></td></tr>
   @endif

   </table>
   </td></tr> 


    <tr><td colspan="2">
   <table width="60%" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt"> 
       <tr><td>Bank</td> 
           <td>: </td>
           <td align="right">{{$debit_cash->bank_branch}}</td>
           <td rowspan="6" width="20">&nbsp;</td>
           <td rowspan="6" valign="top">Particular: <br><span id='summonningField'>{{$debit_cash->remarks}}</span></tr>
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