@extends('appcashier');
@section('content');
<div class="container">
    <div class="col-md-3">
    </div>    
    <div class="col-md-6" style="background-color: #ccc">
    <form method="POST" action="{{url('encashment')}}">
    {!! csrf_field() !!}
        <div class="form form-group">
            <label for="payee">Payee</label><input onkeypress="nosubmit(event,'bank')" type="text" name="payee" id="payee" class="form-control">
             
        </div>  
   <div class="form-group">
          <hr>
        </div>
        <div class="form-group">
            <input type ="radio" name="whattype" value="China Bank" checked="checked" onclick='popcheckno(this.value)'> China Bank (On us)
        </div> 
     
        <div class="form-group">
           <input type ="radio" name="whattype" value="BPI 1" onclick='popcheckno(this.value)'> BPI 1 (On us)
         </div> 
        <div class="form-group">   
           <input type ="radio" name="whattype" value="BPI 2" onclick='popcheckno(this.value)'> BPI 2 (On us)
         </div> 
        <div class="form-group">
           <input type ="radio" name="whattype" value="China Bank Other" onclick='popcheckno(this.value)'> China Bank Other Branch
         </div> 
        <div class="form-group">
           <input type ="radio" name="whattype" value="Other Check" onclick = "popcheckno(this.value)"> Other Check
        </div> 
        <div class="form-group">
          <hr>
        </div>
          <div class="form form-group">
              <label for="payee">Bank</label><input type="text" name="bank_branch" id="bank_branch" value="China Bank" onkeypress="nosubmit(event,'check_number')" class="form-control">
        </div>
          <div class="form form-group">
              <label for="payee">Check No</label><input type="text" name="check_number" id="check_number" onkeypress="nosubmit(event,'amount')" class="form-control">
          </div>
           <div class="form form-group">
               <label for="payee">Amount</label><input type="text" name="amount" id="amount" onkeypress = "validate(event)" style="text-align: right" class="form-control">
           </div>  
           <div class="form form-group">
               <h5>From</h5>
               <input type = "radio" name="withdrawfrom" value="China Bank" checked="checked">China Bank
               <input type = "radio" name="withdrawfrom" value="BPI 1">BPI 1
               <input type = "radio" name="withdrawfrom" value="BPI 2">BPI 2
           </div>
            <div class="form form-group">
                <input type="submit" value="Process Encashment" class="btn btn-danger form-control">
            </div> 
        </form> 
       </div>     
</div>    


<script src="{{url('/js/nephilajs/cashier.js')}}"></script>   
<script>
    function popcheckno(value){
        if(value == "Other Check"){
          document.getElementById('bank_branch').value = "";  
          document.getElementById('bank_branch').focus();  
        }else{
        if(value == "BPI 2" || value=="BPI 1"){
            value = "BPI"
        }else{
            value = "China Bank";
        }
            
        document.getElementById('bank_branch').value = value;
        document.getElementById('check_number').focus();
    }}
    
</script>    
@stop

