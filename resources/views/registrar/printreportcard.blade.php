@extends("app")
@section("content")

<div class="container">
    <div class="col-md-12">
        <h3>Report Card Printing</h3>
    </div>    
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
        <div id="quarters" class="form form-group" style="display:none">
            <label for ="quarter">Select Quarter</label>
            <select name="quarter" id="quarter" class="form form-control" onchange="kbutton()">
                <option>--Select--</option>
                <option value="1">1st Quarter</option>
                <option value="2">2nd Quarter</option>
                <option value="3">3rd Quarter</option>
                <option value="4">4th Quarter</option>
            </select>
         </div>
        
        <div id="semester" class="form form-group" style="visibility:hidden">
            <label for ="sem">Select Semester</label>
            <select name="sem" id="sem" class="form form-control" onchange="kbutton()">
                <option>--Select--</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            </select>
         </div>        
    </div> 
    <div class="col-md-6">
        <div class="form form-group">
            <div id="sectioncontrol">
            </div>
            <div id="sectionlist" class="col-md-3">
                
            </div>
            <div class="col-md-3">
                <a href="#" onclick = "displaycards()" id="btncard" class="btn btn-warning" style="display:none">Display Cards</a>
            </div>    
        </div>    
    </div>    
</div>

<script>
    
$('#level').change(function(){
        document.getElementById('quarters').style.display='none'
        document.getElementById('semester').style.visibility='hidden'
    
if($('#level').val() == "Grade 9" || $('#level').val() == "Grade 10" || $('#level').val() == "Grade 11" ){
     $("#studentlist").html("");
     $("#sectioncontrol").html("");
     $("#sectionlist").html("")
     getstrand()
        }else{
     $("#stranddisplay").html("");
     //getstudentlist("");
     getsection("");
 }
});
function displaycards(){
    var level = document.getElementById('level').value
    var section = document.getElementById('section').value
    var quarter = document.getElementById('quarter').value    
    var sem = document.getElementById('sem').value    
    var strand
    if(document.getElementById('strand')){
    strand = document.getElementById('strand').value
    }
    if(level == "Grade 9" | level == "Grade 10"){
     
     document.location = "/reportcards/" + level + "/" + strand + "/" + section;
     
    }else if(level == "Grade 11" | level == "Grade 12"){
        
        document.location = "/reportcards/" + level + "/" + strand + "/" + section +"/"+sem;  
    }else if(level == "Kindergarten"){
        
        document.location = "/reportcard/" + level + "/" + section +"/"+quarter;
    }else{
        
            document.location = "/reportcards/" + level + "/" + section;
        
    }
}
function getstrandall(strand){
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
function kbutton(){
    document.getElementById('btncard').style.display='block'
}
function callsection(){
    getsectionlist()
}

function getsection(strand){
    var array = {};
    array['strand'] = strand;
       $.ajax({
            type: "GET",
            data: array,
            url: "/getsectioncon/" + $('#level').val() , 
            success:function(data){
                $("#sectioncontrol").html(data);
                getsectionlist();
            }
});

}


function getsectionlist(){

        var level = document.getElementById('level').value

        if(level == "Grade 11" || level == "Grade 12"){
            document.getElementById('semester').style.visibility='visible'
        }    
        
        if(level == "Kindergarten"){
            document.getElementById('quarters').style.display='block'
        }
        else{
        document.getElementById('btncard').style.display='block'
    }
}

</script>
@stop

