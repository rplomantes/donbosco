function gettrackplan(varstrand){
    var array={};
    array['level'] = $('#level').val();
    array['strand'] = varstrand;
    $.ajax({
            type: "GET", 
            url: "/gettrackplan", 
            data:array,
            success:function(data){
                $('#plancontainer').html("");
                $('#discountcontainer').html("");
                 $('#screendisplay').html("");
                $('#plancontainer').html(data);     
                }
            }); 
    
}




