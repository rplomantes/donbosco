<html>
<head>

<style>
@page { margin: 0px; }
body { margin: 0px; }
@media print {
    table {page-break-after: avoid;}
    
}
</style>
</head> 
<body>   
<div style="margin-left: 20px ;font-size: 10pt; width: 311">
 <table width="311" cellpadding = "0" cellspacing = "0" border = "0">
   <tr><td colspan="2" height="78"  valign="top"></td></tr>
   <tr><td><div style="margin-left: 110px">{{$student->idno}} - {{$student->lastname}}, {{$student->firstname}} {{$student->extensionname}} {{$student->midddlename}}</div></td><td height="20" valign="top"></td></tr>
 </table>
 <table width="311" celpadding ="0" celspacing = "0">
     @if(isset($status->level))
     <tr><td><div style="margin-left: 100px">{{$status->level}} {{$status->strand}} {{$status->section}}</div></td><td><div style="font-size:9pt">{{$tdate->transactiondate}} <br >{{$timeis}}</div></td></tr>
     @else
     <tr><td><div style="margin-left: 100px"></div></td><td>{{$tdate->transactiondate}}</td></tr>
     @endif
     <tr><td colspan="2" height="28"></td></tr>       
     <tr><td colspan="2" height="190"  valign="top" style="padding-left:20px">
           <table width="280" cellpadding = "0" cellspacing = "0" border="0">        
                @foreach($credits as $credit)
                <tr><td>{{$credit->receipt_details}}-{{$credit->sub_department}}</td><td align="right">{{number_format($credit->amount,2)}}</td></tr>
                @endforeach
           
                @if(count($debit_discount)>0)
                <tr><td>Less Discount</td><td align="right">({{number_format($debit_discount->amount,2)}})</td></tr>
                @endif
           
                @if(count($debit_reservation)>0)
                <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Less Reservation</td><td align="right">({{number_format($debit_reservation->amount,2)}})</td></tr>
                @endif
            
                <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</td><td align="right"><b>{{number_format($debit_cash->amount + $debit_cash->checkamount,2)}}</b></td></tr>
                <tr><td colspan="2">&nbsp;&nbsp;</td></tr>
                <tr><td colspan="2">
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size:9pt">        
                        <tr><td>Check Amount<td> :</td> </td><td align="right">{{number_format($debit_cash->checkamount,2)}}</td><td rowspan="4" width="10">&nbsp;</td><td rowspan="4" valign="top">Particular : <br> {{$debit_cash->remarks}}</td></tr>
                        <tr><td>Cash Amount <td> :</td> </td><td align="right">{{number_format($debit_cash->amount,2)}}</td></tr> 
                        <tr><td>Cash Rendered <td> :</td> </td><td align="right">{{number_format($debit_cash->receiveamount,2)}}</td></tr> 
                        <tr><td>Change <td> :</td> </td><td align="right">{{number_format($debit_cash->receiveamount-$debit_cash->amount ,2)}}</td></tr> 
                                         
                        @if(isset($status->status))
                            @if($status->status=='2')
                        <tr><td colspan="2" ><span style="font-size:12pt;font-weight:bold">ENROLLED</span></td></tr>
                            @endif
                        @endif
                    </table> 
                </td></tr>
            </table>
        </td>
     </tr>
 </table>
 <table width = "100%" border="0">           
   <tr><td colspan="2"><span style="margin-left: 70px">{{$debit_cash->bank_branch}}</span></td></tr>
   <tr><td><span style="margin-left: 80px">{{$debit_cash->check_number}} - {{$debit_cash->checkamount}}</span></td><td align="right">{{$posted->firstname}} {{$posted->lastname}}</td></tr>
   <tr><td colspan = "2"></td></tr>
 </table>
 <table width="100%" border = "0">
   <tr><td width="30%"></td><td>OR : <span style = "font-size:10pt; font-weight:bold">{{$tdate->receiptno}}</span></td></tr>
   <tr><td></td><td>Ref No : <span style = "font-size:10pt; font-weight:bold">{{$tdate->refno}}</span></td></tr>
   
 </table>
</div>    
</body>
</html>
