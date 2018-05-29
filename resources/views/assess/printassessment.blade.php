<?php 
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Assessement\ProcessReservation as Reserve;
use App\Http\Controllers\Assessement\ProcessDeposit as Deposit;
use App\Http\Controllers\Assessement\ProcessAwards as Award;

$totalaward = 0;
?>
<meta content="HTML5">
<html>
    @include('includes.header')
    <head>
        <style>
            html{
            margin-left:30px;
            margin-right:30px;
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
        </style>

    </head>
    <body>
        
        
        <h3 align='center'>REGISTRATION/ASSESSMENT FORM</h3>
        
        <table width='100%'>
            <tr>
                <td>Student Id</td>
                <td>:</td>
                <td>{{$idno}}</td>
            </tr>
            <tr>
                <td>Name</td>
                <td>:</td>
                <td>{{Info::get_nameFormal($idno)}}</td>
            </tr>
            <tr>
                <td>Level</td>
                <td>:</td>
                <td>{{Info::get_level($idno,$schoolyear,true)}}</td>
            </tr>
        </table>
        <br>
        <b>Breakdown Fee</b>
        <table border='1' cellspacing='0' width='100%'>
            <tr align='center'>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            
            @foreach($mainaccounts->groupBy('accountingcode') as $accounts)
            <tr>
                <td>{{$accounts->pluck('acctcode')->last()}}</td>
                <td align='right'>{{number_format($accounts->sum('amount'),2)}}</td>
            </tr>
            @endforeach
            <tr>
                <td>Sub Total</td>
                <td align='right'>{{number_format($mainaccounts->sum('amount'),2)}}</td>
            </tr>
            <tr>
                <td>Less: Plan Discount</td>
                <td align='right'>{{number_format($mainaccounts->sum('plandiscount'),2)}}</td>
            </tr>
            @if(Reserve::hasReservation($idno,0))
            <tr>
                <td style="text-indent: 35px">Reservation</td>
                <td align='right'>{{number_format(Reserve::remainingReservation($idno,0),2)}}</td>
            </tr>
            @endif
            
            @if(Deposit::hasDeposit($idno,1))
            <tr>
                <td style="text-indent: 35px">Student Deposit</td>
                <td align='right'>{{number_format(Deposit::remainingDeposit($idno,1),2)}}</td>
            </tr>
            @endif
            
            @if(Award::hasRemainingFund($idno,true))
            <?php $awards = Award::getAvailableAwards($idno,true); ?>
            @foreach($awards->groupBy('type') as $award=>$details)
            <?php
            $total = $details->sum('amount') - $details->sum('used');
            $totalaward = $totalaward+$total;
            ?>
            <tr>
                <td style="text-indent: 35px">{{$award}}</td>
                <td align="right">({{number_format( $total,2)}})</td>
            </tr>
                @endforeach
            @endif
            
            <tr>
                <td>Total</td>
                <td align='right'>
                    <?php
                    $subtotal = $mainaccounts->sum('amount') - $mainaccounts->sum('plandiscount') - $mainaccounts->sum('otherdiscount') - Reserve::remainingReservation($idno,0) - $totalaward;
                    
                    if($subtotal >= Deposit::remainingDeposit($idno,1)){
                        $total = $subtotal - Deposit::remainingDeposit($idno,1);
                    }else{
                        $total = 0;
                    }
                    ?>
                    
                    {{number_format($total,2)}}
                </td>
            </tr>
        </table>
        <br>
        <b>Schedule of Payment ({{Info::selected_plan($idno,$schoolyear)}})</b>
        <table border="1" cellspacing="0" width='100%'>
            <tr align="center">
                <th>Due Dates</th>
                <th>Amount</th>
            </tr>
            <?php
            $deposit = Deposit::remainingDeposit($idno,1);
            $reservaion = Reserve::remainingReservation($idno,0);
            $award = $totalaward;
            $dueAccounts = $mainaccounts->groupBy('duedate');
            ?>
            @foreach($dueAccounts as $dueAmount)
            <?php
            $initAmount = $dueAmount->sum('amount');
            if($dueAmount == $dueAccounts->first()){
                $initAmount = $dueAmount->sum('amount') - $mainaccounts->sum('plandiscount') - $mainaccounts->sum('otherdiscount') - Reserve::remainingReservation($idno,0);
            }
            
            if($initAmount >= $deposit){
                $duetotal  = $initAmount - $deposit;
                $deposit = 0;
            }else{
                $duetotal  = 0;
                $deposit = $deposit - $initAmount;
            }
            
            if($initAmount >= $award){
                $duetotal  = $initAmount - $totalaward;
                $totalaward = 0;
            }else{
                $duetotal  = 0;
                $totalaward = $totalaward - $initAmount;
            }
            
            
            ?>
            @if($dueAmount->pluck('duetype')->last() == 0)
            <tr>
                <td>
                    Upon Enrollment 
                </td>
                <td align='right'>{{number_format($duetotal,2)}}</td>
            </tr>
            @else
                @if(in_array(Info::selected_plan($idno,$schoolyear),array('Monthly 1','Monthly 2')))
                 @if($dueAmount == $dueAccounts->last())
                    <tr>
                        <td>
                            Jul 5 to Feb 5 2019
                        </td>
                        <td align='right'>{{number_format($dueAmount->sum('amount'),2)}}</td>
                    </tr>
                  @endif
                @else
                <tr>
                    <td>
                        {{date("F d, Y",strtotime($dueAmount->pluck('duedate')->last()))}}
                    </td>
                    <td align='right'>{{number_format($duetotal,2)}}</td>
                </tr>
                @endif
            @endif
            @endforeach
        </table>
        
        <div class="notice">
            In accordance with the financial policies of the school as set out in the Student Diary, the failure to meet the financial
            obligation to the school within the specified period may result in the withholding of transfer credentials, deprivation of any quarterly or final
            examinations, refusal of re-admission, dropping from the rolls and availment of other applicable remedies.
            <br><br>
            <i style="font-weight: bold">*If check payment, write name of student and contact no at the back of the check.</i><br>
            <i style="font-weight: bold">*Failure to pay on the due date will be subjected to a penalty of 5% of the amount due or a minimum of P250.00 per month.</i>
        </div>

        <div class="notice">
            <table width = "100%">

                <tr><td>Date:</td><td>Cashier:</td><td>OR#</td></tr>
                <tr><td style="height: 5px;"></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td></tr>
                <tr><td>{{Info::get_StudentSyInfo($idno, $schoolyear,true)->date_registered}}</td><td><hr></td><td><hr/></td></tr>

            </table>    

        </div>    

        <div class="footer">
           <table width = "100%">

                <tr><td>Conforme:</td><td></td><td align="right">Relationship to Student :</td><td width ="30%"><br><hr /></td></tr>
                <tr><td></td><td></td><td align="right">Contact No :</td><td width ="30%"><br><hr /></td></tr>
                <tr><td style="height: 5px"><hr /></td><td></td><td></td><td></td></tr>

                <tr><td></tr>


            </table>    

    </body>
</html>