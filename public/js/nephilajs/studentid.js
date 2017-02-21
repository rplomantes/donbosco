function getid(varid){
  
           $.ajax({
            type: "GET", 
            url: "/getid/" + varid , 
            success:function(data){
            $(".credentials").show();
            document.getElementById('credentials').style.visibility="visible"
            //$('.idno').html(data);
            document.getElementById('idno').value = data
            document.getElementById('id_number').value = data
           
            //alert(data);
            }       
            }); 
        }
                  
                