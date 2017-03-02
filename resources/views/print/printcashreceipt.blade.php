<html>
<head>
     <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <style>
    @page { margin:10px;padding:0px;margin-top: 100px;margin-bottom: 30px;}
    #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
    #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); } </style>
  </style>
<body>
  <div id="header">Don Bosco Technical School
            <h4 style="text-align: center;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">Cash Receipt</h4>
        <p style="text-align: center;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">For <span id="dates" >{{$asOf}}</span></p>
  </div>
    <div id="footer">
      Page <span class="pagenum"></span>
    </div>
  <div id="content" width="100%" >
    <table cellspacing="0" border="0" width="100%" style="font-size: 10pt;page-break-inside: auto;">
            <thead>
                <tr>
                    <td class="receipt" width="50px">OR No.</td>
                    <td class="name" width="280px">Name</td>
                    <td class="dcc" style="text-align: right;width:100px;">Debit <br> Cash/Check</td>
                    <td class="ddiscount" style="text-align: right;width:80px;">Debit <br>Discount</td>
                    <td class="dreserve" style="text-align: right;width:80px;">Debit <br> Reservation</td>
                    <td class="elearn" style="text-align: right;width:80px;">E-learning</td>
                    <td class="misc" style="text-align: right;width:80px;">Misc</td>
                    <td class="book" style="text-align: right;width:80px;">Books</td>
                    <td class="dept" style="text-align: right;width:80px;">Department <br> Facilities</td>
                    <td class="reg" style="text-align: right;width:80px;">Registration</td>
                    <td class="tuition" style="text-align: right;width:80px;">Tuition</td>
                    <td class="reserv" style="text-align: right;width:80px;">Reservation</td>
                    <td class="others" style="text-align: right;width:80px;">Others</td>
                    <td class="stat" style="text-align: right;width:50px;">Status</td>
                </tr>
                <tr style="text-align: right">
                    <td colspan="2" style="text-align: left">Balance brought forward</td>
                    <td class="dcc">{{number_format($totalcash,2)}}</td>
                    <td class="ddiscount">{{number_format($totaldiscount,2)}}</td>
                    <td class="dreserve">{{number_format($drreservation,2)}}</td>
                    <td class="elearn">{{number_format($elearningcr,2)}}</td>
                    <td class="misc">{{number_format($misccr,2)}}</td>
                    <td class="book">{{number_format($bookcr,2)}}</td>
                    <td class="dept">{{number_format($departmentcr,2)}}</td>
                    <td class="reg">{{number_format($registrationcr,2)}}</td>
                    <td class="tuition">{{number_format($tuitioncr,2)}}</td>
                    <td class="reserv">{{number_format($crreservation,2)}}</td>
                    <td class="others">{{number_format($crothers,2)}}</td>
                    <td class="stat"></td>
                </tr>
            </thead>

            <?php             
                $cashtotal=0;
                $discount=0;
                $debitreservation = 0;
                $elearning=0;
                $misc=0;
                $books=0;
                $departmentfacilities = 0;       
                $registration = 0;
                $tuition = 0;
                $creditreservation = 0;
                $other=0;
                ?>
            @if(count($allcollections)>0)
            <?php 
            $index =count($allcollections)-1;
            $lastreceipt= $allcollections[$index][0];

            $tempcashtotal=0;
            $tempdiscount=0;
            $tempdebitreservation = 0;
            $tempelearning=0;
            $tempmisc=0;
            $tempbooks=0;
            $tempdepartmentfacilities = 0;       
            $tempregistration = 0;
            $temptuition = 0;
            $tempcreditreservation = 0;
            $tempother=0;

            $rows = 1;
            $firstpagerows = 1;
            ?>
            @endif
            <tbody>
            @if(count($allcollections)>0)
            @foreach($allcollections as $allcollection)
            <?php

            if($allcollection[12]=="0"){
            $cashtotal = $cashtotal + $allcollection[2];
            $debitreservation = $debitreservation + $allcollection[3];
            $elearning = $elearning +$allcollection[4];
            $misc = $misc + $allcollection[5];
            $books = $books + $allcollection[6];
            $departmentfacilities = $departmentfacilities + $allcollection[7];
            $registration = $registration + $allcollection[8];
            $tuition=$tuition + $allcollection[9];
            $creditreservation = $creditreservation + $allcollection[10];
            $other=$other+$allcollection[11];
            $discount=$discount + $allcollection[13];


            $tempcashtotal = $tempcashtotal + $allcollection[2];
            $tempdebitreservation = $tempdebitreservation + $allcollection[3];
            $tempelearning = $tempelearning +$allcollection[4];
            $tempmisc = $tempmisc + $allcollection[5];
            $tempbooks = $tempbooks + $allcollection[6];
            $tempdepartmentfacilities = $tempdepartmentfacilities + $allcollection[7];
            $tempregistration = $tempregistration + $allcollection[8];
            $temptuition=$temptuition + $allcollection[9];
            $tempcreditreservation = $tempcreditreservation + $allcollection[10];
            $tempother=$tempother+$allcollection[11];
            $tempdiscount=$tempdiscount + $allcollection[13];            
            }
            ?>
            <tr style="border-bottom: 1px solid;border-top: 1px solid;">
            @if($allcollection[12]=="1")
            <td class="receipt" style="border-bottom: 1px solid;border-top: 1px solid;">{{$allcollection[0]}}</td><td colspan="13" style="border-bottom: 1px solid;border-top: 1px solid;">Cancelled</td>
            @else

            <td class="receipt" style="border-bottom: 1px solid;border-top: 1px solid;">{{$allcollection[0]}}</td>
            <td class="name" style="border-bottom: 1px solid;border-top: 1px solid;">{{$allcollection[1]}}</td>
            <td class="dcc" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[2],2)}}</td>
            <td class="ddiscount" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[13],2)}}</td>
            <td class="dreserve" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[3],2)}}</td>
            <td class="elearn" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[4],2)}}</td>
            <td class="misc" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[5],2)}}</td>
            <td class="book" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[6],2)}}</td>
            <td class="dept" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[7],2)}}</td>
            <td class="reg" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[8],2)}}</td>
            <td class="tuition" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[9],2)}}</td>
            <td class="reserv" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[10],2)}}</td>
            <td class="others" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">{{number_format($allcollection[11],2)}}</td>
            <td class="stat" align="right" style="border-bottom: 1px solid;border-top: 1px solid;">Ok</td>

            @endif
            </tr>
            @if($rows == 32 | $allcollection[0] == $lastreceipt | $firstpagerows == 32)
                <tr style="page-break-after: always;border-bottom: 1px solid;border-top: 1px solid;"><td colspan="2" width="210px">Total</td>
            <td align="right" class="dcc">{{number_format($tempcashtotal,2)}}</td>
            <td align="right" class="ddiscount">{{number_format($tempdiscount,2)}}</td>
            <td align="right" class="dreserve">{{number_format($tempdebitreservation,2)}}</td>
            <td align="right" class="elearn">{{number_format($tempelearning,2)}}</td>
            <td align="right" class="misc">{{number_format($tempmisc,2)}}</td>
            <td align="right" class="book">{{number_format($tempbooks,2)}}</td>
            <td align="right" class="dept">{{number_format($tempdepartmentfacilities,2)}}</td>
            <td align="right" class="reg">{{number_format($tempregistration,2)}}</td>
            <td align="right" class="tuition">{{number_format($temptuition,2)}}</td>
            <td align="right" class="reserv">{{number_format($tempcreditreservation,2)}}</td>
            <td align="right" class="others">{{number_format($tempother,2)}}</td>
            <td class="stat">
                </td>
            <td></td>
                </tr>
               <?php
            $tempcashtotal=0;
            $tempdiscount=0;
            $tempdebitreservation = 0;
            $tempelearning=0;
            $tempmisc=0;
            $tempbooks=0;
            $tempdepartmentfacilities = 0;       
            $tempregistration = 0;
            $temptuition = 0;
            $tempcreditreservation = 0;
            $tempother=0;
               $rows = 0; ?>
            @endif

            <?php 
            if(strlen($allcollection[1])>40){
                $rows=$rows+2;
            }else{
                $rows++;
            }

            $firstpagerows++;?>
            @endforeach
                <tr style="border-bottom: none;border-top: none;"><td colspan="15"><br></td></tr>
                <tr style="border-bottom: none;border-top: none;"><td colspan="2" width="210px">Total</td>

            <td align="right" class="dcc">{{number_format($cashtotal,2)}}</td>
            <td align="right" class="ddiscount">{{number_format($discount,2)}}</td>
            <td align="right" class="dreserve">{{number_format($debitreservation,2)}}</td>
            <td align="right" class="elearn">{{number_format($elearning,2)}}</td>
            <td align="right" class="misc">{{number_format($misc,2)}}</td>
            <td align="right" class="book">{{number_format($books,2)}}</td>
            <td align="right" class="dept">{{number_format($departmentfacilities,2)}}</td>
            <td align="right" class="reg">{{number_format($registration,2)}}</td>
            <td align="right" class="tuition">{{number_format($tuition,2)}}</td>
            <td align="right" class="reserv">{{number_format($creditreservation,2)}}</td>
            <td align="right" class="others">{{number_format($other,2)}}</td>
            <td class="stat"></td>
            <td></td>
            </tr>
            @endif        


                <tr style="border-bottom: none;border-top: none;"><td colspan="15"><br></td></tr>
                <tr style="border-bottom: none;border-top: none;"><td colspan="15"><br></td></tr>
                <tr style="border-bottom: none;border-top: none;text-align: right;"><td colspan="2" width="210px" style="text-align: left">Current Balance</td>
                <td class="dcc">{{number_format($totalcash+$cashtotal,2)}}</td>
                <td class="ddiscount">{{number_format($totaldiscount+$discount,2)}}</td>
                <td class="dreserve">{{number_format($drreservation+$debitreservation,2)}}</td>
                <td class="elearn">{{number_format($elearningcr+$elearning,2)}}</td>
                <td class="misc">{{number_format($misccr+$misc,2)}}</td>
                <td class="book">{{number_format($bookcr+$books,2)}}</td>
                <td class="dept">{{number_format($departmentcr+$departmentfacilities,2)}}</td>
                <td class="reg">{{number_format($registrationcr+$registration,2)}}</td>
                <td class="tuition">{{number_format($tuitioncr+$tuition,2)}}</td>
                <td class="reserv">{{number_format($crreservation+$creditreservation,2)}}</td>
                <td class="others">{{number_format($crothers+$other,2)}}</td>
                <td class="stat"></td>
                <td></td>
            </tr>

            </tbody>

        </table>
  </div>
</body>
</html>

