function getplan(varlevel){
 
    $.ajax({
            type: "GET", 
            url: "/getplan/" + varlevel + "/" + $("#department").val(), 
            success:function(data){
                $('#discountcontainer').html("");
                $('#screendisplay').html("");
                $('#trackcontainer').html("");
                if($('#department').val() == "Senior High School"){
                $('#plancontainer').html("");
                    $('#trackcontainer').html(data); 
                }
                else{
                    if($('#level').val() == "Grade 9" ||$('#level').val() == "Grade 10"){
                    $('#plancontainer').html("");
                    $('#trackcontainer').html(data);
                    }else{
                    $('#plancontainer').html(data);
                }
                }
                
                }
            }); 
    
}

function gettvetplan(batch){
    var course = document.getElementById('course').value;
    $.ajax({
            type: "GET", 
            url: "/gettvetplan/" + batch + "/" + course, 
            success:function(data){
                $('#discountcontainer').html(data);
                $('#screendisplay').html("");
                $('#trackcontainer').html("");
                //$('#plancontainer').html(data);
                }
            }); 
    
}
