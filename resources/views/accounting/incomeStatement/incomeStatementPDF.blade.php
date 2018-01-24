<?php 
use App\Http\Controllers\Accounting\IncomeStatement; 

$totalIncome = 0;
$totalExpense = 0;
$totalOther = 0;

$incomeIndex = 1;
$expenseIndex = 1;
$otherIndex = 1;

$incomeSize = count($incomeGroups);
$expenseSize = count($expenseGroups);
$otherSize = count($otherGroups);
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
@font-face {
    font-family: calibri;
    src: url("<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/fonts/Calibri.ttf");
    font-weight: normal;
}



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
html,body{
margin-top:85px;
margin-left:10px;
margin-right:10px;
font-family: calibri;
}
body{
    padding-top:20px;
}
#header { position: fixed; left: 0px; top: -80px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
#footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
        </style>
	<!-- Fonts -->
	
        </head>
    <body> 
        <div id="footer">Page <span class="pagenum"></span></div>    
    <div id="header">
        <table border = '0'celpacing="0" cellpadding = "0" width="100%" align="center">
            <tr>
                <td>
                    <p align="center"><span style="font-size:12pt;">Don Bosco Technical Institute of Makati, Inc. </span>
                        <br>
                        Chino Roces Ave., Makati City <br>
                        Tel No : 892-01-01
                    </p>
                </td>
            </tr>
            <tr><td style="font-size:12pt;text-align:center;">( A Non-Stock, Non Profit Corporation)</td></tr>
	    <tr><td style="font-size:12pt;text-align:center;"><b>Statement of Income</b></td></tr>
            <tr><td style="text-align:center;"><b>FOR THE YEAR ENDED</b> {{date('F d, Y', strtotime($date))}}</td></tr>
        </table>
            <hr/>
            <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png"  style="position:absolute;width:108px;height:auto;top:10px;left:120px;">
     </div>
    <h4><b>INCOME</b></h4>
        
        @foreach($incomeGroups as $incomeGroup)
        <?php 
        $totalless = 0;

        $accounts = App\accounts_group::with(['chartofaccount','ctraccountgroup'])->where('group',$incomeGroup->id)->get();
        $credits = $accounts->where('chartofaccount.entry','credit');
        $debits = $accounts->where('ctraccountgroup.less',0)->where('chartofaccount.entry','debit');
        $lesses = $accounts->where('ctraccountgroup.less',1)->where('chartofaccount.entry','debit');

        $groupTotal = IncomeStatement::incomeGroupTotal($credits,$debits,$lesses,$date);
        $totalIncome = $totalIncome + $groupTotal;
        ?>
        <table width="80%" style="font-size: 10pt;page-break-inside: avoid">
        <tr><td colspan="3"><b>{{$incomeGroup->groupname}}</b></td><td style="text-align: right;@if($incomeIndex >= $incomeSize)border-bottom:1px solid;@endif">{{number_format($groupTotal,2)}}</td></tr>

        @foreach($credits as $credit)
            <tr>
                <td width="4%"><span style="color: white">H</span></td>
                <td width="48%">{{$credit->chartofaccount->accountname}}</td>
                <td width="24%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2)}}</td>
                <td width="24%"></td>
            </tr>
        @endforeach

        @foreach($debits as $debit)
            <tr>
                <td width="4%"><span style="color: white">H</span></td>
                <td width="48%">{{$debit->chartofaccount->accountname}}</td>
                <td width="24%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2)}}</td>
                <td width="24%"></td>
            </tr>
        @endforeach

        @if(count($lesses) > 0)
        <tr><td colspan="4">Less:</td></tr>
        @endif
        
        @foreach($lesses as $less)
            <tr>
                <td><span style="color: white">H</span></td>
                <td>{{$less->chartofaccount->accountname}}</td>
                <td style="text-align: right">{{number_format(IncomeStatement::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date),2)}}</td>
                <td></td>
            </tr>
            <?php $totalless = $totalless + round(IncomeStatement::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date),2); ?>
        @endforeach
        @if(count($lesses) > 0)
        <tr><td><span style="color: white">H</span></td><td></td><td style="border-top:1px solid;text-align: right">{{number_format($totalless,2)}}</td><td></td></tr>
        @endif

        <tr><td></td><td></td><td style="border-top: 1px solid"></td><td></td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>
        <?php $incomeIndex++; ?>
        </table>
        @endforeach
        <table width="80%" style="font-size: 10pt;page-break-after: always">
            <tr><td><b>TOTAL INCOME</b></td><td width="23%" style="border-bottom:3px solid;text-align: right">{{number_format($totalIncome,2)}}</td></tr>
        </table>
        
        
        <h4><b>EXPENSE</b></h4>
        
        @foreach($expenseGroups as $expenseGroup)
            <table width="80%" style="font-size: 10pt;" border="1">
            <?php 
            $totalExpense = 0;
            $accounts = App\accounts_group::where('group',$expenseGroup->id)->get();
            $debits = $accounts->where('chartofaccount.entry','debit');
            
            $groupTotal = IncomeStatement::otherGroupTotal($debits,$date);
            $totalExpense = $totalExpense + $groupTotal;
            ?>
            <tr><td colspan="3"><b>{{$expenseGroup->groupname}}</b></td><td style="text-align: right;@if($expenseIndex >= $expenseSize)border-bottom:1px solid;@endif">{{number_format($groupTotal,2)}}</td></tr>
                @if(count($debits)>1)
                    @foreach($debits as $debit)
                        <tr>
                            <td width="4%"><span style="color: white">H</span></td>
                            <td width="48%">{{$debit->chartofaccount->accountname}}</td>
                            <td width="24%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2)}}</td>
                            <td width="24%"></td>
                        </tr>
                    @endforeach
                    
                    <tr><td></td><td></td><td style="border-top: 1px solid"></td><td></td></tr>
                @endif
                <tr><td style="padding-top: 12px;"></td><td></td><td></td><td></td></tr>
            <?php $expenseIndex++; ?>
        </table>
        @endforeach
        <table width="80%" style="font-size: 10pt" border="1">
            <tr><td colspan="3"><b>TOTAL EXPENSE</b></td><td style="border-bottom:3px solid;text-align: right">{{number_format($totalExpense,2)}}</td></tr>
            <tr><td colspan="3"><b>NET INCOME FROM OPERATION</b></td><td>{{number_format($totalIncome - $totalExpense,2)}}</td></tr>
        </table>
        <table width="80%" style="font-size: 10pt" border="1">
        @foreach($otherGroups as $otherGroup)
            <?php 
            $totalOther = 0;
            $accounts = App\accounts_group::where('group',$otherGroup->id)->get();
            $credits = $accounts->where('chartofaccount.entry','credit');
            
            $groupTotal = IncomeStatement::otherGroupTotal($credits,$date);
            $totalOther = $totalOther + $groupTotal;
            ?>
        <tr><td colspan="4" style="padding-top: 2px;"></td></tr>
        <tr><td colspan="3"><b>{{$otherGroup->groupname}}</b></td><td style="text-align: right;@if($otherIndex >= $otherSize)border-bottom:1px solid;@endif">{{number_format($groupTotal,2)}}</td></tr>
            @if(count($credits)>1)
                @foreach($credits as $credit)
                    <tr>
                        <td width="4%"></td>
                        <td width="48%">{{$credit->chartofaccount->accountname}}</td>
                        <td width="24%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2)}}</td>
                        <td width="24%"></td>
                    </tr>
                @endforeach
                <tr>
                    <td width="4%"></td>
                    <td width="48%"></td>
                    <td width="24%" style="border-top: 1px solid"></td>
                    <td width="24%"></td>
                </tr>
            @endif
            <?php 
            $totalIncome = $totalIncome + round(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2);
            $otherIndex++; ?>
        @endforeach
        <tr><td colspan="4" style="padding-top: 5px;"></td></tr>
        <tr><td colspan="3"><b>NET INCOME FOR THE YEAR</b></td><td width="24%" style="text-align: right;border-bottom: 5px solid;border-bottom-style: double"><b>{{number_format($totalIncome - $totalExpense,2)}}</b></td></tr>
    </table>
    </body>
</html>
