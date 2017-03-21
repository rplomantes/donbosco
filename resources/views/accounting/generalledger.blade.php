@extends('appaccounting')
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

<div class='container'>
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

<div class='container'>
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Covered Period: </dt>
            <dd>
                <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'>
                    <input class="form-control col-md-5" readonly="readonly" id="fromdate" name="fromdate" value='{{$from}}'>
                </div>
                <div class='col-md-2' style='padding-left: 0px;padding-right: 0px;text-align: center;height: 34px;    padding: 6px 12px;'><b>to</b></div>
                <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'><input class='form-control col-md-5' id="todate" name="todate" value="{{$to}}"></div>
            </dd>
        </dl>
        <a href="#" onclick="gotopage()" class="btn btn-primary navbar-right">View Report</a>
    </div>
</div>

<div class="container col-md-offset-2 col-md-8">
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
            $beginningtotal = $beginningdebit-$beginningcredit;
            $monthlygrandcredit = 0;
            $monthlygranddebit = 0;
            ?>
            <table width="100%" class="table table-borderless">
                <thead>
                    <tr><td width="25%"></td><td width="25%"><u>DEBIT</u></td><td width="25%"><u>CREDIT</u></td><td width="25%"><u>BALANCE</u></td></tr>
                </thead>
                @if($basic == 1 || $basic == 2 || $basic == 3)
                <tr style="text-align: right"><td style="text-align: left">Beginning Balance: </td><td>{{number_format($beginningdebit,2,'.',',')}}</td><td>{{number_format($beginningcredit,2,'.',',')}}</td><td>{{number_format($beginningtotal,2,'.',',')}}</td></tr>
                @endif
            </table>
            <?php 
            $date = $fiscalyear->fiscalyear."-";
            $endOfCycle = $diff;
            $count = 0;
            $startmonth = 5;
            ?>
            <?php 
            do{ 
                $currmonth = $date.$startmonth;
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
                $monthlytotal = 0;
                
                $monthlytotal = $monthlytotal+$beginningtotal;
                
            ?>
                
                @if(count($debitentry)>0)
                <div><h5><u><i>{{date("F Y",strtotime($currmonth))}}</i></u></h5></div>
                <table width="100%" class="table table-borderless">
                    @foreach($debitentry as $entry)
                        @if($entry->entry_type == 1)
                        <tr  style="text-align: right"><td  style="text-align: left;font-weight:bold;" width="25%">Receipts</td><td width="25%">{{number_format($entry->debit,2,'.',',')}}</td><td width="25%">{{number_format($entry->credit,2,'.',',')}}</td><td width="25%"></td></tr>
                        @elseif($entry->entry_type == 2)
                        <tr style="text-align: right"><td  style="text-align: left;font-weight:bold;" width="25%">Debit Memo</td><td width="25%">{{number_format($entry->debit,2,'.',',')}}</td><td width="25%">{{number_format($entry->credit,2,'.',',')}}</td><td width="25%"></td></tr>
                        @elseif($entry->entry_type == 3)
                        <tr style="text-align: right"><td  style="text-align: left;font-weight:bold;" width="25%">Journal Entry</td><td width="25%">{{number_format($entry->debit,2,'.',',')}}</td><td width="25%">{{number_format($entry->credit,2,'.',',')}}</td><td width="25%"></td></tr>
                        @elseif($entry->entry_type == 4)
                        <tr style="text-align: right"><td  style="text-align: left;font-weight:bold;" width="25%">Disbursement</td><td width="25%">{{number_format($entry->debit,2,'.',',')}}</td><td width="25%">{{number_format($entry->credit,2,'.',',')}}</td><td width="25%"></td></tr>
                        @elseif($entry->entry_type == 5)
                        <tr style="text-align: right"><td  style="text-align: left;font-weight:bold;" width="25%">System Generated</td><td width="25%">{{number_format($entry->debit,2,'.',',')}}</td><td width="25%">{{number_format($entry->credit,2,'.',',')}}</td><td width="25%"></td></tr>
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
                            $monthlytotal = $monthlytotal + ($monthlycredit-$monthlydebit);
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
                    <tr style="text-align: right"><td  style="text-align: left">Monthly Sub Total</td><td>{{number_format($monthlydebit,2,'.',',')}}</td><td>{{number_format($monthlycredit,2,'.',',')}}</td><td>{{number_format($monthlytotal,2,'.',',')}}</td></tr>
                </table>

                <ta>
                @endif
                <?php 
                if($startmonth == 12){
                    $startmonth = 1;
                }else{
                    $startmonth = $startmonth+1;
                }

                $count++;
             }while($count< $diff+1); ?>
                <table width="100%" class="table">
                    
                        <?php
                            if($basic == 1){
                            $totalbalance = $monthlygranddebit-$monthlycredit;
                            }
                            elseif($basic == 2){
                            $totalbalance = $monthlycredit-$monthlygranddebit;
                            }
                            elseif($basic == 3){
                            $totalbalance = $monthlytotal + ($monthlycredit-$monthlydebit);
                            }
                            elseif($basic == 4){
                            $totalbalance = $monthlytotal + ($monthlycredit-$monthlydebit);
                            }
                            elseif($basic == 5){
                            $totalbalance = $monthlytotal + ($monthlydebit-$monthlycredit);
                            }  
                        ?>
                    @if($basic == 1 || $basic == 2 || $basic == 3)
                        <tr  style="text-align: right"><td width="25%"  style="text-align: left">Monthly Grand Total</td><td width="25%"><u>{{number_format($monthlygranddebit,2)}}</u></td><td width="25%"><u>{{number_format($monthlygrandcredit,2)}}</u></td><td width="25%">{{number_format($monthlygrandcredit,2)}}</td></tr>
                    @endif
                </table>
        @endforeach

    @endif
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
        window.location = "/generalledger/"+$('#accounts').val()+"/"+$('#title').val()+"/"+$('#todate').val()
    }
            
</script>
@stop
