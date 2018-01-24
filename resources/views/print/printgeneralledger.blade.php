<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta author="John Vincent Villanueva">
            <meta poweredby = "Nephila Web Technology, Inc">


    <style>
            .header{font-size:16pt;font-weigh:bold}
            body{margin-top: 90px}
            #header { position: fixed; left: 0px; top: -10px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
            #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
            .title{font-size:16pt; font-style: italic; text-decoration: underline}
            .content td {font-size:10pt}
            .subtitle{font-size: 10pt;font-weight: bold}
            #maintable td,#maintable th { border:1px solid}
            </style>
    </head>
    <body>
        <div id="footer">Page <span class="pagenum"></span></div>    
        <div  id="header">
            <table  width ="100%">
               <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"></i></td></tr>
               <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
               <tr>
                   <td colspan="4" align="center">
                       <span class="title">
                            @if($basic == 1)
                            General Ledger Asset Report
                            @elseif($basic == 2)
                            General Ledger Liabilities Report
                            @elseif($basic == 3)
                            General Ledger Equity Report
                            @elseif($basic == 4)
                            General Ledger Income Report
                            @elseif($basic == 5)
                            General Ledger Expense Report
                            @endif
                       </span>
                    </td>
               </tr>
               <tr><td colspan="2" align="center">{{date('M d, Y', strtotime($from))}} to {{date('M d, Y', strtotime($to))}} </td></tr>
            </table>
        </div>
    @if($basic > 0)
        <?php
            if($title == "All"){
                $accounts = App\ChartOfAccount::select(DB::raw('distinct accountname as accounttype,acctcode'))->where('acctcode','like',$basic.'%')->get();
            }
            else{
                $accounts = App\ChartOfAccount::select(DB::raw('distinct accountname as accounttype,acctcode'))->where('acctcode',$title)->get();
            }
        ?>
        @foreach($accounts as $account)
            
            <div>ACCOUNT TITLE:</div>
            <b>{{$account->accounttype}} - {{$account->acctcode}}</b>

            <?php 
            $beginningdebit = 0;
            $beginningcredit = 0;
            $beginningtotal = $beginningdebit-$beginningcredit;
            $monthlygrandcredit = 0;
            $monthlygranddebit = 0;
            ?>
            <table width="100%">
                <thead>
                    <tr style="text-align: right">
                        <td width="16.6%"></td>
                        <td width="16.6%"><u>DEBIT</u></td>
                        <td width="16.6%"></td>
                        <td width="16.6%"><u>CREDIT</u></td>
                        <td width="16.6%"></td>
                        <td width="16.6%"><u>BALANCE</u></td>
                    </tr>
                </thead>
                @if($basic == 1 || $basic == 2 || $basic == 3)
                <tr style="text-align: right">
                    <td style="text-align: left" width="16.6%"><b>Beginning Balance: </b></td>
                    <td width="16.6%">{{number_format($beginningdebit,2,'.',',')}}</td>
                    <td width="16.6%"></td>
                    <td width="16.6%">{{number_format($beginningcredit,2,'.',',')}}</td>
                    <td width="16.6%"></td>
                    <td width="16.6%">{{number_format($beginningtotal,2,'.',',')}}</td>
                    </tr>
                @endif
            </table>
            <?php 
            $date = date("Y",strtotime($from));
            $endOfCycle = $diff;
            $count = 0;
            $startmonth = date("m",strtotime($from));
            $monthlytotal = 0;

            $monthlytotal = $monthlytotal+$beginningtotal;
            ?>
            <?php 
            
            do{ 
                $currmonth = $date."-".$startmonth;
                $getmonth = date("Y-m",strtotime($currmonth));

                $debitentry = DB::Select("SELECT entry_type,sum(if( type='debit', amount, 0 )) as debit,sum(if( type='credit', amount, 0 )) as credit FROM "
                        . "(SELECT SUM( amount ) + SUM( checkamount ) AS amount, entry_type,  'debit' AS type "
                        . "FROM dedits "
                        . "WHERE isreverse =0 AND accountingcode = '$account->acctcode' "
                        . "AND transactiondate LIKE  '".$getmonth."-%' and (transactiondate BETWEEN '$from' AND '$to') GROUP BY entry_type "
                        . "UNION ALL  "
                        . "SELECT SUM( amount ) amount, entry_type,  'credit' "
                        . "FROM credits WHERE isreverse =0 AND accountingcode = '$account->acctcode' "
                        . "AND transactiondate LIKE  '".$getmonth."-%' and (transactiondate BETWEEN '$from' AND '$to') "
                        . "GROUP BY entry_type) s group by entry_type");
                
            $monthlydebit = 0;
            $monthlycredit = 0;
                
            ?>

                @if(count($debitentry)>0)
                <p><u><i><b>{{date("F Y",strtotime($currmonth))}}</b></i></u></p>
                <table width="100%" cellpadding='0' cellspacing='0' border='1'>
                    @foreach($debitentry as $entry)
                        @if($entry->entry_type == 1)
                        <tr  style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;font-size:14px;" width="16.6%">Receipts</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @elseif($entry->entry_type == 2)
                        <tr style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;font-size:14px;" width="16.6%">Debit Memo</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @elseif($entry->entry_type == 3)
                        <tr style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;font-size:14px;" width="16.6%">Journal Entry</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @elseif($entry->entry_type == 4)
                        <tr style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;" width="16.6%">Disbursement</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @elseif($entry->entry_type == 5)
                        <tr style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;font-size:14px;" width="16.6%">System Generated</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @endif
                        <?php
                          
                            $monthlydebit = $monthlydebit + $entry->debit;
                            $monthlycredit = $monthlycredit + $entry->credit;

                        ?>
                    @endforeach
                        <?php
                            if($basic == 1){
                            $monthlytotal = $monthlytotal + ($monthlydebit-$monthlycredit);
                            }
                            elseif($basic == 2){
                            $monthlytotal = $monthlytotal + ($monthlycredit-$monthlydebit);
                            }
                            elseif($basic == 3){
                            $monthlytotal = $monthlytotal + ($monthlydebit-$monthlycredit);
                            }
                            elseif($basic == 4){
                            $monthlytotal = $monthlytotal + ($monthlycredit-$monthlydebit);
                            }
                            elseif($basic == 5){
                            $monthlytotal = $monthlytotal + ($monthlydebit-$monthlycredit);
                            }

                            $monthlygrandcredit = $monthlygrandcredit +$monthlycredit;
                            $monthlygranddebit = $monthlygranddebit +$monthlydebit;
                            
                        ?>
                    <tr style="text-align: right;background-color: #dae9f7;">
                        <td style="text-align: left;font-size:14px;">Monthly Sub Total</td>
                        <td></td>
                        <td><b>{{number_format($monthlydebit,2,'.',',')}}</b></td>
                        <td></td>
                        <td><b>{{number_format($monthlycredit,2,'.',',')}}</b></td>
                        <td>{{number_format($monthlytotal,2,'.',',')}}</td>
                    </tr>
                </table>

                <ta>
                @endif
                <?php 
                if($startmonth == 12){
                    $startmonth = 1;
                    $date++;
                }else{
                    $startmonth = $startmonth+1;
                }

                $count++;
             }while($count< $diff); ?>
                <table width="100%" class="table" cellspacing='0'>
                    
                        <?php
                            if($basic == 1){
                                $totalbalance = $monthlygranddebit-$monthlygrandcredit;
                            }
                            elseif($basic == 2){
                                $totalbalance = $monthlygrandcredit-$monthlygranddebit;
                            }
                            elseif($basic == 3){
                                $totalbalance = $monthlygranddebit-$monthlygrandcredit;
                            }
                            elseif($basic == 4){
                                $totalbalance = $monthlygrandcredit-$monthlygranddebit;
                            }
                            elseif($basic == 5){
                                $totalbalance = $monthlygranddebit-$monthlygrandcredit;
                            }  
                        ?>
                    @if($basic == 1 || $basic == 2 || $basic == 3)
                        <tr  style="text-align: right"><td width="24.9%"  style="text-align: left">Monthly Grand Total</td><td width="24.9%"><u>{{number_format($monthlygranddebit,2)}}</u></td><td width="33.2%"><u>{{number_format($monthlygrandcredit,2)}}</u></td><td width="16.6%"></td></tr>
                    @endif
                    <tr  style="text-align: right"><td width="24.9%"  style="text-align: left"><b>Balance as of</b> {{date("M d Y",strtotime($to))}}</td><td width="24.9%"><u>{{number_format($monthlygranddebit,2)}}</u></td><td width="33.2%"><u>{{number_format($monthlygrandcredit,2)}}</u></td><td width="16.6%"><u>{{number_format($totalbalance,2)}}</u></td></tr>
                </table>
        @endforeach
        @endif
    </body>
</html>

