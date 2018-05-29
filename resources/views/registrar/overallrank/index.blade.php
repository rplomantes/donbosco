@extends('app')
@section('content')
<style>
    #print{
        visibility: hidden;
    }
    
    #semester{
        display: none;
    }
</style>
<div class="container-fluid">
    <div class="col-md-12"><h3>Overall Ranking</h3></div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Schoolyear</label>
            <select class="form-control" id="schoolyear" name="schoolyear" onchange="changeSy(this.value)">
                @for ($i = 2016; $i <= $currSY; $i++)
                    <option value="{{$i}}"
                            @if($i==$selectedSY)
                            selected
                            @endif
                            >{{$i}}</option>
                @endfor            
            </select>
        </div>
        <div class="form-group" id="sort">
            <label>Order By</label>
            <select class="form-control" id="order" name="order" onchange="updatesort(this.value)">
                <option selected="selected" value="name">Name</option>
                <option value="acad">Academic Rank</option>
                <option value="tech">Technical Rank</option>
            </select>
        </div>
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
        <div class="form-group" id="semester">
            <label>Semester</label>
            <select class="form-control" id="sem" name="sem" onchange="updatesemester(this.value)">
                <option selected="selected" hidden="hidden" value="0">--Select--</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            </select>
        </div>
        <div class="form-group" id="subject"></div>
        <div>
            <button id="print" class="col-md-12 btn btn-danger" onclick="printsheetA()">PRINT</button>
        </div>
    </div>
    <div class="col-md-9" id="report" style="overflow-x: scroll">
    </div>
</div>
<script>
    var lvl = "";
    var strand = "null";
    var sem = 0;
    var qtr = 0;
    var sort = "name";
    
    
    function changeSy(schoolyear){
        window.location.href = "/overallRank/"+schoolyear;
    }
    
    function printsheetA(){    
        window.location.href = "/printsheetb/{{$selectedSY}}/"+lvl+"/"+strand+"/"+sec+"/"+sem+"/"+qtr;
    }
   
    function updatelevel(level){
        qtr = 0;
        lvl = level;
        $('#strand').html("");
        $('#subject').html("");
        document.getElementById("sem").value = 0;
        document.getElementById("semester").style.display = "none";
        
        updateRank(qtr);
        
        if((jQuery.inArray( level,["Grade 9","Grade 10","Grade 11","Grade 12"]))>=0){
            getcourse();
        }else{
            updatestrand("null");
        }
    }
    
    function getcourse(){
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= "{{$selectedSY}}";
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
        
        if((jQuery.inArray( lvl,["Grade 11","Grade 12"]))>=0){
            getsemester();
        }else{
            updatesemester(0);
        }
    }
    
    function getsemester(){
        document.getElementById("semester").style.display = "block";
    }
    
    function updatesemester(semester){
        sem = semester;
        
        getQuarter();
    }
    
    function getQuarter(){
        arrays ={} ;
        arrays['level']= lvl;
        $.ajax({
               type: "GET", 
               url: "/getlevelquarter/updateRank",
               data : arrays,
               success:function(data){
                   $('#subject').html(data);
                   }
               });
    }
    
    function getlist(quarter){
        $('#report').html("<div style='text-align:center;margin-left:auto;margin-right:auto;'><i class='fa fa-circle-o-notch fa-spin fa-3x fa-fw'></i><span >Loading report...</span></div>");
        arrays ={} ;
        arrays['level']= lvl;
        arrays['sy']= '{{$selectedSY}}';
        arrays['course']= strand;
        arrays['semester']= sem;
        arrays['quarter']= quarter;
        arrays['sort'] = sort;
        
        $.ajax({
            type:"GET",
            data:arrays,
            url: "/overallRankList",
            success:function(data){
                $('#report').html(data);
                document.getElementById("print").style.visibility = "visible";
            }
        });
    }
    
    function updateRank(quarter){
        $('#report').html("<div style='text-align:center;margin-left:auto;margin-right:auto;'><i class='fa fa-circle-o-notch fa-spin fa-3x fa-fw'></i><span >Calculating Rank...</span></div>");
        arrays ={} ;
        qtr = quarter;
        arrays['level']= lvl;
        arrays['sy']= '{{$selectedSY}}';
        arrays['course']= strand;
        arrays['quarter']= quarter;
        arrays['semester']= sem;
        $.ajax({
               type: "GET", 
               url: "/setoverallrank",
               data : arrays,
               success:function(data){
        
                   getlist(quarter)
                   
                   },
                   error:function(){
                       getlist(quarter)
    }
               });
    }
    
    function updatesort(order){
        sort = order;
        if(qtr != 0){
            getlist(qtr);
        }
    }

</script>
@stop