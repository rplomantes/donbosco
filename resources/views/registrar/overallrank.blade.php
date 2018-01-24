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
    <div class="col-md-2" style="margin-top: 25px;">
        @if(Auth::user()->accesslevel == env('USER_REGISTRAR') && $schoolyear == $sy ||Auth::user()->idno == 'rplomantes')
            <button class="btn btn-danger" onclick="updateRank();">Set Ranking</button>
        @endif
    </div>
    <div class="col-md-7" style="margin-top: 25px;">
        <span id="quarters">
            
        </span>
    </div>
    
</div>
<div class="container">
    <div class="col-md-12">
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
</div>
<div class="container" id="ranklist" style="overflow-x: scroll;">
    
</div>

<script>
    
    
    var level = "";
    var strand = "";
    var quarter = '1';
    var semester = '0';
    
    $("#quarters").on("click", "a.quarter", function(){
        $('a.quarter').removeClass('btn-primary');
        $(this).addClass('btn-primary');
        $(this).blur();
    });
    
    
    
    $("#sy").change(function(){
        document.location = "/overallranking/" + $("#sy").val();
    });
    
    $("#level").change(function(){
	strand = "";
        level = $('#level').val();
        $("#studentlist").html("");
        $('#strand').html("")
        if(level == "Grade 9" || level == "Grade 10"){
            quarter = 1;
            semester =0;
            $('#quarters').html('<a class="btn btn-default quarter btn-primary" id="1st" onclick="changequarter(1,0)">1st Quarter</a><a class="btn btn-default quarter" id="2nd" onclick="changequarter(2,0)">2nd Quarter</a><a class="btn btn-default quarter" id="3rd" onclick="changequarter(3,0)">3rd Quarter</a><a class="btn btn-default quarter" id="4th" onclick="changequarter(4,0)">4th Quarter</a><a class="btn btn-default quarter" id="final" onclick="changequarter(5,0)">Final</a>');
            getcourse();
        }
        else if(level == "Grade 11" || level == "Grade 12"){
            quarter = 1;
            semester =1;
            $('#quarters').html('<div><dl class="dl-horizontal" style="margin-bottom:0px;"><dt>1st Semester</dt><dd><a class="btn btn-default quarter btn-primary" id="1st" onclick="changequarter(1,1)">1st Quarter</a><a class="btn btn-default quarter" id="2nd" onclick="changequarter(2,1)">2nd Quarter</a><a class="btn btn-default quarter" id="final1" onclick="changequarter(5,1)">Final</a></dd></dl></div><br>'+
                                '<div><dl class="dl-horizontal" style="margin-bottom:0px;"><dt>2nd Semester</dt><dd><a class="btn btn-default quarter" id="1st" onclick="changequarter(1,2)">1st Quarter</a><a class="btn btn-default quarter" id="2nd2" onclick="changequarter(2,2)">2nd Quarter</a><a class="btn btn-default quarter" id="final2" onclick="changequarter(5,2)">Final</a></dd></dl></div>');
            getcourse();
            
        }else{
            quarter = 1;
            semester =0;
            $('#quarters').html('<a class="btn btn-default quarter btn-primary" id="1st" onclick="changequarter(1,0)">1st Quarter</a><a class="btn btn-default quarter" id="2nd" onclick="changequarter(2,0)">2nd Quarter</a><a class="btn btn-default quarter" id="3rd" onclick="changequarter(3,0)">3rd Quarter</a><a class="btn btn-default quarter" id="4th" onclick="changequarter(4,0)">4th Quarter</a><a class="btn btn-default quarter" id="final" onclick="changequarter(5,0)">Final</a>');
            getoverallrank();
            
        }
    });    
    
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
    
    function changequarter(setQuarter,sem){
        quarter = setQuarter;
        semester =sem;
        
        getoverallrank();
    }
    
    function updatestrand(strnd){
        strand = strnd;
        getoverallrank();
    }
    
    function getoverallrank(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= '{{$sy}}';
        arrays['course']= strand;
        arrays['quarter']= quarter;
        arrays['semester']= semester;
        $.ajax({
               type: "GET", 
               url: "/getoverallrank",
               data : arrays,
               success:function(data){
                   $('#ranklist').html(data)
                   }
               });
    }
    
    function updateRank(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= '{{$sy}}';
        arrays['course']= strand;
        arrays['quarter']= quarter;
        arrays['semester']= semester;
        $.ajax({
               type: "GET", 
               url: "/setoverallrank",
               data : arrays,
               success:function(data){
                   getoverallrank();
                   }
               });
    }
    
</script>
@stop



