@extends('appaccounting')
@section('content')
<?php
$coa = \App\ChartOfAccount::pluck('accountname')->toArray();
$initialentry = \App\Accounting::where("posted_by",\Auth::user()->idno)->where('isfinal','0')->first();
if(count($initialentry)>0){
$uniqid = $initialentry->refno;    
}else{
$uniqid = uniqid();
}
$departments = DB::Select("Select * from ctr_acct_dept");
?>
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
  </script>
<div class="container-fluid">
    <div class=" col-md-12 form form-group" >
       <label class="label label-danger" style="font-size:15pt; background-color: pink;"> Ref No : {{$uniqid}}</label>
    </div>    
    <div style="padding-top: 10px; padding-bottom: auto;background: #ccc;height: 100px" class="col-md-12 panel panel-default">
        <div class="col-md-1">
            <label for = "acctcode">Account Code</label>
            <input type="text" name="acctcode" id="acctcode" class="form-control" readonly="readonly" style="background-color: #ddd;color: red">
        </div>
            <div class="col-md-3">
                <label for="accountname">Account Name</label>
                <input type="hidden" value="{{$uniqid}}" name="refno" id="refno">    
                <input class="form-control coa" id="accountname" name="accountname">
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
                <select class="form-control" name="department" id="department">
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
    <div class="col-md-12">
     <form id="form" onsubmit="return sub();" method="POST" action="{{url('/addentry')}}" >
            {!! csrf_field() !!} 
   
     </form>
     </div>  
</div>  

<script type="text/javascript">
$(document).ready(function(){ 
   $("#forsubmit").fadeOut();
   $("#amountdetails").fadeOut();
    partialtable();
   $("#accountname").keypress(function(e){
       if(e.keyCode==13){
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
               arrays['amount']=$("#amount").val();
               arrays['refno']=$("#refno").val();
               arrays['idno']= "{{Auth::user()->idno}}";
                //alert("hello")
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
    $("#processbtn").click(function(){
        if($("#particular").val()==""){
            alert("Please Fill-up Particular!!!");
            $("#particular").focus();
            }else{
                
              var arrays = {};
              arrays['refno'] = $("#refno").val();
              arrays['particular'] = $("#particular").val();
              arrays['idno']="{{Auth::user()->idno}}";
              arrays['totalcredit']=$("#totalcredit").val();
              $.ajax({
                  type:"GET",
                  url:"/postacctgremarks",
                  data:arrays,
                  success:function(data){
                     document.location = "{{url('addentry')}}" 
                  }
              });
               
            }
    })
    
    }); 

 function partialtable(){
   $.ajax({
        type:"GET",
        url:"/getpartialentry/" + $("#refno").val(),
            success:function(data){
               $("#partialentry").html(data);
                       
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
              document.location = "{{url('addentry')}}"
          }
      });
  }
 
</script>
@stop
