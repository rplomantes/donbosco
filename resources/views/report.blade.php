<?php
$cashtuition = $totaltuition - ($totaldisctuition+$totalfapetuition+$totaldeptuition);
$cashreg = $totalreg - ($totaldiscreg+$totalfapereg+$totaldepreg+$totalregreservation);
$cashdept = $totaldept - ($totaldiscdept+$totalfapedept+$totaldepdept);
$cashelearnig = $totalelearnig - ($totaldiscelearnig+$totalfapeelearnig+$totaldepelearnig);
$cashmisc = $totalmisc - ($totaldiscmisc+$totalfapemisc+$totaldepmisc);
$cashbook = $totalbook - ($totaldiscbook+$totalfapebook+$totaldepbook);
$cashreservation = $totalbook - ($totaldebreservation);
$cashother = $totalother - ($totalfapeother+$totaldepother);
?>

<table>
    <tr>
        <td>Acocunts</td>
        <td>Cash</td>
        <td>Discount</td>
        <td>FAPE</td>
        <td>Deposit</td>
        <td>Reservation</td>
    </tr>
    <tr>
        <td>Tuition and Other Fees Receivable</td>
        <td>{{$cashtuition}}</td>
        <td>{{$totaldisctuition}}</td>
        <td>{{$totalfapetuition}}</td>
        <td>{{$totaldeptuition}}</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Registration and Other Fees</td>
        <td>{{$cashreg}}</td>
        <td>{{$totaldiscreg}}</td>
        <td>{{$totalfapereg}}</td>
        <td>{{$totaldepreg}}</td>
        <td>{{$totalregreservation}}</td>
    </tr>
    <tr>
        <td>Department Facilities</td>
        <td>{{$cashdept}}</td>
        <td>{{$totaldiscdept}}</td>
        <td>{{$totalfapedept}}</td>
        <td>{{$totaldepdept}}</td>
        <td>0</td>
    </tr>
    <tr>
        <td>E-Learning, Test & Workbook</td>
        <td>{{$cashelearnig}}</td>
        <td>{{$totaldiscelearnig}}</td>
        <td>{{$totalfapeelearnig}}</td>
        <td>{{$totaldepelearnig}}</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Other Miscellaneous Fees</td>
        <td>{{$cashmisc}}</td>
        <td>{{$totaldiscmisc}}</td>
        <td>{{$totalfapemisc}}</td>
        <td>{{$totaldepmisc}}</td>
        <td>0</td>     
    </tr>
    <tr>
        <td>Sales Non-VAT Books</td>
        <td>{{$cashbook}}</td>
        <td>{{$totaldiscbook}}</td>
        <td>{{$totalfapebook}}</td>
        <td>{{$totaldepbook}}</td>
        <td>0</td>
    </tr>
    <tr>
        <td>Reservation</td>
        <td>{{$totalreservation}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$totaldebreservation}}</td>
    </tr>
    <tr>
        <td>Others</td>
        <td>{{$cashother}}</td>
        <td>0</td>
        <td>{{$totalfapeother}}</td>
        <td>{{$totaldepother}}</td>
        <td></td>
    </tr>
</table>
<table>
    <tr>
        <td>Receipt No.</td>
        <td>Transaction Date</td>
        <td>From</td>
        <td>Misc</td>
        <td>Books</td>
        <td>Elearning</td>
        <td>Department <br> Facilities</td>
        <td>Registration</td>
        <td>Tuition</td>
        <td>Others</td>
    </tr>
    @foreach($depositbreakdown as $deposit)
    <tr>
        <td>{{$deposit['receipt']}}</td>
        <td>{{$deposit['trans']}}</td>
        <td>{{$deposit['payer']}}</td>
        <td>{{$deposit['deposit']}}</td>
        <td>{{$deposit['misc']}}</td>
        <td>{{$deposit['book']}}</td>
        <td>{{$deposit['elearnign']}}</td>
        <td>{{$deposit['department']}}</td>
        <td>{{$deposit['registration']}}</td>
        <td>{{$deposit['tuition']}}</td>
        <td>{{$deposit['sundry']}}</td>
    </tr>
    @endforeach
</table>
</div>

