@extends('app')
@section('content')
<div class='col-md-12'>
    <span id="quarters">
        <a class="btn btn-default quarter btn-primary" id="1st" onclick="changequarter(1)">1st Quarter</a><a class="btn btn-default quarter" id="2nd" onclick="changequarter(2)">2nd Quarter</a><a class="btn btn-default quarter" id="3rd" onclick="changequarter(3)">3rd Quarter</a><a class="btn btn-default quarter" id="4th" onclick="changequarter(4)">4th Quarter</a>
    </span>
    <select id='level' class='form-control'  onchange="viewlist(this.value)">
        <option value="null">-- Select Level --</option>
        @foreach($levels as $level)
        <option value='{{$level->level}}'>{{$level->level}}</option>
        @endforeach
    </select>
    <div id="strands" style="visibility: hidden">
        <select id='level' class='form-control'  onchange="setStrand(this.value)">
            <option value="null">-- Select Strand --</option>
            <option value="ABM">ABM</option>
            <option value="STEM">STEM</option>

        </select>
    </div>    
    <div id="rerank" style="visibility: hidden">
        <button onclick = "setRank()">Set Ranking</button>
    </div>
    <div class="col-md-12" id="main_content">
        
    </div>
    <div id="blocker" style="position:fixed;top:0px;left:0px;width:100%;height:100%;background-color: rgba(181, 181, 181, 0.46);vertical-align: middle;text-align:center;line-height: 100%;display: none">
        
    </div>

</div>
<script>
    var level = "";
    var strand = "";
    var qtr = 1;
    var qtrstring = "FIRST";
    
    $("#quarters").on("click", "a.quarter", function(){
            $(this).siblings().removeClass('btn-primary');
            $(this).addClass('btn-primary');
            $(this).blur();
        });

    function changequarter(setQuarter){
        qtr=setQuarter;
        viewlist(document.getElementById('level').value);
    }
    function viewlist(lvl){
        $('#main_content').html("");
        document.getElementById('rerank').style.visibility='hidden';
        document.getElementById('strands').style.visibility='hidden';
        level = lvl
        

         
        if(lvl == "Grade 11" | lvl == "Grade 12"){
            document.getElementById('strands').style.visibility='visible';
        }else{
            if(lvl != "null"){
                 document.getElementById('rerank').style.visibility='visible'    
             }            
            viewranking(lvl);
        }
    }
    
    function setStrand(strnd){
        if(strnd != "null"){
             document.getElementById('rerank').style.visibility='visible'    
         }
        strand = strnd;
        viewranking(level)
        
    }
    
    function viewranking(lvl){
        var arrays ={} ;
        arrays['strand'] = strand;
        arrays['quarter'] = qtr;

         $.ajax({
             type:"GET",
             url:"showallrank/"+lvl,
             data:arrays,
             success:function(data){
                if(lvl != "null"){
                 $('#main_content').html(data);
                }
             }
             
         });

    }
    
    function setRank(){
        document.getElementById('blocker').style.display='block';
        var arrays ={} ;
        arrays['level'] = level;
        arrays['quarter'] = qtr;
        arrays['strand'] = strand;


            $.ajax({
                type: "GET", 
                url: "/setallrank",
                data : arrays,
                success:function(data){
                    document.getElementById('blocker').style.display='none';
                    viewranking(level)
                    }
                }); 
    }    
</script>
@stop