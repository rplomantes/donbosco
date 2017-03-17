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
            div.header{font-size:14pt;font-weight: bold}
            div.title{font-size:14pt}
        </style>
    </head>
    <body>
        <div class="header">Don Bosco Technical Institute of Makati</div>
        <div class="title">Journal Voucher</div>
        <div class="voucherno">Journal Voucher No : <b>{{$remarks->referenceid}}</b> </div>
        <div class = "entrydate">Date Entered : {{date('M d, Y',strtotime($remarks->trandate))}}</div>
        <div class = "postedby"> Posted By : {{strtoupper($user->firstname)}} {{strtoupper($user->lastname)}}</div>
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
