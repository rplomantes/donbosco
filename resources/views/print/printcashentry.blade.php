
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
    height: 20px;
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
    font-size: 12pt;
    font-weight: bold;
}
        </style>
	<!-- Fonts -->
	
        </head>
<body> 
    <table border = '0'celpacing="0" cellpadding = "0" width="550px" align="center"><tr><td width="10px">
        <img src = "{{ asset('/images/logo.png') }}" alt="DBTI" align="middle"  width="70px"/></td>
            <td width="530px"><p align="center"><span style="font-size:20pt;">Don Bosco Technical Institute of Makati, Inc. </span><br>
        Chino Roces Ave., Makati City <br>
        Tel No : 892-01-01
        </p>
    </td>
    </tr>
    </table>
    
    <h3 align="center"> Transaction Report</h3>
    <table>
        <tr><td>From: {{$fromtran}}</td></tr>
        <tr><td>To: {{$totran}}</td></tr>
        
        <hr />
   </table>    
    <div class="body">
    <h5>Credit</h5>
    <table class="table table-striped">
        <tr><td>Account Name</td><td>Amount</td></tr>
        <?php $totalcredit=0;?>
        @foreach($credits as $credit)
        <?php $totalcredit = $totalcredit + $credit->amount;?>
        <tr><td>{{$credit->acctcode}}</td><td align="right">{{number_format($credit->amount,2)}}</td></tr>
        @endforeach
        <tr><td><b>Total Credit</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
     </table>   
    
<div class="container">
<h5>Debit</h5>
    <table class="table table-striped">
    <tr><td>Partcular</td><td align="right">Amount</td></tr>
    <?php $totaldebits=0;?>    
    @if(count($debitcashchecks)>0)
    @foreach($debitcashchecks as $debitcashcheck)
    <?php $totaldebits = $totaldebits + $debitcashcheck->totalamount;?>
    <tr><td>{{$debitcashcheck->acctcode}} {{$debitcashcheck->depositto}}</td><td align="right">{{number_format($debitcashcheck->totalamount,2)}}</td></tr>
    @endforeach    
    @endif
    
    
    
   <tr><td><b>Total Debit</b></td><td align="right"><b>{{number_format($totaldebits,2)}}</b></td></tr> 
</table>
        </div>
    </body>
    </html>