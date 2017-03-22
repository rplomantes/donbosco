<?php
$coa = \App\ChartOfAccount::pluck('accountname')->toArray();
$previousbalance = 0;
$mainbalance=0;
$mainaccount = DB::Select("SELECT sum(amount) - sum(payment) - sum(plandiscount) - sum(otherdiscount) -sum(debitmemo) as balance from ledgers"
        . " where categoryswitch <= '6' and idno = '$idno'");
$previous = DB::Select("SELECT sum(amount) - sum(payment) - sum(plandiscount) - sum(otherdiscount)- sum(debitmemo) as balance from ledgers"
        . " where categoryswitch >= '10' and idno = '$idno'");
if(count($previous)>0){
foreach($previous as $prev){
    $previousbalance = $previousbalance + $prev->balance;
}}

if(count($mainaccount)>0){
foreach($mainaccount as $main){
    $mainbalance = $mainbalance + $main->balance;
}}
$others = DB::Select("SELECT amount - payment-plandiscount-otherdiscount as balance, id, description from ledgers"
        . " where categoryswitch > '6'  and categoryswitch < '10' and  idno = $idno ");
$level = \App\Status::where('idno',$idno)->first()->level;
$student=\App\User::where('idno',$idno)->first();
$debitmemozero="";
$debitmemoid =  \App\User::where('idno',\Auth::user()->idno)->first();
$debitmemono = $debitmemoid->debitmemono;
$debitmemouserid = $debitmemoid->reference_number;
for($i=strlen($debitmemono);$i<=5;$i++ ){
   $debitmemozero = $debitmemozero."0"; 
}
$debitmemono= $debitmemouserid.$debitmemozero.$debitmemono;
$uniqid = uniqid();
$departments = DB::Select("Select * from ctr_acct_dept");
?>

@extends('appaccounting')
@section('content')

  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
  <script>
   $( function() {
    var coa = [<?php echo '"'.implode('","', $coa).'"' ?>];
    $( ".coa" ).autocomplete({
      source: coa
    });
    });
  </script>
<div class="container-fluid">
    <div class="col-md-12"> 
    <div class="col-md-3">
      <h2>DEBIT MEMO</h2>
    </div>
    <div class=" col-md-2 form form-group" >
        <label>Reference Number</label>
       <div class="btn btn-danger form-control">{{$uniqid}}</div>
    </div> 
    <div class=" col-md-2 form form-group" >
        <label>Debit Memo Number</label>
        <div class="btn btn-danger form-control">{{$debitmemono}}</div>
    </div>     
    
             <form onsubmit="return dosubmit();" class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/debitcredit') }}">
             {!! csrf_field() !!} 
             <input type="hidden" name="idno" value="{{$idno}}"> 
             <input type="hidden" name="totalprevious" id = "totalprevious" value="{{$previousbalance}}">
             <input type="hidden" name="totalmain" id = "totalmain" value="{{$mainbalance}}">
    </div>          
    <div class="col-md-6">
             <h5>Credit</h5>
            
             <table class="table table-responsive table-bordered">
               @if($mainbalance > 0 )
                <tr><td>Main Account<br>{{$mainbalance}}</td><td align="right"><input onkeypress = "validate(event)"  max="{{$mainbalance}}"  type="text" name="totaldue" id="totaldue" style="text-align:right" class="form-control"></td></tr>
               @else
               <input type="hidden" name="totaldue" id="totaldue" value="0">
               @endif
                
                @if($previousbalance > 0 )   
                <tr><td>Previous Balance<br>{{$totalprevious}}</td><td><input type="text" onkeypress = "validate(event)" max="{{$totalprevious}}" name="previous" id="previous" style="text-align:right" class="form-control" ></td></tr>
                @else   
                <input type="hidden" name="previous" id="previous" value="0">
                @endif
                
                @if(count($others)>0)
                @foreach($others as $coll)
                    @if($coll->balance  > 0)
                         <tr><td>{{$coll->description}} <br>{{$coll->balance}}</td><td><input type="hidden" name="othermax[{{$coll->id}}]" value="{{$coll->balance}}"> <input type="text" name="others[{{$coll->id}}]"  id="others"  style="text-align:right" class="form-control others" onkeypress = "validate(event)" onkeydown = "submitother(event,this.value,'{{$coll->balance}}','{{$coll->id}}')" value=""></td></tr>
                    @endif
                @endforeach
                @endif
               
                
                <tr><td>Amount To Be Credited</td><td align="right"><input type="text" name="totalamount" class="totalamount" id ="totalamount" value="0.00" style="color: red; font-weight: bold; text-align: right" class="form-control"  readonly="readonly"></td></tr>
             </table>    
               
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <table class="table table-striped">
                    <tr><td>Student ID</td><td>:</td><td>{{$idno}}</td></tr>
                    <tr><td>Student Name</td><td>:</td><td>{{$student->lastname}}, {{$student->firstname}}</td></tr>
                    <tr><td>Grade/Level</td><td>:</td><td>{{$level}}</td></tr>
                </table>    
            </div>    
        </div>    
             
        <div class="form-group">
            <div class="col-md-12" style="padding-top: 10px; padding-bottom: auto;background: #b6c4db;height: 100px">
                <div class="col-md-1">
                    <label for = "acctcode">Account Code</label>
                    <input type="text" name="acctcode" id="acctcode" class="form-control" readonly="readonly" style="background-color: #ddd;color: red">
                </div>
                <div class="col-md-3">
                    <label for="accountname">Account Name</label>
                    <input type="hidden" value="{{$uniqid}}" name="refno" id="refno">  
                    <input type="hidden" value="{{$debitmemono}}" name="receiptno" id="receiptno">
                    <input type="hidden" value="5" name="entry_type" id="entry_type">
                    <input type="text" class="form-control coa" id="accountname" name="accountname" onkeypress="nosubmit(event)">
                </div>
                <div class="amountdetails" id="amountdetails">
                    <div class="col-md-2">
                        <label for ="subsidiary">Subsidiary</label>
                        <select class="form-control" name="subsidiary" id="subsidiary">
                            <option value="" selected="selected" hidden="hidden">select Subsidiary if any</option>
                        </select>    
                    </div>   
                    <div class="col-md-2">
                        <label for ="department">Department</label>
                        <select class="form-control" name="department" id="department" class="department">
                            <option>None</option>
                           @foreach($departments as $department)
                            <option value="{{$department->sub_department}}">{{$department->sub_department}}</option>
                           @endforeach
                        </select>    
                    </div>
                    
                    <div class="col-md-2">
                        <label for ="entrytype">Debit/Credit</label>
                        <select class="form-control" name="entrytype" id ="entrytype">
                            <option value="dr">Debit</option>
                           
                        </select>    
                    </div> 
                    <div class="col-md-2">
                            <label for ="amount">Amount</label>
                            <input type="text" class="form-control" name="amount" id="amount" style="text-align: right"> 
                    </div>
                 </div>   
                </div>
        </div>
            
        <div class="form-group"> 
            <div class="col-md-12">
                <hr>
            <h5>Debit</h5>    
            <table class="table table-bordered table-striped" id="debitentry">
                <tbody>
                <tr><td>Acct Code</td><td>Account Name</td><td>Subsidiary</td><td>Department</td><td>Amount</td><td></td></tr>
                </tbody>
                 
            </table>
           </div>     
        </div>    
        <div class="form-group">
         <div class="col-md-8">
             <input type="text" placeholder="Explanation" name="remarks" id="remarks" class="form form-control">
         </div>    
         <div class="col-md-4">   
         <input type="submit" name="submit"  value="Process Debit Memo" id="processbtn" class="form-control btn btn-primary processbtn">
         </div>
         </div>  
             
     </form>
</div>

<script>
  $(document).ready(function(){
  $("#amountdetails").fadeOut(); 
  $("#processbtn").fadeOut();
  $("#remarks").fadeOut();
  $("#amount").keypress(function(e){
      if(e.keyCode==13){
          e.returnValue=false
          if(e.preventDefault) e.preventDefault();
       if($('#amount').val()==""){
           alert("Please Fill-in the Amount!!!")
       }   else{
           string = "<tr><td><input type=\"text\" name=\"acctcodedebit[]\" "
           string = string + " value = \"" + $("#acctcode").val() + "\"></td><td>" + $("#accountname").val()
           string = string + " </td><td>" + "<input type=\"text\" name=\"subsidiary[]\" value=\"" + $("#subsidiary").val() + "\"></td><td> <input type = \"text\" name=\"department[]\" value=\"" + $("#department").val() 
           string = string + "\"></td><td><input type=\"text\" class=\"debitamount\" name=\"debitamount[]\" value=\"" + $("#amount").val() + "\"></td><td><a href=\"#\" onclick = \"remove(this)\" id=\"remove\">Remove</a></tr>"     
           //$("#debitentry").append(string);
           $("#assess").children().append(string);
           $("#amount").val("")
           $("#amountdetails").fadeOut();
           $("#acctcode").val("")
           $("#accountname").val("");
           checkifbalance()
       }
      }
  })
  
  })
  
  function checkifbalance(){
  totalcredit = parseFloat($("#totalamount").val());
  totaldebit=0;
  
  $('.debitamount').each(function(index,element){
       totaldebit = parseFloat(totaldebit) + parseFloat(element.value); 
    });
   if(totalcredit == 0 ){
       alert("Please fill-in the credit side!")
   } else{
       if(totalcredit == totaldebit){
          $("#processbtn").fadeIn();
          $("#remarks").fadeIn();
       } else {
           $("#processbtn").fadeOut();
          $("#remarks").fadeOut(); 
       }
   }
  }
  
  function remove(object){
  $(object).parents('tr').remove();
  checkifbalance();
  }
  
  function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
            theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
        }
        
        if(key == 13){
            theEvent.preventDefault();
            computetotal();
            return false;
        }
  
   
}

function computetotal(){
    if(document.getElementById('totaldue').value==""){
     totaldue = 0; }
    else { 
    totaldue = parseFloat($("#totaldue").val());
    }
    if(document.getElementById('previous').value==""){
     totalprevious = 0;
    }else{
    totalprevious = parseFloat($("#previous").val());
    }
    //var penalty = document.getElementById('penalty').value;
    //var reservation = document.getElementById('reservation').value;
    var totalother = 0;
    $('.others').each(function(index,element){
       if(element.value != ""){ 
       totalother = parseFloat(totalother) + parseFloat(element.value); 
        }
    });
    var total = totaldue + totalother + totalprevious;
    document.getElementById('totalamount').value = total.toFixed(2);
    //alert(total);
    
}

function nosubmit(evt){
    var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
        if(key == 13  ){ 
            theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
                if($("#accountname").val()==""){
               alert("Please Fill-up Account Name");
           } else{              
                var arrays={};
                arrays['accountname']=$("#accountname").val();
                $.ajax({
                type:"GET",
                url:"/getaccountcode",
                data:arrays,
                    success:function(data){
                    $("#acctcode").val(data)
                    popsubsidiary(data)
                    $("#amountdetails").fadeIn();
                    }    
                })
            }
        }
}

function popsubsidiary(acctcode){
     
    var arrays={};
    arrays['acctcode']=acctcode;
    $.ajax({
        type:"GET",
        url:"/getsubsidiary",
        data:arrays,
            success:function(data){
                $("#subsidiary").html(data);              
            }
    });
 }
 
 
 function dosubmit(){
     if($("#remarks").val()==""){
         alert("Please Fill-in Particulars");
         return false;
     }
 }
</script>    

@stop

