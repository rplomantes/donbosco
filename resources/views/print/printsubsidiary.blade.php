<html>
    <head>
        <style type='text/css'>
           .top_header{width:70%}
        </style>
        <style type='text/css' media='print'>
          table tr td{font-size:10pt}
         .top_header{width:100%}
        </style>
    </head>
    <body>
        <!--
        <table style="width: 100%;" cellpadding="10">
            <tdead>
                <tr>
                    <td class="logo"><img src="{{asset('images/logo.png')}}" style="display: inline-block"></td>
                    <td class="school"><h3 style="display: inline-block;">Don Bosco Technical Institute, Makati</h3></td>
                <tr>
            </thead>
            <tbody>
                <tr>
        -->
        <table border = '0' cellpacing="0" cellpadding = "0" class="top_header" align="center">
        <thead>    
        <tr><td rowspan="3" width="65"><img src="{{asset('images/logo.png')}}" style="display: inline-block; width:60"></td><td><span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span></td></tr>
        <tr><td style="font-size:10pt;">Chino Roces Ave., Makati City </td></tr>
        <tr><td style="font-size:10pt;">Tel No : 892-01-01</td></tr>
        <tr><td colspan="2">
                 <!--       <div class="info" style="margin-left: auto;margin-right: auto;">_-->
                            Individual Account Summary </td></tr>
        <tr><td colspan="2">Account Title : {{$request->accountname}}</td>
        @if(isset($request->all))
        @else
        <tr><td colspan="2">Date Covered : {{Date('M d,Y', strtotime($request->from))}} To {{Date('M d,Y',strtotime($request->to))}}</td></tr>
        @endif
        <tr><td colspan="2">&nbsp;</td></tr>
        </thead>
        <tbody>
        <tr><td colspan = "3">
        <table cellspacing="0" border="1" width="100%">
        <thead>
        <tr><td>Tran Date</td><td>OR No</td><td>Name</td><td>Amount</td></tr>
        </thead>
        <tbody>
            <?php $total=0;?>
        @foreach($dblist as $dbl)
        <tr><td>{{$dbl->transactiondate}}</td><td>{{$dbl->receiptno}}</td><td>
                                {{$dbl->lastname}}, {{$dbl->firstname}} {{$dbl->middlename}}
                                </td><td align="right">{{number_format($dbl->amount,2)}}</td></tr>
        <?php $total = $total + $dbl->amount;?>
        @endforeach
        <tr><td colspan = "3">Total</td><td align="right">{{number_format($total,2)}}</td></tr>
                            </tbody>
                        </table>                            
        
            </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
