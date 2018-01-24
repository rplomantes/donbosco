<html>
<head>
     <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <style>
      .cash{text-align: right;}
    @page { margin:10px;padding:0px;margin-top: 100px;margin-bottom: 30px;}
    #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
    #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
    table tr td{
        border-bottom: 1px solid;
        border-top: 1px solid;
    }
  </style>
<body>
    
    <div id="header"><span style="font-size:20px;">Don Bosco Technical School</span>
            <h4 style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 15pt;">Debit Memo</h4>
            <p style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">For <span id="dates" >{{date("l, F d, Y",strtotime($trandate))}}</span></p>
        </div>
        <div id="footer">Page <span class="pagenum"></span></div>    
        <div id="content" width="100%">
            <table cellspacing="0" border="0" width="100%" style="font-size: 13px;page-break-inside: auto;">
                <tr>
                    <td>Ref No.</td>
                    <td>Name</td>
                    <td>Debit Sundry</td>
                    <td>E-learning</td>
                    <td>Misc</td>
                    <td>Books</td>
                    <td>Department <br> Facilities</td>
                    <td>Registration</td>
                    <td>Tuition</td>
                    <td>Others</td>
                    <td>Status</td>
                </tr>
                <?php 
                $totaldebits = 0;
                $totalelearning = 0;
                $totalmisc = 0;
                $totalbooks = 0;
                $totaldept = 0;
                $totalreg = 0;
                $totaltuition = 0;
                $totalothers = 0;
                ?>
                @foreach($records as $record)
                <tr style="text-align: right;">
                    <td style="text-align: left;">{{$record->refno}}</td>
                    <td style="text-align: left;">{{$record->receivefrom}}</td>
                    <td>{{number_format($record->debits,2,'.',', ')}}</td>
                    <td>{{number_format($record->elearning,2,'.',', ')}}</td>
                    <td>{{number_format($record->misc,2,'.',', ')}}</td>
                    <td>{{number_format($record->books,2,'.',', ')}}</td>
                    <td>{{number_format($record->dept,2,'.',', ')}}</td>
                    <td>{{number_format($record->registration,2,'.',', ')}}</td>
                    <td>{{number_format($record->tuition,2,'.',', ')}}</td>
                    <td>{{number_format($record->others,2,'.',', ')}}</td>
                    @if($record->stat == 0)
                    <td>OK</td>
                    @else
                    <td>Cancelled</td>
                    @endif

                    @if($record->stat == 0)
                    <?php 
                        $totaldebits = $totaldebits + $record->debits;
                        $totalelearning = $totalelearning + $record->elearning;
                        $totalmisc = $totalmisc + $record->misc;
                        $totalbooks = $totalbooks + $record->books;
                        $totaldept = $totaldept + $record->dept;
                        $totalreg = $totalreg + $record->registration;
                        $totaltuition = $totaltuition + $record->tuition;
                        $totalothers = $totalothers + $record->others;
                    ?>
                    @endif
                </tr>
                @endforeach
                <tr style="background-color: #ffd400;font-weight: bold;text-align: right;">
                    <td colspan="2" style="text-align: center;">Total</td>
                    <td>{{number_format($totaldebits,2,'.',', ')}}</td>
                    <td>{{number_format($totalelearning,2,'.',', ')}}</td>
                    <td>{{number_format($totalmisc,2,'.',', ')}}</td>
                    <td>{{number_format($totalbooks,2,'.',', ')}}</td>
                    <td>{{number_format($totaldept,2,'.',', ')}}</td>
                    <td>{{number_format($totalreg,2,'.',', ')}}</td>
                    <td>{{number_format($totaltuition,2,'.',', ')}}</td>
                    <td>{{number_format($totalothers,2,'.',', ')}}</td>
                    <td></td>
                </tr>
            </table>
        </div>
                    
</body>
</html>


