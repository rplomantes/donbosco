<?php
$mainaccount = DB::Select()

?>

@extends('appaccounting')
@section('content')

<h5>Credit</h5>
             <form onsubmit="return dosubmit();" class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/debitcredit') }}">
             {!! csrf_field() !!} 
             <input type="hidden" name="idno" value="{{$student->idno}}">
             <input type="hidden" id = "reservation" name="reservation" value="{{$reservation}}">
             <input type="hidden" name="totalprevious" id = "totalprevious" value="{{$totalprevious}}">
             <input type="hidden" name="totalother" id = "totalother" value="{{$totalother}}">
             <input type="hidden" name="totalmain" id = "totalmain" value="{{$totalmain}}">
             <input type="hidden" id="totalpenalty" name="totalpenalty" value="{{$penalty}}"> 
            
             <table class="table table-responsive table-bordered">
               @if($totalmain > 0 )
                <tr><td>Main Account<br>{{$totalmain}}</td><td align="right"><input onkeypress = "validate(event)"  onkeydown = "duenosubmit(event)"   type="text" name="totaldue" id="totaldue" style="text-align:right" class="form-control"></td></tr>
               @else
               <input type="hidden" name="totaldue" id="totaldue" value="0">
               @endif
                
                @if(count($previousbalances)> 0 )   
                <tr><td>Previous Balance<br>{{$totalprevious}}</td><td><input type="text" onkeypress = "validate(event)" onkeydown = "submitprevious(event,this.value)" name="previous" id="previous" style="text-align:right" class="form-control" ></td></tr>
                @else   
                <input type="hidden" name="previous" id="previous" value="0">
                @endif
                @if(count($othercollections)>0)
                @foreach($othercollections as $coll)
                    @if(($coll->amount - $coll->payment - $coll->debitmemo) > 0)
                         <tr><td>{{$coll->description}} <br>{{$coll->amount-$coll->payment-$coll->debitmemo}}</td><td><input type="text" name="other[{{$coll->id}}]"  id="other" style="text-align:right" class="form-control" onkeypress = "validate(event)" onkeydown = "submitother(event,this.value,'{{$coll->amount-$coll->payment-$coll->debitmemo}}','{{$coll->id}}')" value=""></td></tr>
                    @endif
               @endforeach
                @endif
                @if($penalty > 0)
                <tr><td>Penalty</td><td align="right"><input type="text" style="text-align:right" id="penalty" onkeypress = "validate(event)" name="penalty" value="{{$penalty}}" class="form form-control"></td></tr>
                @else
                <input type="hidden" id="penalty" name="penalty" value="0">
                @endif
               
                @if($reservation > 0)
                <tr><td>Add: Reservation</td><td align="right"><span class="form-control">{{number_format($reservation,2)}}</span></td></tr>
                @endif
                
                <tr><td>Amount To Be Credited</td><td align="right"><input type="text" name="totalamount" id ="totalamount" style="color: red; font-weight: bold; text-align: right" class="form-control"  readonly></td></tr>
                <!--<tr><td><input type="radio" value="1" name="paymenttype" checked onclick="getpaymenttype(this.value)"> Cash</td><td><input onclick="getpaymenttype(this.value)" type="radio" value="2" name="paymenttype" > ChecK</td></tr> -->
                
                <tr><td colspan="2">
              
                    
                        <table class="table table-responsive"  style="background-color: #ccc"><tr>
                           
                       
                        <tr><td colspan="2"><label>Debit</label>
                                
                               <select name="debitdescription" class="form form-control">
                                   @foreach($debitdescriptions as $desc)
                                   <option value="{{$desc->debitname}}">{{$desc->debitname}}</option>
                                   @endforeach
                               </select>    
                        </td></tr>
                                       
                        </table>
                       
                                            
                        </td></tr> 
                <tr><td colspan="2"><input  style="font-weight: bold;visibility: hidden" type="submit" name="submit" id="submit" value ="Process Debit Memo" class="btn btn-danger form form-control"> </td></tr>
               
             </table>    
   






@stop

