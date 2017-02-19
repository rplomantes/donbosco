function getdiscount(){
   var department = document.getElementById('department').value;
   var myarray= {};
   myarray['department']=department;
   if(department=="TVET"){
   var plan = document.getElementById('plan').value;    
   var course = document.getElementById('course').value;  
   var batch = document.getElementById('batch').value;
   myarray['plan']=plan;
   myarray['course']=course
   myarray['batch']=batch;
   }
      $.ajax({
            type: "GET", 
            url: "/getdiscount" , 
            data: myarray,
            success:function(data){
                if(department=="TVET"){
                  $('#screendisplay').html(data);
                 // $('#discountcontainer').html(data);
                 // $('#trackcontainer').html(data);
                }else{   
                $('#screendisplay').html("");
                $('#discountcontainer').html(data); 
            }
                }
            }); 
   
}

function compute(){
    var department = $('#department').val();
    var level = $('#level').val();
    var strand = $('#strand').val();
    var course= $('#course').val();
    var discount = $('#discount').val();
    var plan = $('#plan').val();
    var id=$('#id').val();
    
    var arrays ={} ;
    arrays["department"] = department;
    arrays["level"] = level; 
    arrays["strand"] = strand; 
    arrays["course"]= course; 
    arrays["discount"] = discount;
    arrays["plan"] = plan;
    arrays["id"]= id;
   $.ajax({
            type: "GET", 
            url: "/compute" ,
            data : arrays,
            success:function(data){
                $('#screendisplay').html(data); 
                
                }
            }); 
   
    
}

function computetvet(){
    
    var discount = document.getElementById('discount').value;
    var tf = document.getElementById('tuitionfee').value
    var mc = document.getElementById('misc').value
    var gr = document.getElementById('gradfee').value
    var tuitionfee = 0;
    var misc = 0;
    var gradfee = 0;
    
   if(document.getElementById('tuitionfee_trainee').checked){
     tuitionfee = eval(tf-(tf*(discount/100)));   
     //tuitionfee = eval(tf*(discount/100));   
     
    }
    if(document.getElementById('misc_trainee').checked){
       misc = eval(mc)
    }
    if(document.getElementById('gradfee_trainee').checked){
       gradfee = eval(gr)
    } 
    
    document.getElementById('contribution').value = eval(tuitionfee+misc+gradfee)
    
   //alert()
}

function select_all(){
    $('.books').prop('checked', 'checked');
    $('#check_uncheck').html("<p style=\"cursor:pointer\" onclick=\"unselect_all()\">Uncheck All</p>")
}

function unselect_all(){
    //alert("hello")
    $('.books').attr('checked',false);
    $('#check_uncheck').html("<p style=\"cursor:pointer\" onclick=\"select_all()\">Select All</p>");
}