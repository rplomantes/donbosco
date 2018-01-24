<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        
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
        <h3 align="center"> Debit Memo Journal</h3>
        
    <table>
        <tr><td>From: {{$fromtran}}</td></tr>
        <tr><td>To: {{$totran}}</td></tr>
        
        <hr />
   </table> 
        <table width="100%">
            @foreach($dms as $dm)
            <tr><td>
            <?php
            $accounts = DB::Select("select r.accountingcode,accountname,sum(if( type='credit', amount, 0 ))  as credits,sum(if( type='debit', amount, 0 )) as debit from chart_of_accounts coa join "
                    . "(select accountingcode,'credit' as type,sum(amount) as amount from credits where entry_type=2 and isreverse = '0' and refno='$dm->refno' group by accountingcode "
                    . "UNION ALL "
                    . "select accountingcode,'debit',sum(amount)+sum(checkamount) as amount from dedits where entry_type=2 and isreverse = '0' and refno='$dm->refno' group by accountingcode) r "
                    . "on coa.acctcode = r.accountingcode group by accountingcode order by coa.id");
            ?>
            <br>
            
                
            
            <table width="100%" border="1" cellspacing="0">
                <?php 
                $totaldebit = 0;
                $totalcredit = 0;
                ?>
                <thead>
                    <tr ><th colspan="4" style="text-align:left"><b>Reference No:</b>{{$dm->refno}}</th></tr>
                    <tr><th>Acct No.</th><th>Account Title</th><th>Debit</th><th>Credit</th></tr>
                </thead>
                @foreach($accounts as $account)
                <?php 
                $totaldebit = $totaldebit + $account->debit;
                $totalcredit = $totalcredit + $account->credits;
                ?>        
                <tr><td>{{$account->accountingcode}}</td><td>{{$account->accountname}}</td><td style="text-align: right">
                        @if($account->debit > 0)
                        {{number_format($account->debit, 2, '.', ', ')}}
                        @endif
                    </td><td style="text-align: right">
                        @if($account->credits > 0)
                        {{number_format($account->credits, 2, '.', ', ')}}
                        @endif
                    </td></tr>
                @endforeach
                <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
            </table>
            <br>
            </td></tr>
            @endforeach
        </table>
    </body>
</html>

