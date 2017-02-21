function getid(varid){
   
          $.ajax({
            type: "GET", 
            url: "/getid/" + varid , 
            success:function(data){
          
            document.getElementById('idno').value = data
            document.getElementById('password').value = data
            document.getElementById('password_confirmation').value = data 
           
            //alert('hello');
            }       
            }); 
        }
               