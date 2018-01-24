<?php use App\Http\Controllers\Accounting\IncomeStatement; ?>
@if($group->type == 4)
<?php 
$hascredit = 0; 
$totalless = 0;
?>
<table border="1" width="50%">
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td colspan="4"><b>{{$group->groupname}}</b></td></tr>
    
    <?php 
    $accounts = App\accounts_group::with(['chartofaccount','ctraccountgroup'])->where('group',$group->id)->get();
    $credits = $accounts->where('chartofaccount.entry','credit');
    $debits = $accounts->where('ctraccountgroup.less',0)->where('chartofaccount.entry','debit');
    $lesses = $accounts->where('ctraccountgroup.less',1)->where('chartofaccount.entry','debit');
    
    $creditSize = count($credits);
    $debitSize = count($debits);
    $lessSize = count($lesses);
    $index = 1;
    ?>
    @foreach($credits as $credit)
        <?php $hascredit = $hascredit + IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date); ?>
        @if($creditSize>1)

            @if($index >= $creditSize)
            <tr>
                <td width="2%"></td>
                <td width="38%">{{$credit->chartofaccount->accountname}}</td>
                <td width="30%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2)}}</td>
                <td>{{number_format($hascredit,2)}}</td>
                <?php $index = 1;?>
            </tr>
            @else
            <tr>
                <td width="2%"></td>
                <td width="38%">{{$credit->chartofaccount->accountname}}</td>
                <td width="30%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($credit->chartofaccount->acctcode,$credit->chartofaccount->entry,$date),2)}}</td>
                <td width="30%"></td>
            </tr>
            @endif

        @else
        <tr>
            <td width="2%"></td>
            <td width="38%">{{$credit->chartofaccount->accountname}}</td>
            <td width="30%"></td>
            <td width="30%" style="text-align: right">{{number_format($hascredit,2)}}</td>
        </tr>
        @endif
        <?php $index++;?>
    @endforeach
    @if($lessSize > 0)
    <tr><td colspan="4">Less:</td></tr>
    @endif
    @foreach($lesses as $less)
    <?php $totalless = $totalless + IncomeStatement::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date); ?>
            <tr>
                <td width="2%"></td>
                <td width="38%">{{$less->chartofaccount->accountname}}</td>
                <td width="30%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($less->chartofaccount->acctcode,$less->chartofaccount->entry,$date),2)}}</td>
                <td width="30%"></td>
            </tr>
    @endforeach
    @if($debitSize > 0)
    <tr><td colspan="4"></td></tr>
    @endif
    @foreach($debits as $debit)
            <tr>
                <td width="2%"></td>
                <td width="38%">{{$debit->chartofaccount->accountname}}</td>
                <td width="30%" style="text-align: right">{{number_format(IncomeStatement::accountTotal($debit->chartofaccount->acctcode,$debit->chartofaccount->entry,$date),2)}}</td>
                <td width="30%"></td>
            </tr>
    @endforeach
</table>
@endif

@if($group->type == 5)
@endif