<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;
$process = \App\ChartOfAccount::where('acctcode',$title)->first();

$template = 'appaccounting';
if(in_array(Auth::user()->accesslevel,array(env('USER_ACCOUNTING'),env('USER_ACCOUNTING_HEAD')))){
    $template = 'appaccounting';
}elseif(in_array(Auth::user()->accesslevel,array(env('USER_ADMIN')))){
    $template = 'appadmin';
}
?>
@extends($template)
@section('content')
<style>
    @media (min-width: 768px){
        .dl-horizontal dd {
            margin-left: 140px;
        }
        .dl-horizontal dt {
            width:130px;
        }
    }
</style>
<div class='container'>
    <div class="col-md-12">
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Accounts</dt>
            <dd>
                <select class="form-control" id='accounts' onchange = "getAccount(this.value)">
                    <option value = "0" hidden="hidden">-- Select --</option>
                    <option value = "1">Assets</option>
                    <option value = "2">Liabilities</option>
                    <option value = "3">Equity</option>
                    <option value = "4">Income</option>
                    <option value = "5">Expense</option>
                </select>
            </dd>
        </dl>
    </div>
        </div>
        <div class="col-md-12">
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Account Title</dt>
            <dd>
                <select class="form-control" id='title' >
                </select>
            </dd>
        </dl>
    </div>
            </div>
    <div class="col-md-12">
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Covered Period: </dt>
            <dd>
                <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'>
                    <input class="form-control col-md-5" id="fromdate" name="fromdate" value='{{$from}}'>
                </div>
                <div class='col-md-2' style='padding-left: 0px;padding-right: 0px;text-align: center;height: 34px;    padding: 6px 12px;'><b>to</b></div>
                <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'><input class='form-control col-md-5' id="todate" name="todate" value="{{$to}}"></div>
            </dd>
        </dl>
        <a href="#" onclick="gotopage()" class="btn btn-primary navbar-right">View Report</a>
    </div>
        
</div>

<div class="col-md-offset-1 col-md-10">
    @if($basic > 0)
        <?php
            if($title == "All"){
                $accounts = App\ChartOfAccount::select(DB::raw('distinct accountname as accounttype,acctcode'))->where('acctcode','like',$basic.'%')->get();
            }
            else{
                $accounts = App\ChartOfAccount::select(DB::raw('distinct accountname as accounttype,acctcode'))->where('acctcode',$title)->get();
            }
        ?>

        @if($basic == 1)
        <h3> General Ledger Asset Report</h3>
        @elseif($basic == 2)
        <h3> General Ledger Liabilities Report</h3>
        @elseif($basic == 3)
        <h3> General Ledger Equity Report</h3>
        @elseif($basic == 4)
        <h3> General Ledger Income Report</h3>
        @elseif($basic == 5)
        <h3> General Ledger Expense Report</h3>
        @endif
    
        @foreach($accounts as $account)
            
            <h5><dl class="dl-horizontal"><dt>ACCOUNT TITLE:</dt><dd>{{$account->accounttype}}</dd></dl></h5>
            <h5><dl class="dl-horizontal"><dt>ACCOUNT CODE:</dt><dd>{{$account->acctcode}}</dd></dl></h5>
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
            <table width="100%" class="table">
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
                <table width="100%" class="table table-bordered">
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
                <table width="100%" class="table">
                    
                        <?php
                                $totalbalance = AcctHelper::getaccttotal($monthlygrandcredit,$monthlygranddebit,$process->entry);?>
                    @if($basic == 1 || $basic == 2 || $basic == 3)
                        <tr  style="text-align: right"><td width="25%"  style="text-align: left">Monthly Grand Total</td><td width="25%"><u>{{number_format($monthlygranddebit,2)}}</u></td><td width="25%"><u>{{number_format($monthlygrandcredit,2)}}</u></td><td width="25%"></td></tr>
                    @endif
                    <tr  style="text-align: right"><td width="25%"  style="text-align: left"><b>Balance as of</b> {{date("M d Y",strtotime($to))}}</td><td width="25%"><u>{{number_format($monthlygranddebit,2)}}</u></td><td width="25%"><u>{{number_format($monthlygrandcredit,2)}}</u></td><td width="25%"><u>{{number_format($totalbalance,2)}}</u></td></tr>
                </table>
        @endforeach
        <a href='{{url("generalledger/print",array($basic,$title,$from,$to))}}' class="btn btn-primary col-md-12">Print</a>
    @endif
    
    
    
</div>

</div>
    
<script>
    
    function getAccount(group){
        $.ajax({
        type: "GET", 
        url: "/getaccount/" + group, 
        success:function(data){
            
            $('#title').html(data);
          } 
        });
    }
    
    function gotopage(){
        window.location = "/generalledger/"+$('#accounts').val()+"/"+$('#title').val()+"/"+$('#fromdate').val()+"/"+$('#todate').val()
    }
            
</script>
@stop
