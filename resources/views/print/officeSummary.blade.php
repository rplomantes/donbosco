<html>
<head>
     <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
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
    <body>    
        <div id="header"><span style="font-size:20px;">Don Bosco Technical School</span>
            <h4 style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 15pt;">DEPARTMENTAL
                @if($acctcode == 4)
                INCOME
                @elseif($acctcode == 1)
                ASSETS
                @else
                EXPENSE
                @endif
                SUBSIDIARY LEDGER - {{$dept}}</h4>
            <p style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">For the period of <span id="dates" ><b>{{date("F d, Y",strtotime($fromdate))}}</b> to <b>{{date("F d, Y",strtotime($todate))}}</b></span></p>
        </div>
        <div id="footer">Page <span class="pagenum"></span></div>
    <?php 
    use App\Http\Controllers\Accounting\OfficeSumController;

    $currAccount = 0;
    $total = 0;
    ?>
    <table  width="100%" cellspacing="0" style="font-size:15px">
        <thead>
        <tr>
            <td>Account Title</td>
            <td style="text-align: right">Total Amount</td>
            @foreach($offices as $office)
            <td style="text-align: right">{{$office->sub_department}}</td>
            @endforeach
        </tr>
        </thead>
        
        @foreach($coas as $coa)
            <?php
            $acctTotal = OfficeSumController::accounttotal($accounts,$coa->acctcode,$acctcode);
            $total = $total + $acctTotal;
            ?>
            @if(OfficeSumController::showAcct($accounts,$offices,$coa->acctcode,$acctcode))
                <tr>
                    <td>{{$coa->accountname}}</td>

                    <td style="text-align: right">{{number_format($acctTotal,2,' .',',')}}</td>
            
                    @foreach($offices as $office)
                    <td style="text-align: right">
                        {{OfficeSumController::accountdepttotal($accounts,$office->sub_department,$coa->acctcode,$acctcode)}}
                    </td>
                    @endforeach            
                </tr>
            @endif
        @endforeach
        
        <tr>
            <td style="text-align: right;padding-right: 40px">Grand Total</td>
            <td style="text-align: right">{{number_format($total,2,' .',',')}}</td>
            @foreach($offices as $office)
            <td style="text-align: right">
                {{OfficeSumController::deptTotal($accounts,$office->sub_department,$acctcode)}}
            </td>
            @endforeach
        </tr>
    </table>
    </body>
</html>


