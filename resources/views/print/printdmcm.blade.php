<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       
        
 <style>
    .body table, th  , .body td{
    border: 1px solid black;
    font-size: 10pt;
}

td{
    padding-right: 10px;
    padding-left: 10px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 15px;
}

.notice{
    font-size: 10pt;
    padding:5px;
    border: 1px solid #000;
    text-indent: 10px;
    margin-top: 5px;
}
.footer{
  padding-top:10px;
    
}
.heading{
    padding-top: 10px;
    font-size: 10pt;
    font-weight: bold;
}
        </style>
	<!-- Fonts -->
	
        </head>
<body> 
    <table border = '0'celpacing="0" cellpadding = "0" width="100%" align="center">
        <tr>
        <td>
            <span style="font-size:15pt;">Don Bosco Technical Institute of Makati, Inc. </span>
        </td></tr><tr>
        <td>
        Chino Roces Ave., Makati City 
        </td></tr><tr>
        <td>
        Tel No : 892-01-01
    </td>
    <td align ="right">Date: {{date('M d,Y',strtotime($debit_dm->transactiondate))}}
     </td>   
    </tr>
    </table>
     <h5 align="center">DEBIT MEMO/CREDIT MEMO</h5>
<table width='80%'>
<tr><td>Student Id</td><td> : </td><td>{{$student->idno}}</td></tr>
<tr><td>Name</td><td> : </td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</td></tr>
@if(isset($status->level))
@if($status->department != "TVET")
    <tr><td>Level</td><td> : </td><td>{{$status->level}}</td></tr>
    @if($status->level == 'Grade 9' || $status->level == 'Grade 10')
    <tr><td>Specialization</td><td> : </td><td>{{$status->strand}}</td></tr>
    @endif
    @if($status->department=="Senior High School")
    <tr><td>Strand</td><td> : </td><td>{{$status->strand}}</td></tr>
    @endif
@else
    <tr><td>Course</td><td> : </td><td>{{$status->course}}</td></tr>
@endif
@endif
</table>
     <div>
                 
           <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
               <tr><td colspan="2" style="background-color: #ccc">Credit</td></tr>  
               
           @foreach($credits as $credit)
           <tr><td>{{$credit->receipt_details}}</td><td align="right">{{number_format($credit->amount,2)}}</td></tr>
           @endforeach
           </table>
           
                 
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr><td colspan="2" style="background-color: #ccc">Debit</td></tr>  
            @foreach($debits as $debit)
               <tr><td>{{$debit->acctcode}}</td><td align="right">{{number_format($debit->amount,2)}}</td></tr>
           @endforeach
           </table>  
           <table border = "0" width="100%">
            </td></tr>        
            <tr><td>&nbsp;&nbsp;</td><td></td></tr>
            <tr><td></td><td align="right"><span style="font-size:10pt">Prepared By : {{$posted->firstname}} {{$posted->lastname}}</span></td></tr>
            <tr><td></td><td align="right"><span style="font-size:9pt">Accounting&nbsp;&nbsp;&nbsp;</span></td></tr>
       </table>
         
     </div>
</body>
</html>