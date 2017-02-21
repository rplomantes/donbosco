@extends("appcashier")

@section("content")
<div class="container_fluid">
    <div class="col-md-9">
        <div class="col-md-12">
        <div class="form-group">
                <a href="{{url('/cashier', $student->idno)}}" class="btn btn-primary">Back</a>
               
                <div class="pull-right">
                <label for="receiotno">Receipt No</label>
                <div class="btn btn-danger"><strong>{{Auth::user()->receiptno}}</strong>
                </div>
                </div>
        </div> 
        
        <table class="table table-striped" style="width: 80%">
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
        <hr />
       </div>
        <div id="myForm" class="col-md-12">
            <form class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('othercollection') }}" onsubmit="return confirm('Continue to process payment?')">
                    {!! csrf_field() !!} 
        <div class="col-md-8" > 
            <div style="padding: 10px;">
               
                    <input type="hidden" name="idno" value="{{$student->idno}}">
                    
                    <div class="form-group col-md-12">
                        <h5>Reservation : </h5> <input class="form-control" style="text-align:right" type="text" name="reservation" onkeypress = "validateother(event,'reservation')"   placeholder="0.00" id="reservation" onblur="ctotal()">
                    </div>
                    
                    <h5>Other Collection</h5>
                    <div class="col-md-3">Account Type</div>
                    <div class="col-md-3">Particular</div>
                    <div class="col-md-3">Department</div>
                    <div class="col-md-3">Amount</div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount1"   class="form-control" onchange = "getParticular(this.value,'particular1')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular1">  
                    <select class="form-control" name="particular1">
                    </select>  
                    </div>
                        
                    <div class="col-md-3" id="acct_department1">  
                    <select class="form-control" name="acct_department1">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                           
                        
                     <div class="col-md-3">
                         <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount1" name="amount1" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount2"   class="form-control" onchange = "getParticular(this.value,'particular2')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular2">  
                    <select class="form-control" name="particular2">
                    </select>  
                    </div>
                        
                        <div class="col-md-3" id="acct_department2">  
                    <select class="form-control" name="acct_department2">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                        
                     <div class="col-md-3">
                      <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount2" name="amount2" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount3"   class="form-control" onchange = "getParticular(this.value,'particular3')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular3">  
                    <select class="form-control" name="particular3">
                    </select>  
                    </div>
                        
                        <div class="col-md-3" id="acct_department3">  
                    <select class="form-control" name="acct_department3">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                        
                     <div class="col-md-3">
                      <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount3" name="amount3" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount4"   class="form-control" onchange = "getParticular(this.value,'particular4')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular4">  
                    <select class="form-control" name="particular4">
                    </select>  
                    </div>
                        <div class="col-md-3" id="acct_department4">  
                    <select class="form-control" name="acct_department4">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                        
                     <div class="col-md-3">
                      <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount4" name="amount4" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount5"   class="form-control" onchange = "getParticular(this.value,'particular5')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular5">  
                    <select class="form-control" name="particular5">
                    </select>  
                    </div>
                        
                    <div class="col-md-3" id="acct_department5">  
                    <select class="form-control" name="acct_department5">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                           
                        
                     <div class="col-md-3">
                         <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount5" name="amount5" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount6"   class="form-control" onchange = "getParticular(this.value,'particular6')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular6">  
                    <select class="form-control" name="particular6">
                    </select>  
                    </div>
                        
                    <div class="col-md-3" id="acct_department6">  
                    <select class="form-control" name="acct_department6">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                           
                        
                     <div class="col-md-3">
                         <input type="text" style="text-align:right" placeholder = "0.00" onkeypress = "validateother(event,'cash')" class="form-control" id = "amount6" name="amount6" onblur="ctotal()">
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-3">
                        <select name="groupaccount7"   class="form-control" onchange = "getParticular(this.value,'particular7')">
                        <option value = "none">--Select Group Account--</option>
                        @foreach($accounttypes as $accounttype)
                        <option value="{{$accounttype->accounttype}}">{{$accounttype->accounttype}}</option>
                        @endforeach
                        </select>    
                    </div>    
                    
                    <div class="col-md-3" id="accountparticular7">  
                    <select class="form-control" name="particular7">
                    </select>  
                    </div>
                        
                    <div class="col-md-3" id="acct_department7">  
                    <select class="form-control" name="acct_department7">
                        <option value="None">None</option>
                        @foreach($acct_departments as $acct_dept)
                        <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                        @endforeach
                    </select>  
                    </div>
                           
                        
                     <div class="col-md-3">
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
        <div class="col-md-4" style="background-color: #C6C6FF;">
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
                        <input type="radio" name="depositto" value="BPI 1" > BPI 1
                        <input type="radio" name="depositto" value="BPI 2"> BPI 2
                        
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
       <!-- end of form--></div>
    </div>
    <div class="col-md-3">
        <div class="btn btn-primary form form-control">Other Payment References</div>
        <div class="form-group">
            <div class="col-md-6">
            <h5>Reservation : </h5>
            </div> 
            <div class="col-md-6" style="text-align: right">
                <b>{{number_format($advance,2)}}</b>
            </div>    
        </div>
        <div class="btn btn-primary form form-control">Other Payment</div>
        <div class="form-group">
            
            <table class="table table-responsive"><tr><td>Particular</td><td style="text-align: right">Amount</td></tr>
            @if(count($paymentothers)> 0)
              
            @foreach($paymentothers as $paymentother)
            <tr><td>{{$paymentother->receipt_details}}</td><td style="text-align: right">{{$paymentother->amount}}</td></tr>  
            @endforeach        
            @endif
            </table>
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
            var r = document.getElementById('reservation').value != "" ? eval(document.getElementById('reservation').value):0;
            var amount1 = document.getElementById('amount1').value != "" ? eval(document.getElementById('amount1').value):0;
            var amount2 = document.getElementById('amount2').value != "" ? eval(document.getElementById('amount2').value):0;
            var amount3 = document.getElementById('amount3').value != "" ? eval(document.getElementById('amount3').value):0;
            var amount4 = document.getElementById('amount4').value != "" ? eval(document.getElementById('amount4').value):0;
            var amount5 = document.getElementById('amount5').value != "" ? eval(document.getElementById('amount5').value):0;
            var amount6 = document.getElementById('amount6').value != "" ? eval(document.getElementById('amount6').value):0;
            var amount7 = document.getElementById('amount7').value != "" ? eval(document.getElementById('amount7').value):0;
            document.getElementById('totalcredit').value = (r + amount1 + amount2 + amount3 + amount4 + amount5 + amount6 + amount7).toFixed(2);
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
@stop