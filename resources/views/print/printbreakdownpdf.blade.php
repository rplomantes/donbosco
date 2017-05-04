
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
    
        <h3 align="center"> Sundry Accounts Breakdown</h3>
<?php
$fromtran = strtotime($fromtran);
$totran = strtotime($totran);
?>
	<p align="center"><b>{{date("Y/m/d",$fromtran)}} - {{date("Y/m/d",$totran)}}</b></p>
	<hr>
        <table class="table table-striped" border="1" cellspacing="0" style="margin-left:20px;margin-right:20px;">
            <?php 
            $total = 0;
            
            ?>
            <thead style="font-weight: bolder"><tr><td>Acct No.</td><td>Account Title</td><td>Amount</td></tr></thead>
            @foreach($breakdown as $breakd)
            <?php 
            $total = $total + $breakd->amount;
            ?>        
            <tr><td>{{$breakd->accountingcode}}</td>
                <td>{{$breakd->acctcode}}</td>
                <td align="right">
                    {{number_format($breakd->amount, 2, '.', ', ')}}
                </td>
            </tr>
            @endforeach
		<tr><td colspan="2" style="text-align: right"><b>Total</b></td>
		<td style="text-align: right"><b>{{number_format($total, 2, '.', ', ')}}</b></td>
		</tr>
        </table>
    </body>
</html>
