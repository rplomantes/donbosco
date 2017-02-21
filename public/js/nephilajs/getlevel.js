function getlevel(vardepartment){
            $.ajax({
            type: "GET", 
            url: "/getlevel/" + vardepartment , 
            success:function(data){
                $('#coursecontainer').html("");
                $('#levelcontainer').html("");
                $('#trackcontainer').html(""); 
                $('#plancontainer').html("");
                $('#discountcontainer').html("");
                $('#screendisplay').html("");
                    if(vardepartment=="TVET"){
                     $('#coursecontainer').html(data);     
                    }
                     else{
                     $('#levelcontainer').html(data); 
                     }
                 }
            }); 
    }
    
 


    
