<?php
$lists = \App\AccountingRemark::where('trandate', $trandate)->where('posted_by',\Auth::user()->idno)->get();
?>
<html>
</head>
 <style>
        .header{font-size:16pt;font-weigh:bold}
        .title{font-size:16pt; font-style: italic; text-decoration: underline}
        .content td {font-size:10pt}
        .subtitle{font-size: 10pt;font-weight: bold}
        </style>
</head>
<body>
    
 <table  width ="100%">
            <tr><td><span class="header">Don Bosco Technical Institute of Makati</span></td><td align="right"><span class="title">Journal Entry Daily Summary</span></i></td></tr>
            <tr><td colspan="2">Chino Roces Avenue, Makati, Metro Manila</td></tr>
            <tr><td colspan="2">Date: {{date('M d, Y', strtotime($trandate))}}</td></tr>
        </table>

        <table border ="1"   cellspacing = "0" class="content" width="100%"><tr><td>Journal Voucher No</td><td>Remarks</td><td>Amount</td><td>Status</td></tr>
           @foreach($lists as $list)
           <tr><td>{{$list->referenceid}}</td><td>{{$list->remarks}}</td><td>{{$list->amount}}</td>
           <td>@if($list->isreverse == "0")
               OK
               @else
               Cancelled
               @endif
           </td>    
           </tr>
           @endforeach 
        </table>
       
  <br>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
            <tr valign="top"><td width="33%">Prepared By:<br><br><i>{{\Auth::user()->firstname}} {{\Auth::user()->lastname}}</i></td><td width="33%">Checked By:</td><td>Approved By:</td></tr>
</table>  
  
  </body>
  </html>
