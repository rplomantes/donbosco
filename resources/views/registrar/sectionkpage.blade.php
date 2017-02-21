@extends("app")
@section("content")

<div class="container">
    <div class="col-md-6">
        <div class="form form-group">
            <label for ="level">Select Level</label>
            <select name="level" id="level" class="form form-control">
                <option>--Select--</option>
                @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
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
$('#level').change(function(){
if($('#level').val() == "Grade 9" || $('#level').val() == "Grade 10" || $('#level').val() == "Grade 11" ){
     $("#studentlist").html("");
     $("#sectioncontrol").html("");
     $("#sectionlist").html("")
     getstrand()
        }else{
     $("#stranddisplay").html("");
     getstudentlist("");
     getsection("");
 }
});

function getstrandall(strand){
    getstudentlist(strand);
    getsection(strand);
}

function getstrand(){
    $.ajax({
            type: "GET", 
            url: "/getstrand/" + $('#level').val() , 
            success:function(data){
                $("#stranddisplay").html(data);
            }
});
}

function callsection(){
    getsectionlist()
}

function getstudentlist(strand){
    var array={};
    array['strand'] = strand;
        $.ajax({
            type: "GET", 
            data: array,
            url: "/getstudentlist/" + $('#level').val() , 
            success:function(data){
                $("#studentlist").html(data);
            }
            
});
       
}

function getsection(strand){
    
    var array = {};
    array['strand'] = strand;
       $.ajax({
            type: "GET",
            data: array,
            url: "/getsection/" + $('#level').val() , 
            success:function(data){
                $("#sectioncontrol").html(data);
                getsectionlist();
            }
});

}


function getsectionlist(){
    
        strand= "";
        if($("#strand").length){
            strand = $("#strand").val();
        }
        var array={};
        array['strand']=strand;
         $.ajax({
            type: "GET", 
            data: array,
            url: "/getsectionlist/" + $('#level').val() + "/" + $('#section').val() , 
            success:function(data){
                $("#sectionlist").html(data);
            }
});
}

function setsection(id){
    strand="";
    if($("#strand").length>0){
        strand=$("#strand").val();
    }
    //alert($("#strand").val());
    $.ajax({
            type: "GET", 
            //url: "/setsection/" + id + "/" + $('#section').val() , 
            url: "/setsection/" + id + "/" + $('#section').val() , 
            success:function(data){
                if(data == "true"){
                    getstudentlist(strand);
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
            //url: "/rmsection/" + id  , 
            url: "/rmsection/" + 0  , 
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



