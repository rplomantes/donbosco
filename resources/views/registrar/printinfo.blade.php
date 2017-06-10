@extends('app')
@section('content')

<div class="container_fluid">
    <div class="col-md-6">
       
        <div class="form form-group col-md-4">
        <label>Level</label>
        <select id = "level" name="level" class="form form-control">
            <option>--Select--</option>
            @foreach($levels as $level)
            <option value="{{$level->level}}">{{$level->level}}</option>
            @endforeach
        </select>    
    </div>    
        <div class="form form-group col-md-4">
            <div id="section_strand">
            </div>    
        </div>    
        <div class="form form-group col-md-4">
            <div id="section_section">
            </div>    
        </div>  
        <div class="form form-group col-md-12">
            <div id="mybutton">
                <a href="#" onclick="showsoa()" class="btn btn-primary">Show SOA</a>
            </div>    
        </div> 
</div>
    
    <div class="col-md-6">
    <div id="soasummary">
            
    </div>
    </div>    
    
</div>      

<script>
    $('#level').change(function(){
        $.ajax({
            type: "GET", 
            url: "/getsection1/" + $('#level').val(), 
            success:function(data){
                $('#section').html("");
                $('#soasummary').html("");
                $('#section_strand').html(data);
                }                
            }); 
    });
    
 function getsectiontrack(strand){
     $.ajax({
            type: "GET", 
            url: "/getsectionstrand/" + $('#level').val() + "/" + strand, 
            success:function(data){
                $('#soasummary').html("");
                $('#section_section').html(data);
                }                
            }); 
 }
 
 
 function showsoa(){
     var level  = $("#level").val();
     var trandate = $("#year").val() + "-" + $("#month").val() + "-" + $("#day").val();
     var strand="none";
     
   
     if(document.getElementById('strand')){
      strand = $("#strand").val();
     }
     
     var section = $('#section').val();
     document.location = "/getsoasummary/" + level + "/" + strand + "/" + section + "/" + trandate;
       /* 
     $.ajax({
            type: "GET", 
            url: "/getsoasummary/" + level + "/" + strand + "/" + section + "/" + trandate, 
            success:function(data){
                $('#soasummary').html(data);
                }                
            }); 
            */
 }
    
</script>    



@stop

