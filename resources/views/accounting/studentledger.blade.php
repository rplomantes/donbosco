@extends("appaccounting")

@section("content")
  <div class="container_fluid">  
      <div class="col-md-12">
             
          <div class="col-md-6" >
            <div class="form-group">
                <a href="{{url('/')}}" class="btn btn-primary">Back</a>
                <a href="{{url('/accounting', $student->idno)}}" class="btn btn-primary">Refresh</a>
                
                 <a href="{{url('/previous',$student->idno)}}" class="btn btn-primary"> Previous Accounts</a>
               
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
              <h5>Payment History</h5>
              <table class="table table-striped"><tr><td>Date</td><td>Ref Number</td><td>OR Number</td><td align="right">Amount</td><td>Details</td><td>Status</td></tr>
                  @if(count($debits)>0)
                  @foreach($debits as $debit)
                  <tr><td>{{$debit->transactiondate}}</td><td>{{$debit->refno}}</td><td>{{$debit->receiptno}}</td><td align="right">{{number_format($debit->amount + $debit->checkamount,2)}}</td><td><a href="{{url('/viewreceipt',array($debit->refno,$student->idno))}}">View</a></td>
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
               <table class="table table-striped"><tr><td>Description</td><td>Amount</td></tr>
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
            <h5>Due Today</h5>
            <div class="form form-group">
             
                <div class="btn btn-danger form-control" style="font-size:20pt; font-weight: bold; height: 50px">{{number_format($totaldue + $totalprevious - $reservation ,2) }}</div>
            
             </div>
            <div class="form form-group">
            
                <a href = "{{url('viewdebitmemo',$student->idno)}}" class="btn btn-primary form-control" style="font-size:20pt; font-weight: bold; height: 50px">DEBIT MEMO</a>
           
            </div>    
        </div>
            
    </div>
</div>
 
<script>

function computetotal(){
    if(document.getElementById('totaldue').value==""){
     totaldue = 0; }
    else { 
    totaldue = document.getElementById('totaldue').value;
    }
    var totalprevious = document.getElementById('previous').value;
    var penalty = document.getElementById('penalty').value;
    var reservation = document.getElementById('reservation').value;
    var totalother = 0;
    $('#other').each(function(index,element){
       totalother = totalother + element.value; 
    });
    var total = parseFloat(totaldue) + parseFloat(totalprevious)  + parseFloat(penalty) + parseFloat(totalother) + parseFloat(reservation);
    document.getElementById('totalamount').value = total.toFixed(2);
    //alert(total);
    
}


function submitother(event,amount,original,id){
    if(event.keyCode == 13) {
        
        if( parseFloat(original) < parseFloat(amount)){
            alert('Amount should not be more than ' + original)
            //document.getElementById("other[" + id +"]").value=original;
        }
        else{
        /*    
        document.getElementById('receive').focus(); 
        var totaldue = document.getElementById('totaldue').value;
        var totalprevious = document.getElementById('previous').value;
        var totalother = document.getElementById('totalother').value;
        var penalty = document.getElementById('penalty').value;
        var reservation = document.getElementById('reservation').value;
        var total = parseFloat(totaldue) + parseFloat(totalprevious) + parseFloat(totalother) + parseFloat(penalty) - parseFloat(reservation)+parseFloat(amount)-parseFloat(original);
        document.getElementById('totalamount').value = total.toFixed(2);
        */
       computetotal();
       if(document.getElementById('totalmain').value > 0 ){
           document.getElementById('totaldue').focus();
       }else{
           document.getElementById('submit').style.visibility="visible";
             document.getElementById('submit').focus();
       }
       //document.getElementById('receivecash').focus(); 
            }
        event.preventDefault();
        return false;
}

}


function dosubmit(){
    if(confirm("Continue to process DM ?")){
        return true;
    }else{
        document.getElementById('submit').style.visibility="hidden";
        //document.getElementById('receivecash').focus();
        return false;
    }
    
}
</script>
   
<script src="{{url('/js/nephilajs/getpaymenttype.js')}}"></script>
@stop


