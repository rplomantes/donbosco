@extends('appcashier')
@section('content')
   <?php
    $cbcash=0;
    $cbcheck=0;
    $bpi1cash=0;
    $bpi1check=0;
    $bpi2cash=0;
    $bpi2check=0;
    $totalactual=0;
    ?>
<div class="container_fluid">
    <div class="col-md-12">
   <h4>Transaction Date: {{date('M d, Y',strtotime($transactiondate))}}</h4>
     <input type="hidden" name="trandate" id="trandate" value="{{$transactiondate}}">
    </div>
     <div class="col-md-6">
         <h5>Create Deposit Slip</h5>
            <div class="col-md-3"><label for="bank">Select <br> Bank</label>
                <select name="bank" id="bank" class="form form-control"><option value = "China Bank">China Bank</option>
                        <option value = "BPI 1">BPI 1</option>
                        <option value = "BPI 2">BPI 2</option>
                </select>
            </div>   
            <div class="col-md-3"><label for='deposittype'>Select Type <br>of Deposit</label>
                <select name="deposittype" id ="deposittype" class="form form-control"><option value ="0">Cash</option>
                    <option value ="1">Check</option>
                </select> 
            </div>
            <div class="col-md-3"><label for="amount">Deposit <br> Amount</label>
                <input type="text" name="amount" id="amount" class="form form-control" style="text-align:right" onkeypress="validate(event)">
            </div>
             <div class="col-md-3"> <br>
            <a href="#" onclick="myDeposit()" class="btn btn-primary">Add</a>
            </div>

    
            <div class="col-md-12">
                <hr>
                <h5>Deposit Slip Breakdown</h5>
                    @if(count($deposit_slips)>0)
                        <table class="table table-striped"><tr><td>Bank</td><td>Deposit Type</td><td>Amount</td><td></td></tr>
 
                            @foreach($deposit_slips as $deposit_slip)
                            <?php $totalactual = $totalactual + $deposit_slip->amount;
                            if($deposit_slip->bank == "China Bank"){
                                if($deposit_slip->deposittype == '0'){
                                    $cbcash = $cbcash + $deposit_slip->amount;
                                } else {
                                    $cbcheck = $cbcheck + $deposit_slip->amount;
                                }
                            } elseif ($deposit_slip->bank=="BPI 1") {
                                if($deposit_slip->deposittype == '0'){
                                    $bpi1cash = $bpi1cash + $deposit_slip->amount;
                                } else {
                                    $bpi1check=$bpi1check + $deposit_slip->amount;
                                }
                        } elseif ($deposit_slip->bank == "BPI 2") {
                            if($deposit_slip->deposittype == '0'){
                                $bpi2cash = $bpi2cash + $deposit_slip->amount;
                            } else {
                                $bpi2check = $bpi2check = $deposit_slip->amount;
                            }
                        
                    }
                            ?>
                                <tr><td>{{$deposit_slip->bank}}</td><td>
                                    @if($deposit_slip->deposittype=="0")
                                        Cash
                                    @else
                                        Check
                                    @endif
                                </td><td align="right">{{number_format($deposit_slip->amount,2)}}</td><td><a href='#' onclick="removeslip('{{$deposit_slip->id}}')">remove</a></td></tr>
                            @endforeach
                        </table>
                    @else
                        <h5>No Deposit slip Created Yet</h5>
                    @endif
    
                    <h5>Actual Deposit Total</h5>
                    <table class="table table-striped">
                    <tr><td>Bank</td><td>Cash</td><td>Check</td><td>Total</td></tr>
                    <tr><td>China Bank</td><td align="right">{{number_format($cbcash,2)}}</td><td align="right">{{number_format($cbcheck,2)}}</td><td align="right">{{number_format($cbcash+$cbcheck,2)}}</td></tr>
                    <tr><td>BPI 1</td><td align="right">{{number_format($bpi1cash,2)}}</td><td align="right">{{number_format($bpi1check,2)}}</td><td align="right">{{number_format($bpi1cash+$bpi1check,2)}}</td></tr>
                    <tr><td>China Bank</td><td align="right">{{number_format($bpi2cash,2)}}</td><td align="right">{{number_format($bpi2check,2)}}</td><td align="right">{{number_format($bpi1cash+$bpi2check,2)}}</td></tr>
                    
                    </table>
                        
            </div>
         
</div>            
<div class="col-md-6">
    <h5>Computed Receipt vs Actual Deposit</h5>
    <table class="table table-striped">
        <tr><td><b>Total Computed Receipts</b></td><td align="right"><b>{{number_format($totaldebit,2)}}</b></td></tr>
        <tr><td><b>Total Actual Deposit</b></td><td align="right"><b>{{number_format($totalactual,2)}}</b></td></tr>
        <tr><td><b>Overall Difference</b></td><td align="right"><b>{{number_format($totaldebit-$totalactual,2)}}</b></td></tr>
    </table>    
    <hr>
    <h5>Computed receipts</h5>
        <table class="table table-striped"><tr><td>Bank</td><td align="center">Cash</td><td align="center">Check</td><td align="center">Total</td></tr>
        @if(count($debits)>0)
            @foreach($debits as $debit)
            <tr><td>{{$debit->depositto}}</td><td align="right">{{number_format($debit->amount,2)}}</td>
            <td align="right">{{number_format($debit->checkamount,2)}}</td>
            <td align="right">{{number_format($debit->amount+$debit->checkamount,2)}}</td></tr>
            @endforeach
        @else
            <tr><td colspan="4"> No Collection Yet!!..</td></tr>
        @endif
        </table>
    
    <h5>Encashment</h5>
        <table class="table table-striped">
            <tr><td>Bank</td><td>Amount</td></tr>
                @if(count($encashments)=='0')
                    <tr><td colspan="2">No Encashment Yet!!..</td></tr>
                @else
                    @foreach($encashments as $encashment)
                        <tr><td>{{$encashment->whattype}}</td><td align="right">{{number_format($encashment->amount,2)}}</td></tr>
                    @endforeach
                @endif
        </table>
</div>

<div class="col-md-12">
    <a href="{{url('printactualdeposit',$transactiondate)}}" class="btn btn-primary">Print Actual Deposit</a>
    <hr>
</div>

    <div id="depositdisplay" class="depositdisplay">
       
    </div>    
</div>    
</div>

<script>
function myDeposit(){
    var idno = "{{\Auth::user()->idno}}";
    var bank = $("#bank").val();//document.getElementById('bank').value
    var deposittype = $("#deposittype").val()//document.getElementById('deposittype').value
    var amount = $("#amount").val();//document.getElementById('amount').value
    //alert(idno);
     var arrays ={} ;
    arrays["bank"] = bank;
    arrays["deposittype"] = deposittype; 
    arrays["amount"] = amount; 
    arrays["idno"] = idno;
    arrays["transactiondate"] = $('#trandate').val()
    //alert("hello")
     $.ajax({
         type:"GET",
         url:"/myDeposit",
         data: arrays,
         success:function(data){
             //$('#depositdisplay').html(data);
             if(data=="true"){
                 //location.reload();
                 document.location = "{{url('actualdeposit',$transactiondate)}}"
             }
         }
     });
}

function removeslip(refid){
   
    $.ajax({
        type:"GET",
        url:"/removeslip/" + refid,
       success:function(data){
             //$('#depositdisplay').html(data);
             if(data=="true"){
                 //location.reload();
                 document.location = "{{url('actualdeposit',$transactiondate)}}"
             }
         } 
    });
}

 function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
            theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
        }
        
        if(key == 13){
            myDeposit();
            theEvent.preventDefault();
            return false;
            
        }
    }    
</script>
@stop
