<?php
    $schoolyear = \App\CtrSchoolYear::first()->schoolyear;
    $currsy = $schoolyear;
?>

@extends("app")
@section("content")
<div class="container">
    <div class="col-md-3">
        <div class="form form-group">
            <label for ="level">Schoolyear</label>
            <select name="level" id="sy" class="form form-control">
                
                @while($schoolyear >= 2016)
                    <option value="{{$schoolyear}}"
                            @if($schoolyear == $sy)
                            selected = "selected"
                            @endif
                            >{{$schoolyear}}</option>
                    <?php 
                    $schoolyear = $schoolyear - 1;
                    ?>
                @endwhile
            </select>
        </div>
    </div>
    @if($currsy == $sy)
    <div class="col-md-offset-3 col-md-6" id="lock">

    </div>
    @endif
</div>
<div class="container">
    <div class="col-md-3">
        <div class="form form-group">
            <label for ="level">Level</label>
            <select name="level" id="level" class="form form-control" id="level">
                <option>--Select--</option>
                @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div class="form form-group" id="strand">
        </div>
    </div>
    <div class="col-md-3">
        <br>
        @if($currsy == $sy)
        <button id="autosec" class="btn btn-block" onclick="autosection()">Auto Section</button>
        @endif
    </div>
    <div class="col-md-3" id="section">
    </div>
    <div class="col-md-3" id="adviser">
    </div>
</div>
<div class="container">
    <div class="col-md-6" id="studentlist">
    </div>
    <div class="col-md-6" id="sectionlist">
    </div>    
</div>

<script>
    var level = "";
    var strand = 'null';
    var section = "";
    
    @if($currsy == $sy)
        document.getElementById("autosec").disabled = true;
    @endif
    
    $("#sy").change(function(){
        document.location = "/kto12sectioning/" + $("#sy").val();
    });
    
    $("#level").change(function(){
        strand = 'null';
        section = "";
        
        level = $('#level').val();
        $("#studentlist").html("");
        $("#sectionlist").html("");
        $('#strand').html("")
        $('#section').html("")
        $('#adviser').html("")
        
        @if($currsy == $sy)
        document.getElementById("autosec").disabled = true;
        @endif
        
        if(level == "Grade 9" || level == "Grade 10" || level == "Grade 11" || level == "Grade 12"){
            getcourse();
        }else{
            getstudentlist();
            getsection();
            
            document.getElementById("autosec").disabled = false;
            
        }
    });
    
    function getstudentlist(){
        var array={};
        array['strand'] = strand;
        $.ajax({
            type: "GET", 
            data: array,
            url: "/studentslist/" + level + "/" + {{$sy}}, 
            success:function(data){
                $("#studentlist").html(data);
            }
            
        });
    }
    
    function getcourse(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= "{{$sy}}";
        $.ajax({
               type: "GET", 
               url: "/getlevelstrands/updatestrand",
               data : arrays,
               success:function(data){
                   $('#strand').html(data)
                   }
               });
    }
    
    function updatestrand(strnd){
        strand = strnd;
        getstudentlist();
        getsection()
        
        document.getElementById("autosec").disabled = false;
    }
    
    function getsection(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= '{{$sy}}';
        arrays['course']= strand;
        $.ajax({
               type: "GET", 
               url: "/getlevelsections/updatesection",
               data : arrays,
               success:function(data){
                   $('#section').html(data)
                   }
               });
    }
    
    function updatesection(sec){
        section = sec
        sectionlist()
    }
    
    function sectionlist(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= '{{$sy}}';
        arrays['strand']= strand;
        arrays['section']= section;
        $.ajax({
               type: "GET", 
               url: "/getsectionstudents",
               data : arrays,
               success:function(data){
                   $('#sectionlist').html(data)
                   }
               });
    }
    
    @if($currsy == $sy)
    @endif
</script>
@stop



