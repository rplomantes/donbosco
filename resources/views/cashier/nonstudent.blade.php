<?php
$banks = \App\Dedit::distinct('bank_branch')->pluck('bank_branch')->toArray();
$checkno = \App\Dedit::distinct('check_number')->take(5)->pluck('check_number')->toArray();
$payees = \App\NonStudent::distinct('fullname')->pluck('fullname')->toArray();

foreach($payees as $key=>$value){
  $payees[$key]=str_replace('"','\"',$value);
}
?>

@extends('appcashier')
@section('content')
  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
<script>
   $( function() {
    var bank = [<?php echo '"'.implode('","', $banks).'"' ?>];
    $( "#bank_branch" ).autocomplete({
      source: bank
    });
    });
/*
   $( function() {
    var checkno = [];
    $( "#check_number" ).autocomplete({
      source: checkno
    });
    });
*/
   $( function() {
    var payee = [<?php echo '"'.implode('","', $payees).'"' ?>];
    $( "#name" ).autocomplete({
      source: payee
    });
    });
</script>
<div class="container">
    <div class="col-md-9" >
        <div class="form-group">
        <a class=" btn btn-primary" href="{{url('/')}}">Home</a>
        
        <a class=" btn btn-primary" href="{{url('nonstudent')}}">Refresh</a>
        </div>
       
     
            <div style="padding: 10px;">
                <form class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/nonstudent') }}" onsubmit="return dosubmit();">
                    {!! csrf_field() !!} 
                   
                    <div class="form-group">
                        <h5>Receive From : </h5> <input class="form-control"  type="text" name="name" onkeypress="nosubmit(event,'groupaccount1')"  name id="name" required>
                    </div>
                    <h5>Other Collection</h5>
                    <div class="col-md-2">Account Type</div>
                    <div class="col-md-3">Account Name</div>
                    <div class="col-md-3">Particular</div>
                    <div class="col-md-2">Department</div>
                    <div class="col-md-2">Amount</div>
                    
                    <div class="form-group">
                    <div class="col-md-2">
                        <select name="basicaccount1"   class="form-control" onchange = "getAccount(this.value,'groupaccount1')">
                            <option value="" hidden="hidden">--Select Account--</option>
                            <option value = "1">Assets</option>
                            <option value = "2">Liabilities</option>
                            <option value = "3">Equity</option>
                            <option value = "4">Income</option>
                            <option value = "5">Expense</option>
                        </select>
                    </div>
                        
                    <div class="col-md-3">
                        <select name="groupaccount1" id="groupaccount1"   class="form-control" onchange = "getParticular(this.value,'particular1')">
                        </select>
                    </div>
                    
                    <div class="col-md-3" id="accountparticular1">  
                    <select class="form-control" name="particular1">
                    </select>  
                    </div>
                        
                    <div class="col-md-2" id="acct_department1">  
                    <select class="form-control" name="acct_department1">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                           
                        
                     <div class="col-md-2">
                         <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount1" name="amount1" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-2">
                        <select name="basicaccount2"  class="form-control" onchange = "getAccount(this.value,'groupaccount2')">
                            <option value="" hidden="hidden">--Select Account--</option>
                            <option value = "1">Assets</option>
                            <option value = "2">Liabilities</option>
                            <option value = "3">Equity</option>
                            <option value = "4">Income</option>
                            <option value = "5">Expense</option>
                        </select>
                    </div>
                        
                    <div class="col-md-3">
                        <select name="groupaccount2" id="groupaccount2" class="form-control" onchange = "getParticular(this.value,'particular2')">
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular2">  
                    <select class="form-control" name="particular2">
                    </select>  
                    </div>
                        
                        <div class="col-md-2" id="acct_department2">  
                    <select class="form-control" name="acct_department2">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                        
                     <div class="col-md-2">
                      <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount2" name="amount2" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-2">
                            <select name="basicaccount3" class="form-control" onchange = "getAccount(this.value,'groupaccount3')">
                                <option value="" hidden="hidden">--Select Account--</option>
                                <option value = "1">Assets</option>
                                <option value = "2">Liabilities</option>
                                <option value = "3">Equity</option>
                                <option value = "4">Income</option>
                                <option value = "5">Expense</option>
                            </select>
                        </div>
                        
                    <div class="col-md-3">
                        <select name="groupaccount3" id="groupaccount3" class="form-control" onchange = "getParticular(this.value,'particular3')">
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular3">  
                    <select class="form-control" name="particular3">
                    </select>  
                    </div>
                        
                        <div class="col-md-2" id="acct_department3">  
                    <select class="form-control" name="acct_department3">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                        
                     <div class="col-md-2">
                      <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount3" name="amount3" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-2">
                            <select name="basicaccount4"  class="form-control" onchange = "getAccount(this.value,'groupaccount4')">
                                <option value="" hidden="hidden">--Select Account--</option>
                                <option value = "1">Assets</option>
                                <option value = "2">Liabilities</option>
                                <option value = "3">Equity</option>
                                <option value = "4">Income</option>
                                <option value = "5">Expense</option>
                            </select>
                        </div>
                        
                    <div class="col-md-3">
                        <select name="groupaccount4" id="groupaccount4" class="form-control" onchange = "getParticular(this.value,'particular4')">
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular4">  
                    <select class="form-control" name="particular4">
                    </select>  
                    </div>
                        <div class="col-md-2" id="acct_department4">  
                    <select class="form-control" name="acct_department4">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                        
                     <div class="col-md-2">
                      <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount4" name="amount4" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-2">
                            <select name="basicaccount5" class="form-control" onchange = "getAccount(this.value,'groupaccount5')">
                                <option value="" hidden="hidden">--Select Account--</option>
                                <option value = "1">Assets</option>
                                <option value = "2">Liabilities</option>
                                <option value = "3">Equity</option>
                                <option value = "4">Income</option>
                                <option value = "5">Expense</option>
                            </select>
                        </div>
                        
                    <div class="col-md-3">
                        <select name="groupaccount5" id="groupaccount5" class="form-control" onchange = "getParticular(this.value,'particular5')">

                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular5">  
                    <select class="form-control" name="particular5">
                    </select>  
                    </div>
                        
                    <div class="col-md-2" id="acct_department5">  
                    <select class="form-control" name="acct_department5">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                           
                        
                     <div class="col-md-2">
                         <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount5" name="amount5" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-2">
                            <select name="basicaccount6" class="form-control" onchange = "getAccount(this.value,'groupaccount6')">
                                <option value="" hidden="hidden">--Select Account--</option>
                                <option value = "1">Assets</option>
                                <option value = "2">Liabilities</option>
                                <option value = "3">Equity</option>
                                <option value = "4">Income</option>
                                <option value = "5">Expense</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="groupaccount6" id="groupaccount6" class="form-control" onchange = "getParticular(this.value,'particular6')">

                            </select>    
                        </div>    
                    
                        <div class="col-md-3" id="accountparticular6">  
                            <select class="form-control" name="particular6">
                            </select>  
                        </div>
                        
                        <div class="col-md-2" id="acct_department6">  
                        <select class="form-control" name="acct_department6">
                            <option value="None">None</option>
                            @foreach($acct_departments as $acct_dept)
                            <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                            @endforeach
                        </select>  
                        </div>
                           
                        <div class="col-md-2">
                            <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount6" name="amount6" onblur="ctotal()">
                       </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-2">
                            <select name="basicaccount7" class="form-control" onchange = "getAccount(this.value,'groupaccount7')">
                                <option value="" hidden="hidden">--Select Account--</option>
                                <option value = "1">Assets</option>
                                <option value = "2">Liabilities</option>
                                <option value = "3">Equity</option>
                                <option value = "4">Income</option>
                                <option value = "5">Expense</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="groupaccount7" id="groupaccount7" class="form-control" onchange = "getParticular(this.value,'particular7')">
                            </select>    
                        </div>    
                    
                        <div class="col-md-3" id="accountparticular7">  
                            <select class="form-control" name="particular7">
                            </select>  
                        </div>
                        
                        <div class="col-md-2" id="acct_department7">  
                            <select class="form-control" name="acct_department7">
                                <option value="None">None</option>
                                @foreach($acct_departments as $acct_dept)
                                <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                                @endforeach
                            </select>  
                        </div>
                           
                        
                        <div class="col-md-2">
                            <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount7" name="amount7" onblur="ctotal()">
                       </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                         <h5> Total </h5>
                         </div>
                        <div class="col-md-4">
                            <input type="text" style="text-align:right" value="0.00" readonly="readonly" id="totalcredit" name="totalcredit" class="form-control">
                        </div>
                    </div>
         
                  </div>
              </div>
        <div class="col-md-3" style="background-color: #C6C6FF;">
<div class="form-group" style="background-color: #C6C6FF;">
             <div style="padding: 10px;">
                 
                   
                     <div class="form-group">
                      <label>Cash Rendered : </label> <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cashrendered')" class="form-control" id = "cash" name="cash">
                    
                     </div>
                    <div class="form-group">
                      <div>Change : <span style="color:red;font-size: 10pt; font-weight: bold" id="change">0.00</span></div>
                    </div>    
                    <div class="form-group">
                         <table class="table table-responsive"  style="background-color: #ccc"><tr>
                           
                        <tr><td><p>Check</p><label>Bank/Branch</label>
                        <input type="text" name="bank_branch"  id="bank_branch"  onkeydown = "nosubmit(event,'check_number')"  class="form form-control">
                        </td>
                        <td><input type="checkbox" name="iscbc" id="iscbc" value="cbc" onkeydown="submitiscbc(event,this.checked)"> China Bank Check<label>Check Number</label>
                        <input  type="text" name="check_number" id="check_number" onkeydown = "nosubmit(event,'check')" class="form form-control">
                        </td></tr>
                        <tr><td colspan="2"><label>Check Amount : </label><input style ="text-align: right" type="text" name="check" id="check" onkeypress="validateother(event,'check')"   placeholder="0.00" class="form form-control">
                        </td></tr>
                                       
                        </table>
                        
                    </div>    
                    <div class="form-group">
                        <input type="radio" name="depositto" value="China Bank" checked= "checked"> China Bank
                        <input type="radio" name="depositto" value="China Bank 2"> China Bank 2
                        <br>
                        <input type="radio" name="depositto" value="BPI 1" > BPI 1
                        <input type="radio" name="depositto" value="BPI 2"> BPI 2
                        <br>
                        <label><input type="radio" name="depositto" value="LANDBANK 1" > LANDBANK 1</label>
                        <label><input type="radio" name="depositto" value="LANDBANK 2"> LANDBANK 2</label>
                        
                    </div>   
                    <div class="form-group">
                        <label>Particular</label>
                        <input type="text" name="remarks" id="remarks" class="form-control" >
                    </div>    
                    <div class="form-group">
                    <input type="submit" value="Process Payment" id="submit"  style="visibility:hidden" class="form-control btn-danger">
                    </div>
                    
            </div>
        </div>
                    
                </form>
            </div>
        </div>
    </div>
<script>
        function getAccount(group, particular){
            
           // alert(particular);
            $.ajax({
            type: "GET", 
            url: "/getaccount2/" + group, 
            success:function(data){
                if(particular == "groupaccount1"){
                $('#groupaccount1').html(data);
                //document.getElementById('actualamount').focus();
                }
                else if(particular == 'groupaccount2'){
                 $('#groupaccount2').html(data);
                }
                else if(particular == 'groupaccount3'){
                 $('#groupaccount3').html(data);
                }
                else if(particular == 'groupaccount4'){
                 $('#groupaccount4').html(data);
                }
                else if(particular == 'groupaccount5'){
                 $('#groupaccount5').html(data);
                }
                else if(particular == 'groupaccount6'){
                 $('#groupaccount6').html(data);
                }
                else if(particular == 'groupaccount7'){
                 $('#groupaccount7').html(data);
                }
              } 
            }); 
   
        }
        
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
                else if(particular == 'particular5'){
                 $('#accountparticular5').html(data);
                }
                else if(particular == 'particular6'){
                 $('#accountparticular6').html(data);
                }
                else if(particular == 'particular7'){
                 $('#accountparticular7').html(data);
                }
              } 
            }); 
   
        }
        
        function validateother(evt, varcontrol){
            var totalcheck = 0;
            var totalcredit =0;
            var totalcash=0;
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
            if(varcontrol == "deposit"){
            document.getElementById('cash').focus();   
            }
            else if(varcontrol=="cash"){
                /*
                var totalcredit = eval(document.getElementById('totalcredit').value);
                var totalcash = eval(document.getElementById('cash').value);
                var totalcheck = eval(document.getElementById('check').value);
                if(totalcredit == totalcash+totalcheck){
                    document.getElementById('submit').style.visibility="visible";
                    document.getElementById('submit').focus();
                }*/
                document.getElementById('cash').focus();
            }
                else if(varcontrol=="cashrendered"){
                  if(document.getElementById('cash').value==""){
                      alert("No amount entered!!")
                      document.getElementById('bank_branch').focus()
                  }  else {
                    if(eval(document.getElementById('totalcredit').value)==0){
                        alert('Nothing to Process')
                        document.getElementById('amount1').focus();
                    }
                    else{
                     totalcredit = eval(document.getElementById('totalcredit').value);
                     totalcash = eval(document.getElementById('cash').value);
                     if(totalcash >= totalcredit){
                         total=totalcash-totalcredit;
                         document.getElementById('change').innerHTML = total.toFixed(2)
                        // document.getElementById('submit').style.visibility="visible";
                         document.getElementById('bank_branch').focus();
                     }
                     else{
                         document.getElementById('bank_branch').focus();
                     }
                    }}
                } else if(varcontrol == "check"){
                    if(document.getElementById("check").value==""){
                      totalcredit = eval(document.getElementById('totalcredit').value);
                      totalcash = document.getElementById('cash').value != "" ? eval(document.getElementById('cash').value):0;
                      totalcheck = 0;
                     if(totalcredit <= totalcheck + totalcash){
                         total = totalcheck+totalcash-totalcredit
                         document.getElementById('change').innerHTML = total.toFixed(2)
                         //document.getElementById('submit').style.visibility="visible";
                         document.getElementById('remarks').focus();
           
                        } else {
                            alert("Invalid Amount")
                        }
                        //alert("No amount entered!!")
                    }else{
                     if(eval(document.getElementById('check').value) > eval(document.getElementById("totalcredit").value)){
                         alert("Invalid amount")
                         document.getElementById('check').value = "";
                     }   else {
                      totalcredit = eval(document.getElementById('totalcredit').value);
                      totalcash = document.getElementById('cash').value != "" ? eval(document.getElementById('cash').value):0;
                      totalcheck = eval(document.getElementById('check').value);
                     if(totalcredit <= totalcheck + totalcash){
                         total = totalcheck+totalcash-totalcredit
                         document.getElementById('change').innerHTML = total.toFixed(2)
                         //document.getElementById('submit').style.visibility="visible";
                         document.getElementById('remarks').focus();
           
                        } else {
                            alert("Invalid Amount")
                        }
                     }
                    }
                }
            
            theEvent.preventDefault();
            
            return false; 
        }
  
  
        }
        
        function ctotal(){
 // alert(document.getElementById('reservation').value)

            var amount1 = document.getElementById('amount1').value != "" ? eval(document.getElementById('amount1').value):0;
            var amount2 = document.getElementById('amount2').value != "" ? eval(document.getElementById('amount2').value):0;
            var amount3 = document.getElementById('amount3').value != "" ? eval(document.getElementById('amount3').value):0;
            var amount4 = document.getElementById('amount4').value != "" ? eval(document.getElementById('amount4').value):0;
            var amount5 = document.getElementById('amount5').value != "" ? eval(document.getElementById('amount5').value):0;
            var amount6 = document.getElementById('amount6').value != "" ? eval(document.getElementById('amount6').value):0;
            var amount7 = document.getElementById('amount7').value != "" ? eval(document.getElementById('amount7').value):0;
            document.getElementById('totalcredit').value = (amount1 + amount2 + amount3 + amount4 + amount5 + amount6 + amount7).toFixed(2);
            var totalcredit = eval(document.getElementById('totalcredit').value);
                var totalcash = document.getElementById('cash').value != "" ? eval(document.getElementById('cash').value):0;
                var totalcheck = document.getElementById('receivecheck').value  != "" ? eval(document.getElementById('receivecheck').value):0;
                if(totalcredit == totalcash+totalcheck){
                    document.getElementById('submit').style.visibility="visible";
                    document.getElementById('submit').focus();
                }else{
                    document.getElementById('submit').style.visibility="hidden";
                }
        }
        
    </script>    
<script src="{{url('/js/nephilajs/cashier.js')}}"></script>    
<script>
            $('#cash').keypress(function(e){
            
            if(e.keyCode == 13){
		if(parseFloat($('#cash').val()) >= parseFloat($('#totalcredit').val())){
                    $('#remarks').focus();
                }
            }
        });
</script>

@stop

