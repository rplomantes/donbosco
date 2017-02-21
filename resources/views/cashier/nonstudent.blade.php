@extends('appcashier');
@section('content');

<div class="container">
    <div class="col-md-7" >
        <div class="form-group">
        <a class=" btn btn-primary" href="{{url('/')}}">Home</a>
        
        <a class=" btn btn-primary" href="{{url('nonstudent')}}">Refresh</a>
        </div>
       
     
            <div style="padding: 10px;">
                <form class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/nonstudent') }}">
                    {!! csrf_field() !!} 
                   
                    <div class="form-group">
                        <h5>Receive From : </h5> <input class="form-control"  type="text" name="name" onkeypress="nosubmit(event,'groupaccount1')"  name id="name">
                    </div>
                    <h5>Particulars</h5>
                    <div class="col-md-4">Account Type</div><div class="col-md-4">Particular</div><div class="col-md-4">Amount</div>
                    <div class="form-group">
                    <div class="col-md-4">
                        <select name="groupaccount1"  id="groupaccount1" class="form-control" onchange = "getParticular(this.value,'particular1')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-4" id="accountparticular1">  
                    <select class="form-control" name="particular1">
                    </select>  
                    </div>
                     <div class="col-md-4">
                         <input type="text" style="text-align:right" value = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount1" name="amount1" onblur="ctotal()">
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="col-md-4">
                        <select name="groupaccount2"   class="form-control" onchange = "getParticular(this.value,'particular2')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-4" id="accountparticular2">  
                    <select class="form-control" name="particular2">
                    </select>  
                    </div>
                     <div class="col-md-4">
                      <input type="text" style="text-align:right" value = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount2" name="amount2" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-4">
                        <select name="groupaccount3"   class="form-control" onchange = "getParticular(this.value,'particular3')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-4" id="accountparticular3">  
                    <select class="form-control" name="particular3">
                    </select>  
                    </div>
                     <div class="col-md-4">
                      <input type="text" style="text-align:right" value = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount3" name="amount3" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-4">
                        <select name="groupaccount4"   class="form-control" onchange = "getParticular(this.value,'particular4')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-4" id="accountparticular4">  
                    <select class="form-control" name="particular4">
                    </select>  
                    </div>
                     <div class="col-md-4">
                      <input type="text" style="text-align:right" value = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount4" name="amount4" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                         <h5> Total </h5>
                         </div>
                        <div class="col-md-4">
                            <input type="text" style="text-align:right" value="0.00" disabled="true" id="totalcredit" name="totalcredit" class="form-control">
                        </div>
                    </div>
         
                  </div>
              </div>
        <div class="col-md-5" style="background-color: #C6C6FF;">
             <div style="padding: 10px;">
                 
                   
                     <div class="form-group">
                      <label>Cash Amount</label> <input type="text" style="text-align:right" value = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "cash" name="cash">
                    </div>
                    
                    <div class="form-group">
                         <table class="table table-responsive"  style="background-color: #ccc"><tr>
                           
                        <tr><td><p>Check</p><label>Bank/Branch</label>
                        <input type="text" name="bank_branch"  id="bank_branch"  onkeydown = "nosubmit(event,'check_number')"  class="form form-control">
                        </td>
                        <td><input type="checkbox" name="iscbc" id="iscbc" value="cbc" onkeydown="submitiscbc(event,this.checked)"> China Bank Check<label>Check Number</label>
                        <input  type="text" name="check_number" id="check_number" onkeydown = "nosubmit(event,'check')" class="form form-control">
                        </td></tr>
                        <tr><td colspan="2"><label>Check Amount</label><input style ="text-align: right" type="text" name="check" id="receivecheck" onkeypress="validateother(event)"   value="0.00" class="form form-control">
                        </td></tr>
                                       
                        </table>
                        
                    </div>    
                    <div class="form-group">
                        <input type="radio" name="depositto" value="China Bank" checked= "checked"> China Bank
                        <input type="radio" name="depositto" value="BPI 1" > BPI 1
                        <input type="radio" name="depositto" value="BPI 2"> BPI 2
                        
                    </div>   
                    
                    <div class="form-group">
                    <input type="submit" value="Process Payment" id="submit"  style="visibility:hidden" class="form-control btn-danger">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
 <script>
        function getParticular(group, particular){
           // alert(particular);
            $.ajax({
            type: "GET", 
            url: "/getparticular/" + group + "/" + particular, 
            success:function(data){
                if(particular == "particular1"){
                $('#accountparticular1').html(data);
                //document.getElementById('actualamount').focus();
                }
                else if(particular == 'particular2'){
                 $('#accountparticular2').html(data);
                }
                else if(particular == 'particular3'){
                 $('#accountparticular3').html(data);
                }
                else if(particular == 'particular4'){
                 $('#accountparticular4').html(data);
                }
              } 
            }); 
   
        }
        
        function validateother(evt, varcontrol){
            
      var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
            theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
        }
        
        if(key == 13){
            if(varcontrol == "reservation"){
            document.getElementById('cash').focus();   
            }
            else if(varcontrol=="cash"){
                var totalcredit = eval(document.getElementById('totalcredit').value);
                var totalcash = eval(document.getElementById('cash').value);
                var totalcheck = eval(document.getElementById('receivecheck').value);
                if(totalcredit == totalcash+totalcheck){
                    document.getElementById('submit').style.visibility="visible";
                    document.getElementById('submit').focus();
                }
            }
            theEvent.preventDefault();
            
            return false; 
        }
  
  
        }
        
        function ctotal(){
 // alert(document.getElementById('reservation').value)
            var r = eval(document.getElementById('reservation').value);
            var amount1 = eval(document.getElementById('amount1').value);
            var amount2 = eval(document.getElementById('amount2').value);
            var amount3 = eval(document.getElementById('amount3').value);
            var amount4 = eval(document.getElementById('amount4').value);
            document.getElementById('totalcredit').value = r + amount1 + amount2 + amount3 + amount4;
            var totalcredit = eval(document.getElementById('totalcredit').value);
                var totalcash = eval(document.getElementById('cash').value);
                var totalcheck = eval(document.getElementById('receivecheck').value);
                if(totalcredit == totalcash+totalcheck){
                    document.getElementById('submit').style.visibility="visible";
                    document.getElementById('submit').focus();
                }else{
                    document.getElementById('submit').style.visibility="hidden";
                }
        }
        
    </script>    
<script src="{{url('/js/nephilajs/cashier.js')}}"></script>    
@stop

