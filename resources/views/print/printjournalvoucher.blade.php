<?php
$entries = \App\Accounting::where('refno',$refno)->get();
$remarks = \App\AccountingRemark::where("refno",$refno)->first();
$user = \App\User::where('idno',$remarks->posted_by)->first();
$totaldebit=0;
$totalcredit=0;
?>
<html>
    <head>
        <style>
            span.header{font-size:14pt;font-weight: bold}
            span.title{font-size:16pt; font-style: italic; text-decoration: underline}
        </style>
    </head>
    <body>
        <table width="100%"><tr><td>
        <span class="header">Don Bosco Technical Institute of Makati</span>
        </td>
        <td align="right">
        <span class="title">Journal Voucher</span>
        </td></tr>
            <tr><td><span class = "entrydate">Date Entered : {{date('M d, Y',strtotime($remarks->trandate))}}</span></td>
         <td align="right">       
        <span class="voucherno">JV No : <b>{{$remarks->referenceid}}</b> </span>
         </td></tr>
            <tr>
                <td colspan='2'>Date Applied : {{date('M d, Y',strtotime($entries->pluck('transactiondate')->last()))}}</td>
            </tr>
            <tr><td colspan="2">
        <span class = "postedby"> Posted By : {{strtoupper($user->firstname)}} {{strtoupper($user->lastname)}}</span>
        </td></tr></table>
        <hr>
        <table border = '1' cellspacing = '0' cellpadding = '1' width="100%"><tr><th>Account Title</th><th>Subsidiary</th><th>Office</th><th>Debit</th><th>Credit</th></tr>
            @foreach($entries as $entry)
            <tr><td>{{$entry->accountname}}</td><td>{{$entry->subsidiary}}</td><td>{{$entry->sub_department}}</td><td align="right">{{number_format($entry->debit,2)}}</td><td align="right">{{number_format($entry->credit,2)}}</td></tr>
            <?php
            $totaldebit = $totaldebit + $entry->debit;
            $totalcredit = $totalcredit + $entry->credit;
            ?>
            @endforeach
            <tr><td colspan="3">Total</td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td><td align="right"><b>{{number_format($totalcredit,2)}}</b></td></tr>
            <tr><td colspan="5">
              <label>Explanation/Praticular :</label><br>
              {{$remarks->remarks}}
            </td>
            </tr>
        </table>    
        <br><br>
        <table width="90%"><tr><td>Posted By:</td><td>Audited By :</td><td>Approved By :</td></tr>
            <tr><td></td><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>{{strtoupper($user->firstname)}} {{strtoupper($user->lastname)}}</td><td>_______________</td><td>________________</td></tr>
        </table>    
        
    </body>
</html>
