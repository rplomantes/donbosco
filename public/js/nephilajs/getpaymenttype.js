function getpaymenttype(ptype){
    $.ajax({
            type: "GET", 
            url: "/getpaymenttype/" + ptype , 
            success:function(data){
                $('#paymenttype').html(data);
                document.getElementById('check_number').focus();
                }
            }); 
   
  
}


