@extends('appaccounting')
@section('content')



<table cellspacing="2" cellpadding="10">
    <?php 
    $totalassets = 0;
    $totalnoncurrentAssets = 0;
    ?>
    <tr style="text-align: center"><td colspan="3" >ASSETS</td></tr>
    <tr><td colspan="3" style="padding-left:5em"><b>CURRENT ASSETS</b></td></tr>
    <tr><td colspan="3" style="padding-left:7em">Cash on Hand and in Banks</td></tr>
    <?php $cashonhands = \App\ChartOfAccount::where('acctcode','<',110100)->get();
    $rowcount = count($cashonhands);
    $row=1;
    $totalcash = 0;
    ?>
    @foreach($cashonhands as $cashonhand)
    <?php $amount = \App\Dedit::where('accountingcode',$cashonhand->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:10em">{{$cashonhand->accountname}}</td>
        <td style="text-align: right;
            @if($rowcount == $row)
            border-bottom: 1px solid;
            @endif
            "
            >{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php $row++; 
    $totalcash = $totalcash + $amount;
    ?>
    @endforeach
    <?php $totalassets = $totalassets + $totalcash;?>
    <tr><td></td><td></td><td style="text-align: right">{{number_format($totalcash, 2, '.', ', ')}}</td></tr>
    
    
    <?php $tfreceivable = \App\ChartOfAccount::where('acctcode',110100)->get();
    $rowcount = count($tfreceivable);
    $row=1;
    $totaltfreceivable = 0;
    ?>
    @foreach($tfreceivable as $tf)
    <?php $amount = \App\Dedit::where('accountingcode',$tf->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:7em">{{$tf->accountname}}</td>
        <td style="text-align: right;
            @if($rowcount == $row)
            border-bottom: 1px solid;
            @endif
            "
            >{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php $row++; 
    $totaltfreceivable = $totaltfreceivable + $amount;
    ?>
    @endforeach
    <?php $totalassets = $totalassets + $totaltfreceivable;?>
    <tr><td></td><td></td><td style="text-align: right"> {{number_format($totaltfreceivable, 2, '.', ', ')}}</td></tr>
    
    
    
    
    
    <tr><td colspan="3" style="padding-left:7em">Accounts Receivable and Advances</td></tr>
    <?php $ras = \App\ChartOfAccount::where('acctcode','>',110100)->where('acctcode','like','11010%')->get();
    $rowcount = count($ras);
    $row=1;
    $totalra = 0;
    ?>
    @foreach($ras as $ra)
    <?php $amount = \App\Dedit::where('accountingcode',$ra->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:10em">{{$ra->accountname}}</td>
        <td style="text-align: right;
            @if($rowcount == $row)
            border-bottom: 1px solid;
            @endif
            "
            >{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php $row++; 
    $totalra = $totalra + $amount;
    ?>
    @endforeach
    <?php $totalassets = $totalassets + $totalra;?>
    <tr><td></td><td></td><td style="text-align: right">{{number_format($totalra, 2, '.', ', ')}}</td></tr>
    
    
    
    <tr><td colspan="3" style="padding-left:7em">Supplies Inventory</td></tr>
    <?php $inventories = \App\ChartOfAccount::where('acctcode','like','11020%')->get();
    $rowcount = count($inventories);
    $row=1;
    $totalinventories = 0;
    ?>
    @foreach($inventories as $inventory)
    <?php $amount = \App\Dedit::where('accountingcode',$inventory->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:10em">{{$inventory->accountname}}</td>
        <td style="text-align: right;
            @if($rowcount == $row)
            border-bottom: 1px solid;
            @endif
            "
            >{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php $row++; 
    $totalinventories = $totalinventories + $amount;
    ?>
    @endforeach
    <?php $totalassets = $totalassets + $totalinventories;?>
    <tr><td></td><td></td><td style="text-align: right">{{number_format($totalinventories, 2, '.', ', ')}}</td></tr>
    
    
    <?php $prepaid = \App\ChartOfAccount::where('acctcode','like','11030%')->get();
    $rowcount = count($prepaid);
    $row=1;
    $totalprepaid = 0;
    ?>
    @foreach($prepaid as $prep)
    <?php $amount = \App\Dedit::where('accountingcode',$prep->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:7em">{{$prep->accountname}}</td>
        <td style="text-align: right;
            @if($rowcount == $row)
            border-bottom: 1px solid;
            @endif
            "
            >{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php $row++; 
    $totalprepaid = $totalprepaid + $amount;
    ?>
    @endforeach
    <?php $totalassets = $totalassets + $totalprepaid;?>
    <tr><td></td><td></td><td style="text-align: right;border-bottom: 1px solid;">{{number_format($totalinventories, 2, '.', ', ')}}</td></tr>
    
    

    <tr><td></td><td></td><td style="text-align: right;border-bottom: 5px solid;">{{number_format($totalassets, 2, '.', ', ')}}</td></tr>


<!--NON CURRENT-->
    <tr><td colspan="3" style="padding-left:5em"><b>NON-CURRENT ASSETS</b></td></tr>    
    <?php $nctuitions = \App\ChartOfAccount::where('acctcode','like','1200%')->get();
    $rowcount = count($nctuitions);
    $row=1;
    $totalnctuitions = 0;
    ?>
    @foreach($nctuitions as $nctuition)
    <?php $amount = \App\Dedit::where('accountingcode',$nctuition->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:7em">{{$nctuition->accountname}}</td>
        <td style="text-align: right;
            @if($rowcount == $row)
            border-bottom: 1px solid;
            @endif
            "
            >{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php $row++; 
    $totalnctuitions = $totalnctuitions + $amount;
    ?>
    @endforeach
    <?php $totalassets = $totalassets + $totalnctuitions;?>
    <tr><td></td><td></td><td style="text-align: right;border-bottom: 1px solid;">{{number_format($totalnctuitions, 2, '.', ', ')}}</td></tr>

    
    
    
    <tr><td colspan="3" style="padding-left:7em">Deposits and Other Receivables</td></tr>
    <?php 
    $bonds = \App\ChartOfAccount::where('acctcode','like','11040%')->get();
    $otherreceivables = \App\ChartOfAccount::where('acctcode','like','13000%')->get();
    $rowcount = count($otherreceivables);
    $row=1;
    $totalotherreceivables = 0;
    ?>
    @foreach($bonds as $bond)
    <?php $amount = \App\Dedit::where('accountingcode',$inventory->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:10em">{{$bond->accountname}}</td>
        <td style="text-align: right;">{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php
    $totalotherreceivables = $totalotherreceivables + $amount;
    ?>
    @endforeach
    
    @foreach($otherreceivables as $receivables)
    <?php $amount = \App\Dedit::where('accountingcode',$receivables->acctcode)->sum('amount')?>
    <tr>
        <td style="padding-left:10em">{{$receivables->accountname}}</td>
        <td style="text-align: right;">{{number_format($amount, 2, '.', ', ')}}</td>
        <td></td>
    </tr>
    <?php
    $totalotherreceivables = $totalotherreceivables + $amount;
    ?>
    @endforeach
    
    <?php $totalnoncurrentAssets = $totalnoncurrentAssets + $totalotherreceivables;?>
    <tr><td></td><td></td><td style="text-align: right">{{number_format($totalinventories, 2, '.', ', ')}}</td></tr>
    
    
    
    <tr><td colspan="3" style="padding-left:7em">Property and Equipment - net</td></tr>    
    
    
</table>
@stop