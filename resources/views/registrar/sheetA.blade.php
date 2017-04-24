@extends('app')
@section('content')
<style>
    #sempos{
        display:none;
    }
</style>
<div class="container">
    <div class="col-md-12">
        <div class="col-md-12">
            <h1>Sheet A Generator</h1>
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-2">
            <label>Schoolyear</label>
            <select class="form-control" id="schoolyear" onchange="updatesy(this.value)">
                @foreach($sys as $sy)
                <option value="{{$sy->schoolyear}}">{{$sy->schoolyear}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-12 form-group">
        <div class="col-md-4">
            <label>Level</label>
            <select class="form-control" id="level" onchange="updatelevel(this.value)">
                @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4" id="sectionspos">
        </div>
        <div class="col-md-4" id="subjectpos">
        </div>
    </div>
    
    <div class="col-md-12 form-group">
        <div class="col-md-4" id="strandpos">
        </div>
        <div class="col-md-4" id="sempos">
            <label>Semester</label>
            <select class="form-control" id="semester"  onchange="updatesem(this.value)">
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            </select>
        </div>
    </div>
    
    <div class="col-md-12 form-group">
        <button class="btn btn-primary col-md-12" onclick="viewreport()">Generate Report</button>
    </div>
    
</div>

<script>
    var sy = {{$currsy->schoolyear}};
    var level = "Kindergarten";
    var course = "";
    var sem = 1;
    var sec = "";
    
    
    function updatesy(schoolyear){
        sy = schoolyear
        $('#sectionspos').html('');
        $('#strandpos').html('');
        $('#subjectpos').html('');
        $('#sempos').hide();
        
        course = "";
        sem = 1;
        sec = "";
        
        if(level == "Grade 9"  || level == "Grade 10" || level == "Grade 11" || level == "Grade 12"){
            getcourse();
        }else{
            getsection();
        }
    }
    
    function updatelevel(lvl){
        level = lvl;
        $('#sectionspos').html('');
        $('#strandpos').html('');
        $('#subjectpos').html('');
        $('#sempos').hide();
        
        course = "";
        sem = 1;
        sec = "";
        
        if(level == "Grade 9"  || level == "Grade 10" || level == "Grade 11" || level == "Grade 12"){
            getcourse();
        }else{
            getsection();
        }
    }
    
    function updatestrand(strand){
        course = strand;
        if(level == "Grade 11" || level == "Grade 12"){
            $('#sempos').show();
        }
        getsection();
    }
    
    function updatesem(semester){
        sem = semester
    }
    
    function updatesection(section){
        sec = section;
        getsubjects();
    }
    
    function getsection(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= sy;
        arrays['course']= course;
        $.ajax({
               type: "GET", 
               url: "/getlevelsections/updatesection",
               data : arrays,
               success:function(data){
                   $('#sectionspos').html(data)
                   }
               });
    }
    
    function getcourse(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= sy;
        $.ajax({
               type: "GET", 
               url: "/getlevelstrands/updatestrand",
               data : arrays,
               success:function(data){
                   $('#strandpos').html(data)
                   }
               });
    }
    
    function getsubjects(){
        arrays ={} ;
        arrays['level']= level;
        arrays['sy']= sy;
        arrays['course']= course;
        $.ajax({
               type: "GET", 
               url: "/getlevelsubjects/showprint",
               data : arrays,
               success:function(data){
                   $('#subjectpos').html(data)
                   }
               });
    }
    
    function  showprint(){
        
    }
    
    function  viewprint(){
        if(level == "Grade 9"  || level == "Grade 10"){
            window.open("{{url('sheetA/grades')}}"+"/"+level+"/"+course+"/"+, '_blank');
        }
        else if(level == "Grade 11" || level == "Grade 12"){
            
        }
    }
</script>
@stop