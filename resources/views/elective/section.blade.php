@extends('app')
@section('content')

<div class="container">
    <div class="col-md-6">
        <label>Level</label>
        <select class="form-control" id="level" name="level" onchange="getstrand(this.value)">
            <option selected="selected" hidden="hidden">--Select--</option>
            @foreach($levels as $level)
            <option value="{{$level->level}}">{{$level->level}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6" id="elective">
    </div>
</div>
<div class="container">
    <div class="col-md-6" id="strand"></div>
    <div class="col-md-3" id="section"></div>
    <div class="col-md-3" id="adviser"></div>
</div>
<div class="container">
    <div class="col-md-6" id="studentlist"></div>
    <div class="col-md-6" id="sectionlist"></div>
</div>
<script>

    
    var level="";
    var strand="";
    var elective="";
    var section="";
    
    function getstrand(lvl){
        strand="";
        elective="";
        
        var array = {};
        array['sy'] = {{$schoolyear}};
        array['level'] = lvl;
        $.ajax({
            type:"GET",
            data:array,
            url: "/getlevelstrands/updatestrand", 
            success:function(data){
                $('#strand').html(data);
                level = lvl;
            }
        });
        
        $.ajax({
            type:"GET",
            data:array,
            url: "/getelectives/getsections", 
            success:function(data){
                $('#elective').html(data);
            }
        });
    }
    
    function getsections(elect){
        elective = elect;
        var array = {};
        array['sy'] = {{$schoolyear}};
        array['level'] = level;
        array['elective'] = elect;
        $.ajax({
            type:"GET",
            data:array,
            url: "/getelectivesection/getsectioninfo", 
            success:function(data){
                $('#section').html(data);
            }
        });
        
    }
    
    function getadviser(){
        var array = {};
        array['section'] = section;
        
        $.ajax({
            type: "GET", 
            data: array,
            url: "/electiveadviser", 
            success:function(data){
                $("#adviser").html(data);
            }
            
        });
    }
    
    function getsectionstudents(){
        var array = {};
        array['section'] = section;
        
        $.ajax({
            type: "GET", 
            data: array,
            url: "/sectionelectivelist", 
            success:function(data){
                $("#sectionlist").html(data);
            }
            
        });
    }
    
    function getstudentlist(){
        var array={};
        array['strand'] = strand;
        $.ajax({
            type: "GET", 
            data: array,
            url: "/strandStudent/" + level, 
            success:function(data){
                $("#studentlist").html(data);
            }
            
        });
    }
        
    <!--Calls Function -->    
    function updatestrand(strnd){
        strand = strnd;
        getstudentlist();
    }
    
    function getsectioninfo(sec){
        section = sec
        getadviser();
        getsectionstudents();
    }
    
    <!--Section Control-->
    function addtosection(idno){
        alert(idno)
        var array={};
        array['idno'] = idno;
        array['sy'] = {{$schoolyear}};
        array['section'] = section;
        $.ajax({
            type: "GET",
            data: array,
            url: "/addtoelesection",
            success:function(data){
            }
        });
        getstudentlist();
        getsectionstudents();
    }
    
    function removetosection(idno){
        var array={};
        array['idno'] = idno;
        array['sy'] = {{$schoolyear}};
        array['section'] = section;
        $.ajax({
            type: "GET",
            data: array,
            url: "/removetoelesection",
            success:function(data){
            }
        });
        getstudentlist();
        getsectionstudents();
    }
</script>
@stop