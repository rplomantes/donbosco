<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;
?>
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
        <table border = '0'celpacing="0" cellpadding = "0" width="550px" align="center">
            <tr>
                <td width="10px"><img src = "{{ asset('/images/logo.png') }}" alt="DBTI" align="middle"  width="70px"/></td>
                <td width="530px">
                    <p align="center"><span style="font-size:20pt;">Don Bosco Technical Institute of Makati, Inc. </span>
                        <br>
                        Chino Roces Ave., Makati City <br>
                        Tel No : 892-01-01
                    </p>
                </td>
            </tr>
        </table>
    
        <h3 align="center"> Trial Balance</h3>
        <table>
            <tr><td>From: {{$fromtran}}</td></tr>
            <tr><td>To: {{$totran}}</td></tr>

            <hr />
        </table>    
        <table class="table table-striped ">
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            
            $entry = count($trials);
            $curr_row = 1;
            ?>
            <thead style="font-weight: bolder"><tr><td>Acct No.</td><td>Account Title</td><td>Debit</td><td>Credit</td></tr></thead>
            @foreach($trials as $trial)

            <tr><td @if($entry <= $curr_row)style="border-bottom: 1px solid;"@endif
                    >{{$trial->accountingcode}}</td>
                <td @if($entry == $curr_row) style="border-bottom: 1px solid;"@endif>{{$trial->accountname}}</td>
                <td style="text-align: right; 
                    @if($entry == $curr_row)border-bottom: 1px solid;
                    @endif
                    ">
@if($entry == $curr_row)border-bottom: 1px solid;
                    @endif
                    ">

                    @if(in_array(substr($trial->accountingcode,0,1),array(1,5)))
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        )
                        @endif
<?php
                    $totaldebit = $totaldebit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2);
?>
@else
{{number_format(0,2)}}

                    @endif
                </td>
                <td style="text-align: right;
                    @if($entry == $curr_row)
                    border-bottom: 1px solid;
                    @endif
                    ">

                    @if(in_array(substr($trial->accountingcode,0,1),array(2,3,4)))
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2)<0)
                        )
                        @endif
<?php
                    $totalcredit = $totalcredit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->accountingcode),2);
?>
@else
{{number_format(0,2)}}

                    @endif
                </td>
            </tr>
            <?php $curr_row++; ?>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>
    </body>
</html>