<?php
use App\Http\Controllers\Registrar\Section\SectionStudents;


?>
@extends('app')
@section('content')
<style>
#autosection {
  display: flex;
  margin-top: 40px;
  margin-bottom: 20px;
  align-items: center;
  justify-content: center;
}
.fa-rotate-90 {
    -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    transform: rotate(90deg);
}
.clickable:hover{
    cursor:pointer
}

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-8">
                <div class="form-group" id="levelGroup">
                    <label class="label-control">Level</label>
                    <select class="form-control" id="level">
                        <option value="" hidden="hidden">-- Select Level --</option>
                        @foreach($levels as $levelOpt)
                        <option value="{{$levelOpt->level}}">{{$levelOpt->level}}</option>
                        @endforeach
                    </select>
                </div>
                <div class='form-group' id='strand_cont'>
                </div>

            </div>
            @if($level != null)
            <div class="col-md-4" id="autosection">
                @if($strand != null)
                <a href="{{route('autoSection',array($level,$strand))}}" title="Auto Section" class="btn btn-info" id="autoSec">
                @else    
                <a href="{{route('autoSection',$level)}}" title="Auto Section" class="btn btn-info" id="autoSec">
                @endif
                    <i class="fa fa-sitemap fa-4x fa-rotate-90"></i>
                </a>
            </div>
            @endif
        </div>
        <div class="col-md-6">

            <div class="form-group col-md-6" id="sec_cont">
                <label class="label-control">Section</label>
                <select class="form-control" id="section">
                    <option value="" hidden="hidden">-- Select Section --</option>
                    @foreach($sections as $section)
                    <option value="{{$section->section}}">{{$section->section}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div id="levelList">
            {!!SectionStudents::view_levelList($schoolyear, $level, $strand)!!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="row" id="sectionList_cont"></div>
        </div>
    </div>



</div>
<script>
    
    //Re-initialize any required fields
    var strand = "";
    getStrand('{{$level}}',function(){
        $('#level').val('{{$level}}')
        
    });
    
    $('#level').change(function(){
        var level = $(this).val();
        changeGrade(level)
    })
    
    $('#strand_cont').on('change','#strand',function(){
        var strand = $(this).val()
        var level = $('#level').val()
        changeUrl(level,strand)
        
    });
    
    $('#section').change(function(){
        getSectionList($(this).val())
    });
    
    
    
    
    
    function changeGrade(level){
        $('#strand_cont').html("");
        if( level == 'Grade 9' || level == 'Grade 10' || level == 'Grade 11' || level == 'Grade 12'){
            getStrand(level)
        }else{
            changeUrl(level)
        }
        
        
    }
    
    function getStrand(level,callback){
        if( level == 'Grade 9' || level == 'Grade 10' || level == 'Grade 11' || level == 'Grade 12'){
            var array = {};
            array['sy'] = {{$schoolyear}};
            array['level'] = level;
            $.ajax({
                type:"GET",
                data:array,
                url: "/getlevelstrands/null/0", 
                success:function(data){
                    $('#strand_cont').html(data);
                    if(callback){
                        $('#strand').val('{{$strand}}')
                    }
                }
            });   
        }
        
        if(callback){
            callback();
        }
    }
    
    
    function changeUrl(level,strand=null){
        if(strand != null){
            window.location.href = "/section/kto12/sectioning/"+level+"/"+strand;
        }else{
            window.location.href = "/section/kto12/sectioning/"+level;
        }
        
    }
    
    function getLevelList(){
            
            var arrays = {};
            arrays['sy'] = '{{$schoolyear}}';
            arrays['level'] = '{{$level}}';
            arrays['strand'] = '{{$strand}}';
            
            $.ajax({
                type:"GET",
                data:arrays,
                url: "{{route('ajax_getLevelList')}}", 
                success:function(data){
                    $('#levelList').html(data);
                }
            });
    }
    
    function getSectionList(section){
            
            var arrays = {};
            arrays['sy'] = '{{$schoolyear}}';
            arrays['level'] = '{{$level}}';
            arrays['strand'] = '{{$strand}}';
            arrays['section'] = section;
            
            $.ajax({
                type:"GET",
                data:arrays,
                url: "{{route('ajax_getSectionList')}}", 
                success:function(data){
                    $('#sectionList_cont').html(data);
                }
            });
    }
    
    function addStudent(student){
        var section = $('#section').val();
        if(section != ""){
            setSection(student,section)
        }else{
            alert('You cannot assign on blank section')
        }
        
    }
    
    function removeStudent(student){
        var section = "";
        setSection(student,section)
    }
    
    function setSection(student,section){
        var arrays = {};
        arrays['sy'] = '{{$schoolyear}}';
        arrays['idno'] = student;
        arrays['section'] = section;

        $.ajax({
            type:"GET",
            data:arrays,
            url: "{{route('ajax_setStudentSection')}}", 
            success:function(){
                getSectionList($('#section').val())
                getLevelList()
            },
            error:function(){
                alert('Something went wrong')
            }
        });
    }
    
</script>
@stop