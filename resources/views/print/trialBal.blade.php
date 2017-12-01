
<?php 
use App\Http\Controllers\Accounting\Helper as AcctHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       
        
 <style>
@font-face {
    font-family: calibri;
    src: url("<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/fonts/Calibri.ttf");
    font-weight: normal;
}



    .body table, th  , .body td{
    border: 1px solid black;
    font-size: 10pt;
}

td{
    padding-right: 10px;
    padding-left: 10px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 20px;
}

.notice{
    font-size: 10pt;
    padding:5px;
    border: 1px solid #000;
    text-indent: 10px;
    margin-top: 5px;
}
.footer{
  padding-top:10px;
    
}
.heading{
    padding-top: 10px;
    font-size: 12pt;
    font-weight: bold;
}
html,body{
margin-top:80px;
margin-left:10px;
margin-right:10px;
font-family: calibri;
}
#header { position: fixed; left: 0px; top: -80px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
    </style>
</head>
    <body> 
        <?php $title = "Trial Balance";?>
        @include('inludes.footer')
        @include('inludes.header')

        <table class="table table-striped " style="font-size:12pt;">
            <?php 
            $totaldebit = 0;
            $totalcredit = 0;
            
            $entry = count($trials);
            $curr_row = 1;
            ?>
            <thead style="font-weight: bolder"><tr><td width="12%">Acct No.</td><td width="50%">Account Title</td><td width="19%" style="text-align:right">Debit</td><td width="19%" style="text-align:right">Credit</td></tr></thead>
            @foreach($trials as $trial)

            <tr><td @if($entry <= $curr_row)style="border-bottom: 1px solid;"@endif
                    >{{$trial->accountingcode}}</td>
                <td @if($entry == $curr_row) style="border-bottom: 1px solid;"@endif>{{$trial->accountname}}</td>
                <td style="text-align: right; 
                    @if($entry == $curr_row)border-bottom: 1px solid;
                    @endif
                    ">
        @if($trial->entry == 'debit')
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        )
                        @endif
<?php
                    $totaldebit = $totaldebit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2);
?>
@else
{{number_format(0,2)}}
                    @endif
                </td>
                <td style="text-align: right;
                    @if($entry == $curr_row)
                    border-bottom: 1px solid;
                    @endif
                    ">
                    @if($trial->entry == 'credit')
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        (
                        @endif
                    {{number_format(abs(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry)),2)}}
                        @if(round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2)<0)
                        )
                        @endif
<?php
                    $totalcredit = $totalcredit + round(AcctHelper::getaccttotal($trial->credits,$trial->debit,$trial->entry),2);
?>
@else
{{number_format(0,2)}}

                    @endif
                </td>
            </tr>
            <?php $curr_row++; ?>
            @endforeach
            <tr><td colspan="2" style="text-align: right"><b>Total</b></td><td style="text-align: right">{{number_format($totaldebit, 2, '.', ', ')}}</td><td style="text-align: right">{{number_format($totalcredit, 2, '.', ', ')}}</td></tr>
        </table>
    </body>
</html>
