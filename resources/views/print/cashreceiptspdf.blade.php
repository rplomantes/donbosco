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
    <?php
        $repeatrot = true;
        $noOfrecords = count($currTrans);
        $rows = 33;
        $index = 0;
        
        
        $cash = 0;
        $discount = 0;
        $fape = 0;
        $dreservation = 0;
        $deposit = 0;
        $elearning = 0;
        $misc = 0;
        $book = 0;
        $department = 0;
        $registration = 0;
        $tuition = 0;
        $creservation = 0;
        $others = 0;
        
        if(count($forwarder) > 0){
            foreach($forwarder as $forward){
                $cash = $cash + $forward->cash;
                $discount = $discount + $forward->discount;
                $fape = $fape + $forward->fape;
                $dreservation = $dreservation + $forward->dreservation;
                $deposit = $deposit + $forward->deposit;
                $elearning = $elearning + $forward->elearning;
                $misc = $misc + $forward-> misc;
                $book = $book + $forward->book;
                $department = $department + $forward->dept;
                $registration = $registration + $forward->registration;
                $tuition = $tuition + $forward->tuition;
                $creservation = $creservation + $forward->creservation;
                $others = $others + $forward->csundry;
            }
        }
        
        $totalcash = 0;
        $totaldiscount = 0;
        $totalfape = 0;
        $totaldreservation = 0;
        $totaldeposit = 0;
        $totalelearning = 0;
        $totalmisc = 0;
        $totalbook = 0;
        $totaldepartment = 0;
        $totalregistration = 0;
        $totaltuition = 0;
        $totalcreservation = 0;
        $totalothers = 0;
    ?>
    
    @foreach($currTrans as $trans)
    <?php
        if($trans->isreverse == 0){
            $totalcash = $totalcash + $trans->cash;
            $totaldiscount = $totaldiscount + $trans->discount;
            $totalfape = $totalfape + $trans->fape;
            $totaldreservation = $totaldreservation + $trans->dreservation;
            $totaldeposit = $totaldeposit + $trans->deposit;
            $totalelearning = $totalelearning + $trans->elearning;
            $totalmisc = $totalmisc + $trans->misc;
            $totalbook = $totalbook + $trans->book;
            $totaldepartment = $totaldepartment + $trans->dept;
            $totalregistration = $totalregistration + $trans->registration;
            $totaltuition = $totaltuition + $trans->tuition;
            $totalcreservation = $totalcreservation + $trans->creservation;
            $totalothers = $totalothers + $trans->csundry;
        }
    ?>
    @endforeach
    
    <div id="header"><span style="font-size:20px;">Don Bosco Technical School</span>
            <h4 style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 15pt;">Cash Receipt</h4>
            <p style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">For <span id="dates" >{{date("l, F d, Y",strtotime($date))}}</span></p>
        </div>
        <div id="footer">Page <span class="pagenum"></span></div>    
        @if(count($currTrans)> 0 )
        @while($index <= count($currTrans)-1)
        <?php
        $tablecash = 0;
        $tablediscount = 0;
        $tablefape = 0;
        $tabledreservation = 0;
        $tabledeposit = 0;
        $tableelearning = 0;
        $tablemisc = 0;
        $tablebook = 0;
        $tabledepartment = 0;
        $tableregistration = 0;
        $tabletuition = 0;
        $tablecreservation = 0;
        $tableothers = 0;
        ?>
<div id="content" width="100%"  
     @if($index != 0)
     style="page-break-before: always"
     @endif
     >
            <table cellspacing="0" border="0" width="100%" style="font-size: 13px;page-break-inside: auto;">
                <thead>
                    <tr>
                        <th>Receipt No.</th>
                        <th width="150px">Name</th>
                        <th>Debit <br> Cash/Checks</th>
                        <th>Debit <br> Discount</th>
                        <th>Debit <br> FAPE</th>
                        <th>Debit <br> Reservation</th>
                        <th>Debit <br> Deposit</th>
                        <th>E-learning</th>
                        <th>Misc</th>
                        <th>Books</th>
                        <th>Department <br> Facilities</th>
                        <th>Registration</th>
                        <th>Tuition</th>
                        <th>Reservation</th>
                        <th>Others</th>
                        <th>Status</th>
                    </tr>
                    
                    <tr style="text-align: right">
                        <td colspan="2" style="text-align: left">Balance brought forward</td>
                        <td>{{number_format($cash, 2, '.', ',')}}</td>
                        <td>{{number_format($discount, 2, '.', ',')}}</td>
                        <td>{{number_format($fape, 2, '.', ',')}}</td>
                        <td>{{number_format($dreservation, 2, '.', ',')}}</td>
                        <td>{{number_format($deposit, 2, '.', ',')}}</td>
                        <td>{{number_format($elearning, 2, '.', ',')}}</td>
                        <td>{{number_format($misc, 2, '.', ',')}}</td>
                        <td>{{number_format($book, 2, '.', ',')}}</td>
                        <td>{{number_format($department, 2, '.', ',')}}</td>
                        <td>{{number_format($registration, 2, '.', ',')}}</td>
                        <td>{{number_format($tuition, 2, '.', ',')}}</td>
                        <td>{{number_format($creservation, 2, '.', ',')}}</td>
                        <td>{{number_format($others, 2, '.', ',')}}</td>
                        <td></td>
                    </tr>
                </thead>
                <?php $currrows = 1; ?>
                @while($currrows < $rows && $index <= count($currTrans)-1)
                        @if($currTrans[$index]['isreverse'] == 0)
                        <tr>
                            <td>{{$currTrans[$index]['receiptno']}}</td>
                            <td>{{$currTrans[$index]['from']}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['cash'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['discount'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['fape'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['dreservation'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['deposit'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['elearning'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['misc'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['book'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['dept'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['registration'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['tuition'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['creservation'], 2, '.', ',')}}</td>
                            <td class="cash">{{number_format($currTrans[$index]['csundry'], 2, '.', ',')}}</td>
                            <td style='text-align: center'>OK</td>
                        </tr>
                        
                        <?php
                        $tablecash = $tablecash + $currTrans[$index]['cash'];
                        $tablediscount = $tablecash + $currTrans[$index]['discount'];
                        $tablefape = $tablefape + $currTrans[$index]['fape'];
                        $tabledreservation = $tabledreservation + $currTrans[$index]['dreservation'];
                        $tabledeposit = $tabledeposit + $currTrans[$index]['deposit'];
                        $tableelearning = $tableelearning + $currTrans[$index]['elearning'];
                        $tablemisc = $tablemisc + $currTrans[$index]['misc'];
                        $tablebook = $tablebook + $currTrans[$index]['book'];
                        $tabledepartment = $tabledepartment + $currTrans[$index]['dept'];
                        $tableregistration = $tableregistration + $currTrans[$index]['registration'];
                        $tabletuition = $tabletuition + $currTrans[$index]['tuition'];
                        $tablecreservation = $tablecreservation + $currTrans[$index]['creservation'];
                        $tableothers = $tableothers + $currTrans[$index]['csundry'];
                        ?>
                        
                        @else
                        <tr>
                            <td>{{$currTrans[$index]['receiptno']}}</td>
                            <td colspan="15" style="text-align: center">Cancelled</td>
                        </tr>
                        @endif
                <?php 
                    
                    if(strlen($currTrans[$index]['from'])<=17){
                        $currrows++;
                    }elseif(strlen($currTrans[$index]['from'])<=34){
                        $currrows=$currrows+2;
                    }elseif(strlen($currTrans[$index]['from'])<=51){
                        $currrows=$currrows+3;
                    }else{
                        $currrows=$currrows+4;
                    }
                    
                    $index++;
                ?>
                @endwhile
                <tr>
                    <td colspan='2'>Total Cash</td>
                        <td class="cash">{{number_format($tablecash, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tablediscount, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tablefape, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tabledreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tabledeposit, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tableelearning, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tablemisc, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tablebook, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tabledepartment, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tableregistration, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tabletuition, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tablecreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($tableothers, 2, '.', ',')}}</td>
                        <td></td>
                </tr>
                
                @if($index >= count($currTrans)-1)
                <tr><td colspan='16' height="50px">&nbsp;</td></tr>
                    <tr>
                        <td colspan='2'><b>Total</b></td>
                        <td class="cash">{{number_format($totalcash, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldiscount, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalfape, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldeposit, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalelearning, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalmisc, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalbook, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldepartment, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalregistration, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaltuition, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalcreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalothers, 2, '.', ',')}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan='2'><b>Current Balance</b></td>
                        <td class="cash">{{number_format($totalcash + $cash, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldiscount + $discount, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalfape + $fape, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldreservation + $dreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldeposit + $deposit, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalelearning +$elearning, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalmisc + $misc, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalbook + $book, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldepartment + $department, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalregistration + $registration, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaltuition + $tuition, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalcreservation + $creservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalothers + $others, 2, '.', ',')}}</td>
                        <td></td>
                    </tr>
                @endif
            </table>
        </div>
        @endwhile   
    @else
    
    <div id="content" width="100%"  
     @if($index != 0)
     style="page-break-before: always"
     @endif
     >
            <table cellspacing="0" border="0" width="100%" style="font-size: 13px;page-break-inside: auto;">
                <thead>
                    <tr>
                        <th>Receipt No.</th>
                        <th width="150px">Name</th>
                        <th>Debit <br> Cash/Checks</th>
                        <th>Debit <br> Discount</th>
                        <th>Debit <br> FAPE</th>
                        <th>Debit <br> Reservation</th>
                        <th>Debit <br> Deposit</th>
                        <th>E-learning</th>
                        <th>Misc</th>
                        <th>Books</th>
                        <th>Department <br> Facilities</th>
                        <th>Registration</th>
                        <th>Tuition</th>
                        <th>Reservation</th>
                        <th>Others</th>
                        <th>Status</th>
                    </tr>
                    
                    <tr style="text-align: right">
                        <td colspan="2" style="text-align: left">Balance brought forward</td>
                        <td>{{number_format($cash, 2, '.', ',')}}</td>
                        <td>{{number_format($discount, 2, '.', ',')}}</td>
                        <td>{{number_format($fape, 2, '.', ',')}}</td>
                        <td>{{number_format($dreservation, 2, '.', ',')}}</td>
                        <td>{{number_format($deposit, 2, '.', ',')}}</td>
                        <td>{{number_format($elearning, 2, '.', ',')}}</td>
                        <td>{{number_format($misc, 2, '.', ',')}}</td>
                        <td>{{number_format($book, 2, '.', ',')}}</td>
                        <td>{{number_format($department, 2, '.', ',')}}</td>
                        <td>{{number_format($registration, 2, '.', ',')}}</td>
                        <td>{{number_format($tuition, 2, '.', ',')}}</td>
                        <td>{{number_format($creservation, 2, '.', ',')}}</td>
                        <td>{{number_format($others, 2, '.', ',')}}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <tr><td colspan='16' height="50px">&nbsp;</td></tr>
                    <tr>
                        <td colspan='2'><b>Total</b></td>
                        <td class="cash">{{number_format($totalcash, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldiscount, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalfape, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldeposit, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalelearning, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalmisc, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalbook, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldepartment, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalregistration, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaltuition, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalcreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalothers, 2, '.', ',')}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan='2'><b>Current Balance</b></td>
                        <td class="cash">{{number_format($totalcash + $cash, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldiscount + $discount, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalfape + $fape, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldreservation + $dreservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldeposit + $deposit, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalelearning +$elearning, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalmisc + $misc, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalbook + $book, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaldepartment + $department, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalregistration + $registration, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totaltuition + $tuition, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalcreservation + $creservation, 2, '.', ',')}}</td>
                        <td class="cash">{{number_format($totalothers + $others, 2, '.', ',')}}</td>
                        <td></td>
                    </tr>
                </tbody>
        </table>
</div>
    @endif
</body>
</html>

