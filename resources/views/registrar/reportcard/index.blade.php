@extends('app')
@section('content')
<style>
    #print{
        visibility: hidden;
    }
    
    #semester,#qtr{
        display: none;
    }
</style>
<div class='container'>
    <div class='col-md-4'>
        <div class="form-group">
            <label>Level</label>
            <select class="form-control" id="level" name="level" onchange="updatelevel(this.value)">
                <option selected="selected" hidden="hidden">--Select--</option>
                @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="strand"></div>
        <div class="form-group" id="section"></div>
        <div class="form-group" id="semester">
            <label>Semester</label>
            <select class="form-control" id="sem" name="sem" onchange="updatesemester(this.value)">
                <option selected="selected" hidden="hidden" value="0">--Select--</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            </select>
        </div>
        <div class="form-group" id="qtr">
            <label>Quarter</label>
            <select class="form-control" id="quarter" name="quarter" onchange="updatequarter(this.value)">
                <option selected="selected" hidden="hidden" value="0">--Select--</option>
                <option value="1">1st Quarter</option>
                <option value="2">2nd Quarter</option>
                <option value="2">3rd Quarter</option>
                <option value="2">4th Quarter</option>
            </select>
        </div>
        <div>
            <button id="print" class="col-md-12 btn btn-danger" onclick="printsheetA()">PRINT</button>
        </div>
    </div>
    <div class='col-md-8' id="report"></div>
</div>
<script>
    var lvl = "";
    var sec = "";
    var strand = "";
    var sem = 0;
    var quarter = 0;
    
    
    function printsheetA(){
        window.location.href = "/printcards/"+lvl+"/"+strand+"/"+sec+"/"+quarter+"/"+sem;
    }
   
    
    function updatelevel(level){
        lvl = level;
        quarter = 0;
        $('#strand').html("");
        $('#section').html("");
        $('#subject').html("");
        document.getElementById("print").style.visibility = "hidden";
        document.getElementById("sem").value = 0;
        document.getElementById("semester").style.display = "none";
        document.getElementById("quarter").value = 0;
        document.getElementById("qtr").style.display = "none";
        if((jQuery.inArray( level,["Grade 9","Grade 10","Grade 11","Grade 12"]))>=0){
            getcourse();
        }else{
            updatestrand("null");
        }
        
        if(level == 'Kindergarten'){
            document.getElementById("qtr").style.display = "block";
        }
    }
    
    function getcourse(){
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= "{{$sy}}";
        $.ajax({
               type: "GET", 
               url: "/getlevelstrands/updatestrand",
               data : arrays,
               success:function(data){
                   $('#strand').html(data);
                   }
               });
    }
    
    function updatestrand(strnd){
        strand = strnd;
        getsection();
    }
    
    function updatequarter(qtr){
        quarter = qtr;
    }
    
    function getsection(){
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= '{{$sy}}';
        arrays['course']= strand;
        $.ajax({
               type: "GET", 
               url: "/getlevelsections/0/updatesection",
               data : arrays,
               success:function(data){
                   $('#section').html(data)
                   }
               });
               
        if((jQuery.inArray( lvl,["Grade 11","Grade 12"]))>=0){
            getsemester();
        }else{
            updatesemester(0);
        }
    }
    function updatesection(section){
        sec = section;
        if((jQuery.inArray( lvl,["Grade 11","Grade 12"]))>=0 && subj == ""){
            
        }else{
            print();
        }
    }
    function getsemester(){
        document.getElementById("semester").style.display = "block";
        print();
    }
    
    function updatesemester(semester){
        sem = semester;   
    }
    
    function print(){
        document.getElementById("print").style.visibility = "visible";
    }

</script>
@stop