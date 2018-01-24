<?php
$banks = \App\Dedit::distinct('bank_branch')->pluck('bank_branch')->toArray();
$checkno = \App\Dedit::distinct('check_number')->take(5)->pluck('check_number')->toArray();
?>
@extends("appcashier")

@section("content")
  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
<script>
   $( function() {
    var bank = [<?php echo '"'.implode('","', $banks).'"' ?>];
    $( "#bank_branch" ).autocomplete({
      source: bank
    });
    });

   $( function() {
    var checkno = [<?php echo '"'.implode('","', $checkno).'"' ?>];
    $( "#check_number" ).autocomplete({
      source: checkno
    });
    });
</script>
  <div class="container_fluid">  
      <div class="col-md-12">
             
          <div class="col-md-6" >
              <div class="col-md-12 form-group">
                  <label>SOA as of</label>
                  <form class="form-horizontal" method="POST" action="{{url('studentsoa',$student->idno)}}">
                      {!! csrf_field() !!} 
                      <div class="col-md-7">
                        <input type="text" name="soadate" id="soadate" value="{{date('Y-m-d')}}" class="form">
                        <div>
                        <textarea type="text" name="reminder" id="reminder" placeholder=" Custom Remarks" class="form" style="resize: none;vertical-align: bottom;width: 100%;"></textarea>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <input type="submit" class="btn btn-danger" value="View SOA">
                      </div>
                      
                  </form>
              </div>
              <script>
                  function viewsoa(idno){
                      var soadate = document.getElementById('soadate').value
                      var remark = document.getElementById('remark').value
                      window.location="{{url('printsoa',$student->idno)}}/" + soadate +"/";
                  }
              </script>
            <div class="form-group">
                <a href="{{url('/')}}" class="btn btn-primary">Back</a>
                <a href="{{url('/cashier', $student->idno)}}" class="btn btn-primary">Refresh</a>
                <a href="{{url('/otherpayment',$student->idno)}}" class="btn btn-primary"> Other Payment</a>
                 <a href="{{url('/previous',$student->idno)}}" class="btn btn-primary"> Previous Accounts</a>
                @if(\Auth::user()->accesslevel=='2')
                <a href="{{url('/addtoaccount',$student->idno)}}" class="btn btn-primary"> Add To Account</a>
                @endif
                 <div class="pull-right">
                <label for="receiotno">Receipt No</label>
                <div class="btn btn-danger"><strong>{{Auth::user()->receiptno}}</strong>
            </div>
            </div>
            </div>    
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
                 <b>Dropped</b> - {{$status->dropdate}}
                 @elseif($status->status == "4")
                 No Show                 
                 @endif
                 @else
                 Registered
                 @endif       
		 </td>
                 @if(isset($status) && $status->department == "TVET")
                    <td>Batch</td><td>
                        {{$status->period}}
                    </td>

		 @else
                    <td>Specialization</td><td>
                    @if(isset($status->strand))
                        {{$status->strand}}
                    @endif
                    </td>
		 @endif
		</tr>
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
                @if(count($ledgers) > 0)
                @foreach($ledgers as $ledger)
                <?php
                $totalamount = $totalamount + $ledger->amount;
                $totaldiscount = $totaldiscount + $ledger->plandiscount + $ledger->otherdiscount;
                $totaldebitmemo = $totaldebitmemo + $ledger->debitmemo;
                $totalpayment = $totalpayment + $ledger->payment;
                ?>
                <tr><td>{{$ledger->receipt_details}}</td><td align="right">{{number_format($ledger->amount,2)}}</td><td align="right">{{number_format($ledger->plandiscount+$ledger->otherdiscount,2)}}</td>
                    <td align="right">{{number_format($ledger->debitmemo,2)}}</td><td align="right" style="color:red">{{number_format($ledger->payment,2)}}</td>
                    <td align="right">{{number_format($ledger->amount-($ledger->debitmemo+$ledger->plandiscount+$ledger->otherdiscount+$ledger->payment),2)}}</td></tr>
                @endforeach
                @endif
                <tr><td>Total</td><td align="right">{{number_format($totalamount,2)}}</td>
                                  <td align="right">{{number_format($totaldiscount,2)}}</td> 
                                  <td align="right">{{number_format($totaldebitmemo,2)}}</td>
                                  <td align="right" style="color:red">{{number_format($totalpayment,2)}}</td>
                                  <td align="right"><strong>{{number_format($totalamount-$totaldiscount-$totaldebitmemo-$totalpayment,2)}}</strong></td></tr>
            </table>
              <h5>Payment History</h5>
              <table class="table table-striped" id="ph"><tr><td>Date</td><td>Ref Number</td><td>OR Number</td><td align="right">Amount</td><td>Payment Type</td><td>Details</td><td>Status</td></tr>
                  @if(count($debits)>0)
                  @foreach($debits as $debit)
                  <tr><td>{{$debit->transactiondate}}</td><td>{{$debit->refno}}</td><td>{{$debit->receiptno}}</td><td align="right">{{number_format($debit->amount + $debit->checkamount,2)}}</td><td>
                      @if($debit->paymenttype=='1')
                        Cash/Check
                      @elseif($debit->paymenttype=='3')
                        DEBIT MEMO
                      @endif
                      </td><td><a href="{{url('/viewreceipt',array($debit->refno,$student->idno))}}">View</a></td>
                      <td>
                       @if($debit->isreverse=="0")
                        Ok
                       @else
                        Cancelled
                       @endif   
                      </td>
                  
                  </tr>
                  @endforeach
                  @endif
              </table>
              @if(count($oldreceipts)>0)
              <h5>Old Transactions</h5>
              <table class="table table-stripped">
                  <thead>
                      <tr><th>Date</th><th>Receipt No.</th><th>Amount</th></tr>
                  </thead>
                  <tbody>
                      @foreach($oldreceipts as $oldreceipt)
                      <tr><td>{{$oldreceipt->transactiondate}}</td><td>{{$oldreceipt->receiptno}}</td><td>{{$oldreceipt->amount}}</td></tr>
                      @endforeach
                  </tbody>
              </table>
              @endif
              
               <h5>Debit Memo</h5>
              <table class="table table-striped"><tr><td>Date</td><td>Ref Number</td><td>Account</td><td align="right">Amount</td><td>Payment Type</td><td>Details</td><td>Status</td></tr>
                  @if(count($debitdms)>0)
                  @foreach($debitdms as $debit)
                  <tr><td>{{$debit->transactiondate}}</td><td>{{$debit->refno}}</td><td>{{$debit->acctcode}}</td><td align="right">{{number_format($debit->amount + $debit->checkamount,2)}}</td><td>
                      @if($debit->paymenttype=='1')
                      Reservation
                      @elseif($debit->paymenttype=='3')
                      DEBIT MEMO
                      @endif
                      </td><td><a href="{{url('/viewdm',array($debit->refno,$student->idno))}}">View</a></td>
                      <td>
                       @if($debit->isreverse=="0")
                       Ok
                       @else
                       Cancelled
                       @endif   
                      </td>
                  
                  </tr>
                  @endforeach
                  @endif
              </table>
        </div>
    
        <div class="col-md-3">
            
               <h5>Schedule of payment 
               @if(isset($status->plan))
               <strong>({{$status->plan}})</strong>
               @endif
               </h5>
               
             <table class="table table-striped"><tr><td>Due Date</td><td align="right">Amount</td></tr>
             <?php $totaldue = 0;?>
             @if(count($dues) > 0)
                 @foreach($dues as $due)
                   <tr><td>
                     @if($due->duetype == '0')
                     <?php $totaldue = $totaldue + $due->balance; ?>
                     Upon Enrollment
                     @else
                        <?php
                        if($due->duedate <= date('Y-m-d')){
                            $totaldue = $totaldue + $due->balance;
                        }
                        ?>
                         {{date('M d, Y',strtotime($due->duedate))}}
                     @endif    
                     </td><td align="right">{{number_format($due->balance,2)}}</td></tr>
                 @endforeach
                 @endif
             </table>    
               <h5>Previous Balance</h5>
               <table class="table table-striped"><tr><td>School Year</td><td>Amount</td></tr>
                   <?php $totalother = 0;
                   ?>
               @if(count($previousbalances)>0)
                    <?php
                    $annual_prevBalance = $previousbalances->groupBy('schoolyear');
                    ?>
                    @foreach($annual_prevBalance as $prev)
                    <tr><td>{{$prev->pluck('schoolyear')->last()}} - {{$prev->pluck('schoolyear')->last() +1}}</td><td  style='text-align:right'>{{number_format($prev->sum('amount') - $prev->sum('payment'),2)}}</td></tr>

                    @endforeach
               @else
                    <tr><td>None</td><td align="right">0.00</td></tr>
               @endif
               </table>
               <h5>Other Account</h5>
               <table class="table table-striped"><tr><td>Particular</td><td>Amount</td></tr>
               @if(count($othercollections)>0)
                    @foreach($othercollections as $othercollection)
                    <?php
                    $totalother = $totalother + $othercollection->amount-$othercollection->payment-$othercollection->debitmemo;
                    ?>
                    <tr><td>{{$othercollection->receipt_details}}</td><td  align="right">{{number_format($othercollection->amount-$othercollection->payment-$othercollection->debitmemo,2)}}</td></tr>
                    @endforeach
               @else
                    <tr><td>None</td><td align="right">0.00</td></tr>
               @endif
               
               </table>
              
         </div> 
     
        <div class="col-md-3">
            <div class="col-md-12">
                <h5>Due Today</h5>
                <div class="btn btn-danger form-control" style="font-size:20pt; font-weight: bold; height: 50px">{{number_format($totaldue + $totalothers +$totalprevious - $reservation ,2) }}</div>
            </div>    
            <div class="col-md-12" style=" margin-top: 10px;background-color: ">
             <h5>Amount Due</h5>
             <form class="form-horizontal" id ="assess" name="assess" role="form" method="POST" action="{{ url('/payment') }}">
             {!! csrf_field() !!} 
             <input type="hidden" name="idno" value="{{$student->idno}}">
             <input type="hidden" id="reservation" id = "reservation" name="reservation" value="{{$reservation}}">
             <input type="hidden" name="totalprevious" id = "totalprevious" value="{{$totalprevious}}">
             <input type="hidden" name="totalother" id = "totalother" value="{{$totalother}}">
             <input type="hidden" name="totalmain" id = "totalmain" value="{{$totalmain}}">
             <input type="hidden" id="penalty" name="penalty" value="{{$penalty}}">
            
             <table class="table table-responsive table-bordered">
                 <!--Main Account for payment-->
                <tr>
                    <td>Main Account</td>
                    <td align="right">
                        <input class='form-control divide credit' type="text" name="totaldue" id="totaldue" style="text-align:right" value="<?php echo number_format($totaldue,2,'.','');?>">
                    </td>
                </tr>
                @if(count($previousbalances)> 0 )
                
                        <tr><td>Previous Balance</td><td><input type="text"  name="previous" id="previous" style="text-align:right" class="form-control divide credit" value="{{$totalprevious}}"></td></tr>
                @else   
                <input type="hidden" name="previous" id="previous" value="0">
                @endif
                @if(count($othercollections)>0)
                @foreach($othercollections as $coll)
                    @if(round($coll->amount - $coll->payment - $coll->debitmemo,5) > 0)
                         <tr>
                             <td>{{$coll->description}}</td>
                             <td>
                                 <input type="text" name="other[{{$coll->id}}]" style="text-align:right" class="form-control divide other credit"  value="{{$coll->amount-($coll->payment+$coll->debitmemo)}}">
                             </td>
                         </tr>
                    @endif
               @endforeach
                @endif
                
                @if($penalty > 0)
                <tr><td>Add: Penalty</td><td align="right"><span class="form-control divide credit">{{number_format($penalty,2)}}</span></td></tr>
                @endif
                @if($reservation > 0)
                <tr><td>Less: Reservation</td><td align="right"><span class="form-control divide less">{{number_format($reservation,2)}}</span></td></tr>
                @endif
                <?php 
                $deposits = $deposit;
                if($deposit > $totaldue-$reservation+$totalprevious+$totalother+$penalty){
                    $deposits = $totaldue-$reservation+$totalprevious+$totalother+$penalty;
                }
                ?>
                <input id="deposit" name="deposit" readonly="readonly" style="display:none" class="form-control divide" value="{{$deposits}}">
                <input id="remainingdeposit" readonly="readonly" style="display:none" class="form-control" value="{{$deposit}}">                
                @if($deposit > 0)

                <tr><td style="text-align: right">Less: Student Deposit</td><td align="right"><span id="displaydeposit" class="form-control less">{{number_format($deposits,2)}}</span><span>Remaining: {{number_format($deposit,2,'.',',')}}</span></td></tr>

               @endif
                <tr><td>Amount To Be Paid</td><td align="right"><input type="text" name="totalamount" id ="totalamount" style="color: red; font-weight: bold; text-align: right" class="form-control divide" value="{{ number_format($totaldue-$reservation+$totalprevious+$totalother+$penalty-$deposits,2,'.','')}}" readonly></td></tr>
                <!--<tr><td><input type="radio" value="1" name="paymenttype" checked onclick="getpaymenttype(this.value)"> Cash</td><td><input onclick="getpaymenttype(this.value)" type="radio" value="2" name="paymenttype" > ChecK</td></tr> -->
                
                <tr>
                    <td colspan="2">
                        <table class="table table-responsive"  style="background-color: #ccc">
                            <tr>
                                <td>
                                    <p>Check</p>
                                    <label>Bank/Branch</label>
                                    <input class='form-control text' type="text" name="bank_branch"  id="bank_branch">
                                    
                                </td>
                                <td>
                                    <input type="checkbox" name="iscbc" id="iscbc" value="cbc"> China Bank Check<label>Check Number</label>
                                    <input class='form-control text' type="text" name="check_number"  id="check_number">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><label>Check Amount</label>
                                    <input class='form-control divide debit' type="text" name="receivecheck"  id="receivecheck"  placeholder="0.00" style="text-align: right">
                                </td>
                            </tr>
                                       
                        </table>
                        <div style="color:red;font-weight: bold" id="cashdiff"></div>
                </td> </tr>
                <tr><td colspan="2"><label>FAPE:</label><input style ="text-align: right" type="text" placeholder="0.00" name="fape" id="fape"  class="form form-control divide debit">
                <tr><td colspan="2"><label>Cash Amount Rendered:</label><input style ="text-align: right" type="text" placeholder="0.00" name="receivecash" id="receivecash" class="form form-control divide debit">
                        </td></tr>
                <tr><td colspan="2"><label>Change:</label><input style ="text-align: right" type="text" value="0" name="change" id="change" readonly class="form form-control divide">
                        </td></tr>
                <tr>
                    <td colspan="2">
                        <div class="col-md-6"><input type="radio" name="depositto" value="China Bank" checked="checked"> China Bank</div>
                        <div class="col-md-6"><input type="radio" name="depositto" value="China Bank 2"> China Bank 2</div>
                        
                        <div class="col-md-6"><input type="radio" name="depositto" value="BPI 1"> BPI 1</div>
                        <div class="col-md-6"><input type="radio" name="depositto" value="BPI 2"> BPI 2</div>
                                            
                    </td>
                </tr> 
                <tr><td colspan="2"><label>Particular :</label><input type="text" name="remarks" id="remarks" class="form-control" ></td></tr>
             </table>    
   
        </div>
        </div>
    </div>
</div>
   


<script src="{{asset('/js/nephilajs/cashier.js')}}"></script>

@stop
