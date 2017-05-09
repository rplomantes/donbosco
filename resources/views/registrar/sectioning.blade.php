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
</div>
<div class="container">
    <div class="col-md-6">
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
    var strand = "";
    var section = "";
    
    $("#sy").change(function(){
        document.location = "/kto12sectioning/" + $("#sy").val();
    });
    
    $("#level").change(function(){
        level = $('#level').val();
        $("#studentlist").html("");
        $('#strand').html("")
        if(level == "Grade 9" || level == "Grade 10" || level == "Grade 11" || level == "Grade 12"){
            getcourse();
        }else{
            getstudentlist();
            getsection()
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
</script>
@stop



