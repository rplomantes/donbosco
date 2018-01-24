@extends('appadmin')
@section('content')
<div class="col-md-6 col-md-offset-3">
<table class="table table-striped">
                <tr><td>Student ID</td><td>{{$student->idno}}</td>
                    <td>Department</td><td>
                    @if(isset($status->department))
                        {{$status->department}}
                    @endif
                    </td>
                </tr>
                <tr><td>Student Name</td><td><strong>{{$student->lastname}},  {{$student->firstname}} {{$student->middlename}} {{$student->extensionnamename}}</strong></td>
                  @if(count($status)>0)
                    @if($status->department == "TVET")
                    <td>Course</td><td>{{$status->course}}</td>
                    @else
                    <td>Level</td><td>{{$status->level}}</td>
                    @endif
                  @endif  
                </tr>
                <tr><td>Gender</td><td>{{$student->gender}}</td><td>Section</td><td>
                    @if(isset($status->section))    
                        {{$status->section}}
                    @endif
                    </td></tr>
                <tr><td>Status</td><td style="color:red">
                 @if(isset($status->status))
                 @if($status->status == "0")
                 Registered
                 @elseif($status->status == "1")
                 Assessed
                 @elseif($status->status == "2")
                 Enrolled
                 @elseif($status->status == "3")
                 Dropped
                 @elseif($status->status == "4")
                 No Show
                 @endif
                 @else
                 Registered
                 @endif       
                    
                    </td><td>Specialization</td><td>
                    @if(isset($status->strand))
                        {{$status->strand}}
                    @endif
                    </td></tr>
            </table>
            <h5>Account Details</h5>
            <table class="table table-striped">
                <tr><td>Description</td><td align="right">Amount</td><td align="right">Discount</td><td align="right">DM</td><td align="right">Payment</td><td align="right">Balance</td></tr>
                <?php
                $totalamount = 0;
                $totaldiscount = 0;
                $totaldebitmemo = 0;
                $totalpayment = 0;
                ?>
                @if(isset($status->department) && count($ledgers) > 0)
                @foreach($ledgers as $ledger)
                <?php
                $totalamount = $totalamount + $ledger->amount;
                $totaldiscount = $totaldiscount + $ledger->plandiscount + $ledger->otherdiscount;
                $totaldebitmemo = $totaldebitmemo + $ledger->debitmemo;
                $totalpayment = $totalpayment + $ledger->payment;
                ?>
                <tr><td>{{$ledger->receipt_details}}</td><td align="right">{{number_format($ledger->amount,2)}}</td><td align="right">{{number_format($ledger->plandiscount+$ledger->otherdiscount,2)}}</td>
                    <td align="right">{{number_format($ledger->debitmemo,2)}}</td><td align="right" style="color:red">{{number_format($ledger->payment,2)}}</td>
                    <td align="right">{{number_format($ledger->amount-$ledger->debitmemo-$ledger->plandiscount-$ledger->otherdiscount-$ledger->payment,2)}}</td></tr>
                @endforeach
                @endif
                <tr><td>Total</td><td align="right">{{number_format($totalamount,2)}}</td>
                                  <td align="right">{{number_format($totaldiscount,2)}}</td> 
                                  <td align="right">{{number_format($totaldebitmemo,2)}}</td>
                                  <td align="right" style="color:red">{{number_format($totalpayment,2)}}</td>
                                  <td align="right"><strong>{{number_format($totalamount-$totaldiscount-$totaldebitmemo-$totalpayment,2)}}</strong></td></tr>
            </table>
</div>
            @stop