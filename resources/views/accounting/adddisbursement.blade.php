<?php
$coa = \App\ChartOfAccount::pluck('accountname')->toArray();
$payees = \App\Disbursement::distinct('payee')->pluck('payee')->toArray();
$remarks =  \App\Disbursement::distinct('remarks')->pluck('remarks')->toArray();
foreach($payees as $key=>$value){
  $payees[$key]=str_replace('"','\"',$value);
}

foreach($remarks as $key=>$value){
  $remarks[$key]=str_replace('"','\"',$value);
}


$initialentry = \App\Accounting::where("posted_by",\Auth::user()->idno)->where('isfinal','0')->where('type','4')->first();
$voucherid =  \App\User::where('idno',\Auth::user()->idno)->first();
if(count($initialentry)>0){
$uniqid = $initialentry->refno;    
$voucherno = $voucherid->disbursementno;


}else{
$vouchzero="";    
$voucherno = $voucherid->disbursementno;
$voucheruserid = $voucherid->reference_number;
for($i=strlen($voucherno);$i<=5;$i++ ){
   $vouchzero = $vouchzero."0"; 
}
//$voucherno= $voucheruserid.$vouchzero.$voucherno;
//$voucherid->receiptno = $voucherid->receiptno+1;
//$voucherid->update();
$uniqid = uniqid();
}
$departments = DB::Select("Select * from ctr_acct_dept order by sub_department");
$bankaccounts = \App\ChartOfAccount::where('acctcode','>','110010')->where('acctcode','<=','110029')->get();
?>
@extends('appaccounting')
@section('content')
<style>
    .form-control{
        border-radius: 0px;
        margin-bottom: 2px;
        margin-top: 2px;
    }
</style>
  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
  <script>
   $( function() {
    var coa = [<?php echo '"'.implode('","', $coa).'"' ?>];
    $( ".coa" ).autocomplete({
      source: coa
    });
    });
    
   $( function() {
    var payee = [<?php echo '"'.implode('","', $payees).'"' ?>];
    $( "#payee" ).autocomplete({
      source: payee
    });
    });
    
  </script>
  <div class="container-fluid">
      <div class="col-md-2">
      <h2>DISBURSEMENT</h2>    
      </div>    
    <div class=" col-md-2 form form-group" >
        <label>Reference Number</label>
       <div class="btn btn-primary form-control">{{$uniqid}}</div>
    </div> 
    <div class=" col-md-2 form form-group" >
        <label>Voucher Number</label>
        <div class="btn btn-primary form-control">{{$voucherno}}</div>
    </div> 
    <div class=" col-md-6 form form-group" >
        <a href="{{url('dailyjournallist',date('Y-m-d',strtotime(\Carbon\Carbon::now())))}}" class="btn btn-primary navbar-right"> Daily Journal Summary</a>
    </div> 
    <div class="col-md-12">  
      <div class="form-group col-md-6">
          <label>Payee</label>
          <input type="text" class="form form-control" name="payee" id="payee">
      </div>    
    </div>
    <div style="padding-top: 10px; padding-bottom: auto;background: #fff5cc;height: 100px" class="col-md-12 panel panel-default">
        <div class="col-md-1">
            <label for = "acctcode">Account Code</label>
            <input type="text" name="acctcode" id="acctcode" class="form-control" readonly="readonly" style="background-color: #ddd;color: red">
        </div>
            <div class="col-md-3">
                <label for="accountname">Account Name</label>
                <input type="hidden" value="{{$uniqid}}" name="refno" id="refno">  
                <input type="hidden" value="{{$voucherno}}" name="referenceid" id="referenceid">  
                <input type="hidden" value="4" name="entry_type" id="entry_type">
                <input class="form-control coa" id="accountname" name="accountname">
            </div>
            <div class="amountdetails" id="amountdetails">
            <div class="col-md-2">
                <label for ="subsidiary">Subsidiary</label>
                <select class="form-control" name="subsidiary" id="subsidiary" onkeydown="changed(event,'department')">
                    <option value="" selected="selected" hidden="hidden">select Subsidiary if any</option>
                       
                </select>
            </div>   
            <div class="col-md-2">
                <label for ="department">Department</label>
                <select class="form-control" name="department" id="department" onkeydown="changed(event,'entrytype')">
                  <option>None</option>
                  @foreach($departments as $department)
                  <option value="{{$department->sub_department}}">{{$department->sub_department}}</option>
                  @endforeach
                </select>    
            </div> 
            <div class="col-md-2">
                <label for ="entrytype">Debit/Credit</label>
                <select class="form-control" name="entrytype" id ="entrytype" >
                    <option value="dr">Debit</option>
                    <option value="cr">Credit</option>
                </select>    
            </div> 
            <div class="col-md-2">
                <label for ="amount">Amount</label>
                <input type="text" class="form-control" name="amount" id="amount" style="text-align: right"> 
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-bordered"><thead><tr><th>Acct Code</th><th>Account Name</th><th>Subsidiary</th><th>Department</th><th>Debit</th><th>Credit</th><th>Remove</th></tr>
            </thead><tbody id="partialentry">
                   
            </tbody>
        </table>    
    </div>    
    <div class="col-md-12 forsubmit" id="forsubmit">
        <div class="col-md-8">
            <input placeholder="Paricular" type="text" name="particular" id="particular" class="form-control">
        </div> 
        <div class="col-md-4">
            <button class="form-control btn btn-primary processbtn" id="processbtn">Process Entry</button>
        </div>
           
    </div>    
     

  <div class="col-md-12" style="background-color:#fff5cc;padding-top:10px">
          <div class="form-group col-md-2">
              <label>Bank Account</label>
              <select name = "account" id="account" class="form-control">
                  @foreach($bankaccounts as $bankaccount)
                        <option value="{{$bankaccount->acctcode}}"
                        @if($bankaccount->acctcode == 110013)
                        selected= "selected"
                        @endif
                        >{{$bankaccount->accountname}}</option>
                  @endforeach
              </select>      
          </div>
          <div class="form-group col-md-2">
              <label>Amount</label>
              <input type="text" class="form-control" name="creditamount" id="creditamount" style="text-align:right" readonly>
          </div>
          <div class="form-group col-md-2">
              <label>Check Number</label>
              <input type = "text" name="checkno" id="checkno" class="form form-control">
          </div>

          <div class="form-group col-md-12">
              <label>Description</label>
              <input type="text" class="form form-control" name="remarks" id="remarks">
          </div> 
          <div class="form-group col-md-12">
              <button class="btn btn-primary form-control" id="btnprocess">Process Payment</button>
          </div> 
      </div>
    </div>
    
  
<script type="text/javascript">
$(document).ready(function(){ 
   $("#forsubmit").fadeOut();
   $("#amountdetails").fadeOut();
   
   $("#checkno").keypress(function(e){
      if(e.keyCode==13){
          if($("#checkno").val()==""){
              alert("Please Enter Check Number");
          } else {
              $("#remarks").focus();
          }
      } 
   });
   
   $("#remarks").keypress(function(e){
      if(e.keyCode==13){
          $("#btnprocess").focus();
      } 
   });

   $("#payee").keypress(function(e){
      if(e.keyCode==13){
          if($("#payee").val()==""){
              alert("Please Type Explanation/Remarks");
          } else {
              $("#accountname").focus();
          }
      } 
   });

    partialtable();
   $("#accountname").keypress(function(e){
       if(e.keyCode==13){
           if($("#accountname").val()==""){
               //alert("Please Fill-up Account Name");
               document.getElementById('checkno').focus();   
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
                    document.getElementById('subsidiary').focus();   
                    }    
                })
            }
       }    
    }); 
    $("#amount").keypress(function(e){
       if(e.keyCode==13){
           if($("#amount").val()==""){
               alert("Please Enter Amount!!")
           }else{
              
               var arrays={}
               arrays['acctcode']=$("#acctcode").val();
               arrays['accountname']=$("#accountname").val();
               arrays['subsidiary']=$("#subsidiary").val();
               arrays['department']=$("#department").val();
               arrays['entrytype']=$("#entrytype").val();
               arrays['entry_type']=$("#entry_type").val();
               arrays['amount']=$("#amount").val();
               arrays['refno']=$("#refno").val();
               arrays['referenceid'] = $('#referenceid').val();
               arrays['idno']= "{{Auth::user()->idno}}";
               
               $.ajax({
                  type:"GET",
                  url:"/postpartialentry",
                  data:arrays,
                    success:function(data){
                        $("#partialentry").html(data);
                        $("#acctcode").val("");
                        $("#accountname").val("");
                        $("#subsidiary").html("<option>Select Subsidiary If Any</option>");
                        $("#amount").val("");
                        $("#accountname").focus();
                        $("#creditamount").val($("#crdrdiff").val()); 
                        if($("#balance").val() == "yes"){
                         $("#forsubmit").fadeIn();   
                        }else{
                         $("#forsubmit").fadeOut();
                        }
                        $("#amountdetails").fadeOut();
                    }
               });
               
           }
       } 
    });
    
    $("#btnprocess").click(function(){
    if($("#checkno").val()=="" || $("#payee").val()=="" || $("#remarks").val()==""){
        alert("Please Fill Up Necessary Fields!!");
        //return false;
    }    
    else{
        document.getElementById("btnprocess").disabled = true;
         var arrays={};
         arrays['refno'] = $("#refno").val();
         arrays['voucherno'] = $("#referenceid").val();
         arrays['payee'] = $("#payee").val();
         arrays['checkno']= $("#checkno").val();
         arrays['remarks']=$("#remarks").val();
         arrays['creditamount']=$("#creditamount").val();
         arrays['bankaccount'] = $("#account").val();
         arrays['idno']="{{\Auth::user()->idno}}";
         arrays['entry_type']=$("#entry_type").val();
         arrays['accountcode'] = $("#account").val();
       // alert($("#account"))
;         $.ajax({
             type:"GET",
             url:"processdisbursement",
             data:arrays,
             success:function(data){
             document.location= "{{url('printdisbursement')}}" + "/" + $("#refno").val()
             }
        });
    }
    })
    /*
    $("#processbtn").click(function(){
        if($("#particular").val()==""){
            alert("Please Fill-up Particular!!!");
            $("#particular").focus();
            }else{
                
              var arrays = {};
              arrays['refno'] = $("#refno").val();
              arrays['referenceid'] = $("#referenceid").val();
              arrays['particular'] = $("#particular").val();
              arrays['idno']="{{Auth::user()->idno}}";
              arrays['totalcredit']=$("#totalcredit").val();
              $.ajax({
                  type:"GET",
                  url:"/postacctgremarks",
                  data:arrays,
                  success:function(data){
                     document.location = "{{url('printjournalvoucher')}}" + "/" + $("#refno").val(); 
                  }
              });
               
            }
    })*/
    
    }); 

 function partialtable(){
   $.ajax({
        type:"GET",
        url:"/getpartialentry/" + $("#refno").val(),
            success:function(data){
               $("#partialentry").html(data);
               $("#creditamount").val($("#crdrdiff").val());        
                        if($("#balance").val() == "yes"){
                         $("#forsubmit").fadeIn();   
                        } else{
                         $("#forsubmit").fadeOut();
                        }
                        
                    }
            
     });
   
  
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
 
 function removeacctgpost(id){     
         var arrays={};
         arrays['id']=id;
         arrays['refno']=$("#refno").val();
     $.ajax({
         type:"GET",
         url:"/removeacctgpost",
         data:arrays,
         success:function(data){
             $("#partialentry").html(data);
             $("#creditamount").val($("#crdrdiff").val()); 
             if($("#balance").val() == "yes"){
                $("#forsubmit").fadeIn();   
             }else{
                 $("#forsubmit").fadeOut();
                   }
         }
     });
 
    
    }
 
  function removeall(){
      var array={};
      array['refno'] = $("#refno").val();
      $.ajax({
          type:"GET",
          url:"/removeacctgall",
          data:array,
          success:function(data){
              if(data=="true")
              document.location = "{{url('adddisbursement')}}"
          }
      });
  }
  
  function changed(event,to){
      if(event.keyCode == 13) {
          $("#"+to).focus();
      }
  }
  
  $("#entrytype").click(function(){
          $("#amount").focus();
  });
</script>


@stop
