<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta poweredby = "Nephila Web Technology, Inc">
    <style>
        .amount{text-align: right;}
        @page { margin:10px;padding:0px;margin-top: 100px;margin-bottom: 30px;}
        #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
        #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
        table tr td{
            border-bottom: 1px solid;
            border-top: 1px solid;
            }
    </style>
    
    </head>
    <body>    
        <div id="header"><span style="font-size:20px;">Don Bosco Technical School</span>
            <h4 style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 15pt;">CONSOLIDATED DEPARTMENTAL
                @if($acctcode == 4)
                INCOME
                @elseif($acctcode == 1)
                ASSETS
                @else
                EXPENSE
                @endif
                SUBSIDIARY LEDGER</h4>
            <p style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">For the period of <span id="dates" ><b>{{date("F d, Y",strtotime($fromtran))}}</b> to <b>{{date("F d, Y",strtotime($totran))}}</b></span></p>
        </div>
        <div id="footer">Page <span class="pagenum"></span></div>
<?php
use App\Http\Controllers\Accounting\OfficeSumController;
use App\Http\Controllers\Accounting\DeptIncomeController;

$total = 0;
?>
        <table class="table table-striped" width="100%" cellspacing="0" style="font-size:15px">
            <thead>
                <tr>
                    <th width="23%">ACCOUNT TITLE</th>
                    <th>Total 
                        @if($acctcode == 4)
                            Income
	                @elseif($acctcode == 1)
		            Asset
                        @else
                            Expense
                        @endif
                    </th>
                    @foreach($departments as $department)
                    <th align="right">{{str_replace(" Department","",$department)}}</th>
                    @endforeach
                </tr>
            </thead>
                @foreach($coas as $coa)
                @if(DeptIncomeController::showAcct($accounts,$departments,$coa->acctcode,$acctcode))
                    <?php
                    $acctTotal = OfficeSumController::accounttotal($accounts,$coa->acctcode,$acctcode);
                    $total = $total + $acctTotal;
                    ?>
                <tr>
                    <td>{{$coa->accountname}}</td>
                    <td align="right">{{number_format($acctTotal,2,' .',',')}}</td>
                    @foreach($departments as $department)
                    <td align="right">
                        {{OfficeSumController::accountdepttotal($accounts,$department,$coa->acctcode,$acctcode)}}
                    </td>
                    @endforeach
                </tr>
                @endif
                @endforeach
                <tr>
                    <td>Grand Total</td>
                    <td align="right">{{number_format($total,2,' .',',')}}</td>
                    @foreach($departments as $department)
                    <td align="right">
                        {{OfficeSumController::deptTotal($accounts,$department,$acctcode)}}
                    </td>
                    @endforeach
                </tr>
        </table>
    </body>
</html>




