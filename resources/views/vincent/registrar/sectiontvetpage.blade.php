@extends("app")
@section("content")

<div class="container">
    <div class="col-md-6">
        <div class="form form-group">
            <label for ="batch">Select Batch</label>
            <select name="batch" id="batch" class="form form-control">
                <option hidden>--Select--</option>
                @foreach($levels as $level)
                    <option value="{{$level->period}}">Batch {{$level->period}}</option>
                @endforeach
            </select>           
         </div> 
        <div class="form form-group" id="courseSel" style="visibility: hidden">
            <label for ="course">Select Course</label>
            <select name="course" id="course"  class="form form-control">
                <option hidden>--Select--</option>
                @foreach($courses as $course)
                    <option value="{{$course->course}}">{{$course->course}}</option>
                @endforeach
            </select>          
         </div>         

      <div id="stranddisplay">
      </div>    
      <div id="studentlist">
      </div>    
           
    </div> 
    <div class="col-md-6">
        <div class="form form-group">
            <div id="sectioncontrol">
            </div>
            <div id="sectionlist">
            </div>
        </div>    
    </div>    
</div>

<script>
    
$('#batch').change(function(){
    
    var course = $('#course').val();
    $("#studentlist").html("");
    $("#courseSel").css("visibility","visible");
    
    //if(course != NULL){
        

        
    //}
});
$('#course').change(function(){
    
    var course = $('#course').val();
        gettvetstudentlist(course);
        getsection(course);
 
});


function gettvetstudentlist(strand){
    $("#studentlist").html("");
    
    var array={};
    var batch = $('#batch').val();
    
    if (batch === "87"){
        batch = "1st Batch";
    }
    array['strand'] = strand;
        $.ajax({
            type: "GET", 
            data: array,
            url: "/gettvetstudentlist/" + batch +"/"+strand, 
            success:function(data){
                $("#studentlist").html(data);
                
            }
            
});
       
}


function getstrandall(strand){
    getstudentlist(strand);
    getsection(strand);
}


function callsection(){
    getsectionlist()
}

function getsection(strand){
    
    var array = {};
    array['course'] = strand;
    
    var batch = $('#batch').val();
    
 
       $.ajax({
            type: "GET",
            data: array,
            url: "/gettvetsection/" + batch , 
            success:function(data){
                $("#sectioncontrol").html(data);
                getsectionlist();
            }
});

}


function getsectionlist(){
    

            course = $("#course").val();

        var array={};
        array['course']=course;
         $.ajax({
            type: "GET", 
            data: array,
            url: "/gettvetsectionlist/" + $('#batch').val() + "/" + $('#section').val() , 
            success:function(data){
                $("#sectionlist").html(data);
            }
});
}

function setsection(id){

        strand=$("#course").val();
    
    //alert($("#strand").val());
    $.ajax({
            type: "GET", 
            url: "/setsection/" + id + "/" + $('#section').val() , 
            success:function(data){
                if(data == "true"){
                    gettvetstudentlist(strand);
                    getsectionlist();
                }
                
            }
});
}

function rmsection(id){
    strand="";
    if($("#strand")){
        strand =$("#strand").val();
    }
      $.ajax({
            type: "GET", 
            url: "/rmsection/" + id  , 
            success:function(data){
                if(data == "true"){
                    getstudentlist(strand);
                    getsectionlist();
                }
                
            }
});
}

function updateadviser(value, id){
    $.ajax({
       type: "GET",
       url: "/updateadviser/" + id + "/" + value,
       success:function(data){
           
       }
    });
}
</script>
@stop



