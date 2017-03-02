@extends('appaccounting')
@section('content')

<style>
    
    #dates{
    margin-bottom: 0;
    font-weight: 300;
    text-align: center;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    border-radius: 4px;
    
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #333333;
    background-color: #ffffff;
    border-color: #cccccc;
    }

    body{
        font-size: 8pt;
        border-top: 0px
    }
    
    #header{
        display: none;
    }
    .hidden-row{
        display:none;
    }
</style>
<style media="print">
    .hidden-row{
        display:table-row;
    }
    .btn,.text-muted,.no-print{
        display: none;
    }
    #header{
        display: block;
    }
    thead{
        display: table-header-group;
    }
    .breakpage{
        page-break-after: always;
        
    }

    #receipt tr{
        border-bottom: 1px solid;
        border-top: 1px solid;
    }
    .col-md-12{
        padding: 0px;
    }
    .container-fluid{
        padding:0px;
    }
</style>
<div class="container-fluid">
        <span id="page1">
        
        <div class="col-md-12">
            <table cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <td style="page-break-after:avoid">
                            <div id="header">
                                <div class="col-md-12" id="header" style="text-align: center"><h4>Don Bosco Technical School</h4></div>
                            </div>        
                            <h4 style="text-align: center">Cash Receipt</h4>
                            <p style="text-align: center">For <span id="dates" >{{$asOf}}</span></p>
                        </td>
                    </tr>
                </thead>
                <tr>
                    <td>
                        <table width="100%"  class="table table-striped">
                            <thead>
                                <tr >
                                    <td class="receipt">O R No.</td>
                                    <td class="name">Name</td>
                                    <td class="dcc">Debit <br> Cash/Check</td>
                                    <td class="ddiscount">Debit <br>Discount</td>
                                    <td class="dreserve">Debit <br> Reservation</td>
                                    <td class="elearn">E-learning</td>
                                    <td class="misc">Misc</td>
                                    <td class="book">Books</td>
                                    <td class="dept">Department <br> Facilities</td>
                                    <td class="reg">Registration</td>
                                    <td class="tuition">Tuition</td>
                                    <td class="reserv">Reservation</td>
                                    <td class="others">Others</td>
                                    <td class="stat">Status</td>
                                </tr>
                                <tr style="text-align: right">
                                    <td width="260px" colspan="2" style="text-align: left">Balance brought forward</td>
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
            <tr @if($allcollection[12]=="1")
                 class="no-print"
                 @endif><td class="receipt">{{$allcollection[0]}}</td>
        <td class="name">{{$allcollection[1]}}</td>
        <td class="dcc" align="right">{{number_format($allcollection[2],2)}}</td>
        <td class="ddiscount" align="right">{{number_format($allcollection[13],2)}}</td>
        <td class="dreserve" align="right">{{number_format($allcollection[3],2)}}</td>
        <td class="elearn" align="right">{{number_format($allcollection[4],2)}}</td>
        <td class="misc" align="right">{{number_format($allcollection[5],2)}}</td>
        <td class="book" align="right">{{number_format($allcollection[6],2)}}</td>
        <td class="dept" align="right">{{number_format($allcollection[7],2)}}</td>
        <td class="reg" align="right">{{number_format($allcollection[8],2)}}</td>
        <td class="tuition" align="right">{{number_format($allcollection[9],2)}}</td>
        <td class="reserv" align="right">{{number_format($allcollection[10],2)}}</td>
        <td class="others" align="right">{{number_format($allcollection[11],2)}}</td>
        <td class="stat" align="right">@if($allcollection[12]=="0")
            Ok  
            @else
            Cancelled
            @endif
            </td>
        </tr>
        @if($allcollection[12]=="1")
            <tr class="hidden-row"><td class="receipt">{{$allcollection[0]}}</td>
                <td colspan="14" style="text-align: left"><b>Cancelled</b></td>
        </tr>
        @endif
        @if($rows == 30 | $allcollection[0] == $lastreceipt | $firstpagerows == 30)
            <tr 
            @if($rows == 30 |$firstpagerows == 30)
            class="breakpage"
            @endif><td colspan="2" width="210px">Total</td>
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
        <?php $rows++;

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
        </table>
                    </td>
                </tr>
            </table>
            
            <br>
            <br>
            <br>
            <button class="btn btn-danger" onclick="changepage()">Next Page</button>
            <a href="{{url('printcashreceipts',$transactiondate)}}" class="btn btn-info" style="float: right">Print</a>
    </div>
    </span>
    
    <span id="page2">
    <div class="col-md-6" style="page-break-after: always">
    <h5>Other Accounts</h5>
    <table class="table table-striped">
        <tr><td>Receipt No</td><td><Name><td>Description</td><td>Amount</td><td>Status</td></tr>
        @if(count($otheraccounts) > 0 )
        <?php $othertotal = 0;?>
        @foreach($otheraccounts as $otheraccount)
        <?php
        if($otheraccount->isreverse == '0'){
          $othertotal = $othertotal + $otheraccount->amount;  
        }
        ?>
        <tr><td>{{$otheraccount->receiptno}}</td>
            <td>{{$otheraccount->lastname}}, {{$otheraccount->firstname}}</td>
            <td>{{$otheraccount->receipt_details}}</td>
            <td align="right">{{number_format($otheraccount->amount,2)}}</td>
            <td>@if($otheraccount->isreverse == "0")
                Ok
                @else
                Cancelled
                @endif
            </td>
            </tr>    
        @endforeach
        <tr><td colspan="3">Total</td><td align="right">{{number_format($othertotal,2)}}</td><td></td></tr>
        @endif
    </table>    
    </div>
    <div class="col-md-6">
        <h5>Other Account Summary</h5>
        <table class="table table-striped">
            <tr><td>Account Details</td><td>Amount</td>
                @if(count($othersummaries)>0)
                <?php
                $totalsummary=0.00;
                ?>
                @foreach($othersummaries as $othersummary)
                <?php
                $totalsummary = $totalsummary + $othersummary->amount;
                ?>
                <tr><td>{{$othersummary->acctcode}}</td><td align="right">{{number_format($othersummary->amount,2)}}</td></tr>
                @endforeach
                <tr><td>Total</td><td align="right">{{number_format($totalsummary,2)}}</td></tr>
                @endif
        </table>        
    </div>  
        <div class="col-md-12">
        <button class="btn btn-danger" onclick="prevpage()">Previous Page</button>
        </div>
    </span>
    <br>
    <script>
        $("#page2").hide();
        
        function changepage(){
            $("#page2").fadeIn();
            $("#page1").fadeOut();
        }
        function prevpage(){
            $("#page1").fadeIn();
            $("#page2").fadeOut();
        }
    </script>
</div>    
@stop
