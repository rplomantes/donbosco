<!DOCTYPE html>
<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;

$process = \App\ChartOfAccount::where('acctcode',$title)->first();
?>
<html lang="en">
    <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta author="John Vincent Villanueva">
            <meta poweredby = "Nephila Web Technology, Inc">


    <style>
        @font-face {
            font-family: calibri;
            src: url("<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/fonts/Calibri.ttf");
            font-weight: normal;
        }
            
            #header { position: fixed; left: 0px; top: -80px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
            #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
            .subtitle{font-size: 10pt;font-weight: bold}
            #maintable td,#maintable th { border:1px solid}
        html,body{
        margin-top:80px;
        margin-left:10px;
        margin-right:10px;
        font-family: calibri;
        }
    </style>
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
                <tr><td style="font-size:12pt;text-align:center;"><b>
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
                        </b></td></tr>
                <tr><td style="text-align:center;"><b>For the period</b> {{date('M d, Y', strtotime($fromdate))}} to {{date('M d, Y', strtotime($todate))}}</td></tr>
            </table>
                <hr/>
                <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png"  style="position:absolute;width:108px;height:auto;top:10px;left:120px;">
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
            
            <div>ACCOUNT TITLE:<b>{{$account->accounttype}}</b></div>
            <div>ACCOUNT CODE:<b>{{$account->acctcode}}</b></div>
            <br>
            <?php
            $beginningdebit = 0;
            $beginningcredit = 0;
                $debitentry = DB::Select("SELECT entry_type,sum(if( type='debit', amount, 0 )) as debit,sum(if( type='credit', amount, 0 )) as credit FROM "
                        . "(SELECT SUM( amount ) + SUM( checkamount ) AS amount, entry_type,  'debit' AS type "
                        . "FROM dedits "
                        . "WHERE isreverse =0 AND accountingcode = '$account->acctcode' "
                        . "AND (transactiondate BETWEEN '$from' AND '$to') GROUP BY entry_type "
                        . "UNION ALL  "
                        . "SELECT SUM( amount ) amount, entry_type,  'credit' "
                        . "FROM credits WHERE isreverse =0 AND accountingcode = '$account->acctcode' "
                        . "AND (transactiondate BETWEEN '$from' AND '$to') "
                        . "GROUP BY entry_type) s where entry_type = 7 group by entry_type");
                
                foreach($debitentry as $forward){
                    $beginningdebit = $beginningdebit + $forward->debit;
                    $beginningcredit = $beginningcredit + $forward->credit;
                }

            $beginningtotal = AcctHelper::getaccttotal($beginningcredit,$beginningdebit,$process->entry);
            $monthlygrandcredit = $beginningcredit;
            $monthlygranddebit = $beginningdebit;
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
                        . "GROUP BY entry_type) s where entry_type != 7 group by entry_type");
                
            $monthlydebit = 0;
            $monthlycredit = 0;
                
            ?>

                @if(count($debitentry)>0)
                <div><h5><u><i><b>{{date("F Y",strtotime($currmonth))}}</b></i></u></h5></div>                
                <table width="100%" class="table table-bordered" style="font-size:12pt;page-break-inside: avoid">
                    @foreach($debitentry as $entry)
                        @if($entry->entry_type == 1)
                        <tr  style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;" width="16.6%">Receipts</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @elseif($entry->entry_type == 2)
                        <tr style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;" width="16.6%">Debit Memo</td>
                            <td width="16.6%">{{number_format($entry->debit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%">{{number_format($entry->credit,2,'.',',')}}</td>
                            <td width="16.6%"></td>
                            <td width="16.6%"></td>
                        </tr>
                        @elseif($entry->entry_type == 3)
                        <tr style="text-align: right">
                            <td  style="text-align: left;font-weight:bold;" width="16.6%">Journal Entry</td>
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
                            <td  style="text-align: left;font-weight:bold;" width="16.6%">System Generated</td>
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
                            
                            $monthlytotal = AcctHelper::getaccttotal($monthlycredit,$monthlydebit,$process->entry);

                            $monthlygrandcredit = $monthlygrandcredit +$monthlycredit;
                            $monthlygranddebit = $monthlygranddebit +$monthlydebit;
                            
                        ?>
                    <tr style="text-align: right;background-color: #dae9f7;">
                        <td  style="text-align: left">Monthly Sub Total</td>
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
                <table width="100%">
                    
                        <?php
                                $totalbalance = AcctHelper::getaccttotal($monthlygrandcredit,$monthlygranddebit,$process->entry);?>
                    @if($basic == 1 || $basic == 2 || $basic == 3)
                    <tr  style="text-align: right">
                        <td width="33.6%"  style="text-align: left">Monthly Grand Total</td>
                        <td width="16.6%"><u>{{number_format($monthlygranddebit,2)}}</u></td>
                        <td width="16.6%"></td>
                        <td width="16.6%"><u>{{number_format($monthlygrandcredit,2)}}</u></td>
                        <td width="16.6%"></td>
                    </tr>
                    @endif
                    <tr  style="text-align: right"><td width="33.6%"  style="text-align: left"><b>Balance as of</b> {{date("M d Y",strtotime($to))}}</td><td width="16.6%"><u>{{number_format($monthlygranddebit,2)}}</u></td><td width="16.6%"></td><td width="16.6%"><u>{{number_format($monthlygrandcredit,2)}}</u></td><td style="border-bottom: 1px solid;" width="16.6%">{{number_format($totalbalance,2)}}</td></tr>
                </table>
        @endforeach
        @endif
    </body>
</html>

