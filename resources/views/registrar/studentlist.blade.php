@extends('app')

@section('content')

<div class="container">
    <h3 class="no-print">STUDENT CONTACT</h3>
    <div class="col-md-6" class="no-print">
        <div class="form-group no-print">
            <label for="sel1">Select level:</label>
            <select class="form-control" id="level" name="level" onchange = "level(this.value)">
                <option>--- Select Level ---</option>  
                @foreach($lists as $list)    
                <option value="{{$list->level}}">{{$list->level}}</option>
                @endforeach  
            </select>
        </div>   
        <div id="forstrand" class="no-print"> 
        </div>     
        <div id="forsection" class="no-print">
        </div>   
    </div>
  <div class="col-md-6">
  </div>
  <div class="col-md-12">
  <div id = "fordisplay">
  </div>    
</div>


<script>
    var strnd = "";
function level(level){
   
 $.ajax({
     type: "GET",
     url:"studentlist/" + level,
     success:function(data){
          
         $('#forstrand').html("");
         $('#fordisplay').html("");
         $('#forsection').html("");   
         if(level == "Grade 9" || level == "Grade 10" || level=="Grade 11" || level == "Grade 12"){
          $('#forstrand').html(data);   
         } else{
          $('#forsection').html(data);   
         }
     }
     
 });  
    
}
function getstrand(strand){
    
    strnd = strand;
    strands(strand);
}
function strands(strand){
    
    //alert(document.getElementById('level').value);
    $('#forsection').html("");
    $.ajax({
    type: "GET",
     url:"strand/" + strand + "/" + document.getElementById('level').value,
     success:function(data){
         
         $('#forsection').html(data);
         
     }
     
 });  
}

function showstudents(){
    
    $('#fordisplay').html("");
    var arrays ={} ;

    arrays['strand']= strnd;    
    
    $.ajax({
    type: "GET",
     url:"getlist/" + document.getElementById('level').value +"/"+document.getElementById('section').value,
     success:function(data){
         
         $('#fordisplay').html(data);   
         
     }
     
 });  
}
</script>

@endsection
