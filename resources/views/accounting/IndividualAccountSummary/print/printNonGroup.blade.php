<html>
    <head>
        <style>
<?php
use App\Http\Controllers\Accounting\Helper as AcctHelper;
?>
        @if($request->input('date') == "")
        .trandate{
        display:none;}
        @endif
        
        @if($request->input('refno') == "")
        .ref{
        display:none;}
        @endif
        @if($request->input('name') == "")
        .name{
        display:none;}
        @endif
        @if($request->input('level') == "")
        .level{
        display:none;}
        @endif
        @if($request->input('section') == "")
        .section{
        display:none;}
        @endif
        @if($request->input('debit') == "")
        .debit{
        display:none;}
        @endif
        @if($request->input('credit') == "")
        .credit{
        display:none;}
        @endif
        @if($request->input('entry') == "")
        .entry{
        display:none;}
        @endif        
        @if($request->input('department') == "")
        .dept{
        display:none;}
        @endif
        @if($request->input('office') == "")
        .office{
        display:none;}
        @endif
        @if($request->input('remarks') == "")
        .remarks{
        display:none;}
        @endif
        </style>
    </head>
    <body>
        @include('inludes.header')
        <style>
            body{
                margin-top:80px;
            }
            table{
                font-size: 9pt;
            }
        </style>
        <table width='100%' border='1' cellspacing='0' cellpadding='1'>
            <thead>
                <tr>
                    <th width="9%" class='trandate'>Tran. Date</th>
                    <th class='ref'>Ref. No</th>
                    <th class='name'>Name</th>
                    <th class='level'>Level</th>
                    <th class='section'>Section</th>
                    <th class='debit'>Debit</th>
                    <th class='credit'>Credit</th>
                    <th class='entry'>Entry</th>
                    <th class='dept'>Department</th>
                    <th class='office'>Office</th>
                    <th width="20%" class='remarks'>Remarks</th>
                </tr>
            </thead>
            <tbody>
            @foreach($accounts as $entry)
            <tr>
                <td class='trandate'>{{$entry->transactiondate}}</td>
                <td class='ref'>{{$entry->receiptno}}</td>
                <td class='name'>{{$entry->payee}}</td>
                <td class='level'>{{$entry->level}}</td>
                <td class='section'>{{$entry->section}}</td>
                <td class='debit' align='right'>{{number_format($entry->debit,2)}}</td>
                <td class='credit' align='right'>{{number_format($entry->credit,2)}}</td>
                <td class='entry'>{{AcctHelper::get_entryType($entry->entry_type)}}</td>
                <td class='dept'>{{$entry->acctdepartment}}</td>
                <td class='office'>{{$entry->subdepartment}}</td>
                <td class='remarks'>{{$entry->particular}}</td>
            </tr>
            @endforeach
            <tr>
                <td class='trandate'></td>
                <td class='ref'></td>
                <td class='name'></td>
                <td class='level'></td>
                <td class='section'></td>
                <td class='debit' align='right'>{{number_format($accounts->sum('debit'),2)}}</td>
                <td class='credit' align='right'>{{number_format($accounts->sum('credit'),2)}}</td>
                <td class='entry'></td>
                <td class='dept'></td>
                <td class='office'></td>
                <td class='remarks'></td>
            </tr>
            </tbody>
        </table>
    </body>
</html>