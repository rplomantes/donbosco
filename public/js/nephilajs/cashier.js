 var allowsubmit = 1;
 
 function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
            theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
        }
        
        if(key == 13){
            theEvent.preventDefault();
            return false;
            
        }
  
   
}

    function validateDeposit(evt) {
        var theEvent = evt || window.event;  
        var key = theEvent.keyCode || theEvent.which;
        if(key == 13){
            theEvent.preventDefault();
            document.getElementById('bank_branch').focus();
        } 
    }
/*
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
        
      event.preventDefault();
      return false;
    }
  });
});
*/

function duenosubmit(event){
        var theEvent = event || window.event;  
        var key = theEvent.keyCode || theEvent.which;
     if(key== 13) {
         totaldue=document.getElementById('totaldue').value;
         totalmain=document.getElementById('totalmain').value;
         if(parseFloat(totaldue) > parseFloat(totalmain)){
            alert("The amount should not be greater than " + totalmain);
            document.getElementById('totaldue').value="";
           
        }else{
            
             document.getElementById('bank_branch').focus(); 
             computetotal();
        }
      theEvent.preventDefault();
      return false;
    }
    
}

function computetotal(){
    
    var totaldue = document.getElementById('totaldue').value;
    var totalprevious = document.getElementById('previous').value;
    var totalother = 0;
    
    $('input.other').each(function(){
       totalother = parseFloat(totalother) + parseFloat(this.value); 
    });
   //for(i=0;ioth.)
   /*
    if(document.getElementById('other')){
      var tother = document.getElementById('other');
      for(i=0;i<tother.length;i++){
          totalother = totalother + tother[i].value;
      }
    }*/
    //alert(tother.length)
    //var totalother = document.getElementById('totalother').value;
    var penalty = document.getElementById('penalty').value;
    var reservation = document.getElementById('reservation').value;
    var total = parseFloat(totaldue) + parseFloat(totalprevious) + parseFloat(totalother) + parseFloat(penalty) - parseFloat(reservation);
    
    if(document.getElementById("remainingVoucher") !== null){
        
        var usevoucher = 0;
        var vouchers = parseFloat(document.getElementById('remainingVoucher').value);
        
        if(total > vouchers){
            usevoucher = vouchers;
            document.getElementById('Voucher').value = vouchers.toFixed(2);
            total = total - usevoucher;
        }else if(total < vouchers){
            usevoucher = total
            document.getElementById('Voucher').value = total.toFixed(2);
            total = total - usevoucher;
        }
    }
    
    if(document.getElementById("remainingESC") !== null){
        var useesc = 0;
        var esc = parseFloat(document.getElementById('remainingESC').value);
        
        if(total > esc){
            useesc = esc;
            document.getElementById('ESC').value = esc.toFixed(2);
            total = total - useesc;
        }else if(total < esc){
            useesc = total
            document.getElementById('ESC').value = total.toFixed(2);
            total = total - useesc;
        }
    }
    
    var usedeposit = 0;
    var deposits = parseFloat(document.getElementById('remainingdeposit').value);
    
    
    if(total > deposits){
        usedeposit = deposits
        document.getElementById('deposit').value = deposits.toFixed(2);
        $('#displaydeposit').html(deposits.toFixed(2));
        total = total - usedeposit;
    }else if(total < deposits){
        usedeposit = total
        document.getElementById('deposit').value = total.toFixed(2);
        $('#displaydeposit').html(total.toFixed(2));
        total = total - usedeposit;
    }
    
    document.getElementById('totalamount').value = total.toFixed(2);
    
    //alert(total);
}

function submitprevious(event,amount){
    if(event.keyCode == 13) {
        totalprevious = parseFloat(document.getElementById('totalprevious').value);
        if( totalprevious < parseFloat(amount)){
            alert('Amount should not be more than ' + totalprevious)
            document.getElementById('previous').value=totalprevious;
        }
        else{
            
             document.getElementById('bank_branch').focus(); 
             computetotal();
        }
      event.preventDefault();
      return false;
}

}

function submitother(event,amount,original,id){
    if(event.keyCode == 13) {
        
        if( parseFloat(original) < parseFloat(amount)){
            alert('Amount should not be more than ' + original)
            document.getElementById("other[" + id +"]").value=original;
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
       document.getElementById('bank_branch').focus(); 
            }
        event.preventDefault();
        return false;
}

}

function nosubmit(event, whatbranch){
    if(event.keyCode == 13) {
        document.getElementById(whatbranch).focus();
        event.preventDefault();
        return false;
 }
}


function dosubmit(){

    var totaldebit =  0;
    var totalcredit =  0;
    if(document.getElementById("receivecash") !== null){
        
        if(document.getElementById("receivecash").value !== ""){
            totaldebit = totaldebit+eval(document.getElementById("receivecash").value);
        }
        
    }

    if(document.getElementById("receivecheck") !== null){
        if(document.getElementById("receivecheck").value !== ""){
            totaldebit = totaldebit+eval(document.getElementById("receivecheck").value);
        }
        
    }
    
    if(document.getElementById("fape") !== null){
        if(document.getElementById("fape").value !== ""){
            totaldebit = totaldebit+eval(document.getElementById("fape").value);
        }
        
    }
    
    if(document.getElementById("cash") !== null){
        if(document.getElementById("cash").value !== ""){
            totaldebit = totaldebit+eval(document.getElementById("cash").value);
        }
        
    }
    if(document.getElementById("use_deposit") !== null){
        if(document.getElementById("use_deposit").value !== ""){
            totaldebit = totaldebit+eval(document.getElementById("use_deposit").value);
        }
        
    }    
    if(document.getElementById("check") !== null){
        if(document.getElementById("check").value !== ""){
            totaldebit = totaldebit+eval(document.getElementById("check").value);
        }
        
    }
    
    if(document.getElementById("totalcredit") !== null){
        if(document.getElementById("totalcredit").value !== ""){
            totalcredit = totalcredit+eval(document.getElementById("totalcredit").value);
        }
    }
    
    if(document.getElementById("totalamount") !== null){
        if(document.getElementById("totalamount").value !== ""){
            totalcredit = totalcredit+eval(document.getElementById("totalamount").value);
        }
        
    }
    

    
    if(confirm("Continue to process payment now?")){
        if(eval(totaldebit) >= totalcredit){
            if(allowsubmit == 0){
                return false;
            }
            allowsubmit = 0;
            return true;

        }else{
            alert("Cannot continue transaction.");
            return false;

        }
        
    }else{
        
        var submit = document.getElementById('submit');
        if(submit !== null){
            submit.style.visibility="hidden";
        }
        var receive = document.getElementById('receivecash');
        if(receive !== null){
            receive.focus();
        }
        
        return false;
    }
    
}

function submitiscbc(event, isSelected){
  if(event.keyCode == 13) {
  if(isSelected){
      document.getElementById('bank_branch').value="CBC";
      document.getElementById('check_number').focus();
  }else{
       document.getElementById('bank_branch').value="";
      document.getElementById('bank_branch').focus();
  }
        event.preventDefault();
        return false;
    }     
}

function submitcash(event,amount){
    
      if(document.getElementById('submit').style.visibility == "visible"){
       document.getElementById('submit').style.visibility = "hidden" 
       document.getElementById('change').value=""
    }
    
    if(event.keyCode == 13) {  
     
     checkreceive = 0;
     
     if(document.getElementById('receivecheck').value == ""){
       checkreceive = 0;  
     }   
     else {
      checkreceive =  eval(document.getElementById('receivecheck').value)  
     }
     
     if(document.getElementById('fape').value == ""){
       fape = 0;  
     }   
     else {
      fape =  eval(document.getElementById('fape').value)  
     }
     
     
     if(amount == ""){
         amount = 0;
     }
     
     if(eval(document.getElementById("totalamount").value) <= (eval(amount) + eval(checkreceive)+eval(fape))){ 
        if(amount == 0 && checkreceive == 0){
            alert("Cannot continue transaction without payment");
        }
        else{
            if(eval(document.getElementById("totalamount").value) < eval(amount) + eval(checkreceive) + eval(fape)){

             var num = eval(amount) + eval(checkreceive)+eval(fape) - eval(document.getElementById("totalamount").value)
             document.getElementById('change').value =   num.toFixed(2);
             document.getElementById('cashdiff').innerHTML ="";

            }
              document.getElementById('submit').style.visibility="visible";
              document.getElementById('remarks').focus();   
        }
          
      }  
     else {
          
            if(document.getElementById('receivecheck').value==""){
                receivedcheck = 0;
            } else {
                receivedcheck = document.getElementById('receivecheck').value;
            }
            
            if(document.getElementById('fape').value===""){
                fape = 0;
            } else {
                fape = document.getElementById('fape').value;
            }
            
            if(amount==""){
                amount=0;
            }
            
            
       var diff =  eval(document.getElementById("totalamount").value)-eval(amount)-eval(receivedcheck)-eval(fape);
       document.getElementById('cashdiff').innerHTML = "DIFFERENCE : " + diff.toFixed(2);    
       document.getElementById('submit').style.visibility="hidden";    
       document.getElementById('iscbc').focus();
     }
      event.preventDefault();
      return false;
}
    
}

function submitcheck(event, amount){
    document.getElementById('cashdiff').innerHTML ="";
    
    if(document.getElementById('submit').style.visibility == "visible"){
       document.getElementById('submit').style.visibility = "hidden" ;
       document.getElementById('change').value="";
    }
    
    if(event.keyCode == 13) {
        checkreceive = 0
       
        if(eval(amount) == eval(document.getElementById("totalamount").value)){
                document.getElementById('submit').style.visibility="visible";
                document.getElementById('remarks').focus();
        }
        else if(eval(amount) > eval(document.getElementById("totalamount").value)){
            alert("Amount Rereceived should not be greater than the amount to be collected!")
            document.getElementById('receivecheck').value= ""
        }
        else {
            if(document.getElementById('receivecash').value===""){
                receivedcash = 0;
            } else {
                receivedcash = document.getElementById('receivecash').value;
            }
            
            if(document.getElementById('fape').value===""){
                fape = 0;
            } else {
                fape = document.getElementById('fape').value;
            }
            
            
            if(amount==""){
                amount = 0;
            }
            
            var diff =  eval(document.getElementById("totalamount").value)-eval(amount)-eval(receivedcash)-eval(fape);
            document.getElementById('submit').style.visibility="hidden";
            document.getElementById('cashdiff').innerHTML = "DIFFERENCE : " + diff.toFixed(2);
            document.getElementById('receivecash').focus();
            
        }
        
        if($('#receivecheck').val() != ""){
            $('#check_number').prop('required', true);
            $('#bank_branch').prop('required', true);

        }else{
            
            $('#check_number').removeAttr('required');
            $('#bank_branch').removeAttr('required');            
        }
        
        event.preventDefault();
        return false;
        
    }
    
}

function submitfape(event, amount){
    document.getElementById('cashdiff').innerHTML =""
    if(document.getElementById('submit').style.visibility == "visible"){
       document.getElementById('submit').style.visibility = "hidden" 
       document.getElementById('change').value=""
    }
    if(event.keyCode == 13) {
        checkreceive = 0;
        
        if(eval(amount) == eval(document.getElementById("totalamount").value)){
                document.getElementById('submit').style.visibility="visible";
                document.getElementById('remarks').focus();
        }       

        else if(eval(amount) > eval(document.getElementById("totalamount").value)){
            document.getElementById('receivecash').value= "";
            document.getElementById('receivecheck').value="";
            document.getElementById('submit').style.visibility="visible";
            document.getElementById('remarks').focus();
            
        }
        else {
            if(document.getElementById('receivecash').value==""){
                receivedcash = 0;
            } else {
                receivedcash = document.getElementById('receivecash').value;
            }
            
            if(document.getElementById('receivecheck').value==""){
                receivedcheck = 0;
            } else {
                receivedcheck = document.getElementById('receivecheck').value;
            }
            
            
            if(amount==""){
                amount = 0;
            }
            var diff =  eval(document.getElementById("totalamount").value)-eval(amount)-eval(receivedcash)-eval(receivedcheck);
            document.getElementById('submit').style.visibility="hidden";
            document.getElementById('cashdiff').innerHTML = "DIFFERENCE : " + diff.toFixed(2);
            document.getElementById('receivecash').focus();
        }
     event.preventDefault();
     return false;
        
    }
    
}
