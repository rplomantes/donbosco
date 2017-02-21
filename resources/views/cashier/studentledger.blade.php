@extends("appcashier")

@section("content")
  <div class="container_fluid">  
      <div class="col-md-12">
             
          <div class="col-md-6" >
              <div class="form-group">
                  <label>SOA as of</label>
                  <input type="text" name="soadate" id="soadate" value="{{date('Y-m-d')}}" class="form">
                  <a href="#" class="btn btn-danger" onclick = "viewsoa('{{$student->idno}}')">View SOA</a>
              </div>
              <script>
                  function viewsoa(idno){
                      var soadate = document.getElementById('soadate').value
                      window.location="{{url('printsoa',$student->idno)}}/" + soadate
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
                 <b>Dropped</b>
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
                    <td align="right">{{number_format($ledger->amount-$ledger->debitmemo-$ledger->plandiscount-$ledger->otherdiscount-$ledger->payment,2)}}</td></tr>
                @endforeach
                @endif
                <tr><td>Total</td><td align="right">{{number_format($totalamount,2)}}</td>
                                  <td align="right">{{number_format($totaldiscount,2)}}</td> 
                                  <td align="right">{{number_format($totaldebitmemo,2)}}</td>
                                  <td align="right" style="color:red">{{number_format($totalpayment,2)}}</td>
                                  <td align="right"><strong>{{number_format($totalamount-$totaldiscount-$totaldebitmemo-$totalpayment,2)}}</strong></td></tr>
            </table>
              <h5>Payment History 
                  <span>
              </h5>
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
                   <?php $totalprevious = 0; $totalother = 0;
                   ?>
               @if(count($previousbalances)>0)
                    @foreach($previousbalances as $prev)
                    <tr><td>{{$prev->schoolyear}} - {{$prev->schoolyear +1}}</td><td  align="right">{{number_format($prev->amount,2)}}</td></tr>
                    <?php
                    $totalprevious = $totalprevious+$prev->amount;
                    ?>
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
             <form onsubmit="return dosubmit();" class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/payment') }}">
             {!! csrf_field() !!} 
             <input type="hidden" name="idno" value="{{$student->idno}}">
             <input type="hidden" id="reservation" id = "reservation" name="reservation" value="{{$reservation}}">
             <input type="hidden" name="totalprevious" id = "totalprevious" value="{{$totalprevious}}">
             <input type="hidden" name="totalother" id = "totalother" value="{{$totalother}}">
             <input type="hidden" name="totalmain" id = "totalmain" value="{{$totalmain}}">
             <input type="hidden" id="penalty" name="penalty" value="{{$penalty}}">
            
             <table class="table table-responsive table-bordered">
                <tr><td>Main Account</td><td align="right"><input onkeypress = "validate(event)"  onkeydown = "duenosubmit(event)"   type="text" name="totaldue" id="totaldue" style="text-align:right" class="form-control" value="<?php echo number_format($totaldue,2,'.','');?>"></td></tr>
                @if(count($previousbalances)> 0 )
                
                    
                        <tr><td>Previous Balance</td><td><input type="text" onkeypress = "validate(event)" onkeydown = "submitprevious(event,this.value)" name="previous" id="previous" style="text-align:right" class="form-control" value="{{$totalprevious}}"></td></tr>
                @else   
                <input type="hidden" name="previous" id="previous" value="0">
                @endif
                @if(count($othercollections)>0)
                @foreach($othercollections as $coll)
                    @if(($coll->amount - $coll->payment - $coll->debitmemo) > 0)
                         <tr><td>{{$coll->description}}</td><td><input type="text" name="other[{{$coll->id}}]"  class="other" style="text-align:right" class="form-control" onkeypress = "validate(event)" onkeydown = "submitother(event,this.value,'{{$coll->amount-$coll->payment-$coll->debitmemo}}','{{$coll->id}}')" value="{{$coll->amount-$coll->payment-$coll->debitmemo}}"></td></tr>
                    @endif
               @endforeach
                @endif
                @if($penalty > 0)
                <tr><td>Add: Penalty</td><td align="right"><span class="form-control">{{number_format($penalty,2)}}</span></td></tr>
                @endif
                @if($reservation > 0)
                <tr><td>Less: Reservation</td><td align="right"><span class="form-control">{{number_format($reservation,2)}}</span></td></tr>
                @endif
               
                <tr><td>Amount To Be Paid</td><td align="right"><input type="text" name="totalamount" id ="totalamount" style="color: red; font-weight: bold; text-align: right" class="form-control" value="{{ number_format($totaldue-$reservation+$totalprevious+$totalother+$penalty,2,'.','')}}" readonly></td></tr>
                <!--<tr><td><input type="radio" value="1" name="paymenttype" checked onclick="getpaymenttype(this.value)"> Cash</td><td><input onclick="getpaymenttype(this.value)" type="radio" value="2" name="paymenttype" > ChecK</td></tr> -->
                
                <tr><td colspan="2">
              
                    
                        <table class="table table-responsive"  style="background-color: #ccc"><tr>
                           
                        <tr><td><p>Check</p><label>Bank/Branch</label>
                        <input type="text" name="bank_branch"  id="bank_branch"  onkeydown = "nosubmit(event,'check_number')"  class="form form-control">
                        </td>
                        <td><input type="checkbox" name="iscbc" id="iscbc" value="cbc" onkeydown="submitiscbc(event,this.checked)"> China Bank Check<label>Check Number</label>
                        <input  type="text" name="check_number" id="check_number" onkeydown = "nosubmit(event,'receivecheck')" class="form form-control">
                        </td></tr>
                        <tr><td colspan="2"><label>Check Amount</label><input style ="text-align: right" type="text" name="receivecheck" id="receivecheck" onkeypress="validate(event)" onkeydown="submitcheck(event,this.value)"  placeholder="0.00" class="form form-control">
                        </td></tr>
                                       
                        </table>
                        <div style="color:red;font-weight: bold" id="cashdiff"></div>
                </td> </tr>
                <tr><td colspan="2"><label>Cash Amount Rendered:</label><input style ="text-align: right" type="text" placeholder="0.00" name="receivecash" id="receivecash" onkeypress="validate(event)" onkeydown="submitcash(event,this.value)" class="form form-control">
                        </td></tr>
                <tr><td colspan="2"><label>Change:</label><input style ="text-align: right" type="text" value="0" name="change" id="change" onkeypress="validate(event)" readonly class="form form-control">
                        </td></tr>
                <tr><td colspan="2">
                        <input type="radio" name="depositto" value="China Bank" checked="checked"> China Bank
                                            <input type="radio" name="depositto" value="BPI 1"> BPI 1
                                            <input type="radio" name="depositto" value="BPI 2"> BPI 2
                                            
                        </td></tr> 
                <tr><td colspan="2"><label>Particular :</label><input type="text" name="remarks" id="remarks" class="form-control" onkeypres = "validate(event)"></td></tr>
                <tr><td colspan="2"><input  style="visibility: hidden; font-weight: bold" type="submit" name="submit" id="submit" value ="Process Payment" class="btn btn-danger form form-control"> </td></tr>
               
             </table>    
   
        </div>
        </div>
    </div>
</div>
   
<script src="{{url('/js/nephilajs/cashier.js')}}"></script>    
<script src="{{url('/js/nephilajs/getpaymenttype.js')}}"></script>
@stop
