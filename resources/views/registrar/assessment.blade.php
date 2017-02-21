@extends('app')
@section('content')
<form onsubmit="return confirm('Continue to assess this student ?');" class="form-horizontal" role="form" method="POST" action="{{ url('/registrar/assessment') }}">
  {!! csrf_field() !!}  
         <div class='container_fluid'>
         <input type="hidden" name="encoded_by" value = "{{Auth::user()->id}}">      
         <div class='col-md-6'>
         
         <div class='col-md-12'>
         <label for="idno">Student ID Number</label> 
         </div>
         <div class='col-md-6'>
         <input type="text" name = "id_number" id="id_number" class="form-control" readonly> 
         <input type="hidden" name = "idno" id="idno"> 
         <input type="hidden" name="action" value="addnew">
         </div>
         <div class='col-md-6'>
         <a class="btn btn-primary" onclick="getid('{{Auth::user()->id}}')">Get ID</a>
         <a class="btn btn-primary" href="{{url('/registrar/show')}}">Clear</a>
         <a class="btn btn-primary" href="{{url('/')}}">Back</a>
          <script src = "{{asset('/js/nephilajs/studentid.js')}}"></script>
         </div>
        
             <div id="credentials" style="visibility: hidden">
         <div class="col-md-3">       
         
         
         <label for="idno">Last Name</label> 
         <input type="text" name = "lastname" id="lastname" class="form-control"> 
         
          
         </div>
         
         <div class="col-md-3">      
         
         
         <label for="idno">First Name</label> 
         <input type="text" name = "firstname" id="firstname" class="form-control"> 
           
         
         </div>    
             
         <div class="col-md-3">      
         
        
         <label for="idno">Middle Name</label> 
         <input type="text" name = "middlename" id="middlename" class="form-control"> 
           
       
         </div>    
             
          <div class="col-md-3">      
        
        
         <label for="idno">Extension Name</label> 
         <input type="text" name = "extensionname" id="extensionname" class="form-control"> 
       
        
         </div>      
             
        
        <div class="col-md-12">Gender</div>
        <div class="col-md-12">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="radio" id="gender" name="gender" value="Male" checked="checked" /> Male
                </label> 
                <label class="btn btn-default">
                    <input type="radio" id="gender" name="gender" value="Female" /> Female
                </label> 
              
           </div>
        </div>
    
           
             <div class="col-md-6"> 
                 <div class="col-md-12">Program</div>
                 <div class="col-md-12">
                     <select name="department" id="department" class="form-control" onchange="getlevel(this.value)">
                     <option value="">Select Program</option>
                     <option value="Kindergarten">Kindergarten</option>
                     <option value="Elementary">Elementary</option>
                     <option value="Junior High School">Junior High School</option>
                     <option value="Senior High School">Senior High School</option>
                     <option value="TVET">TVET</option>
                    </select>    
                  </div>   
                 <script src = "{{url('/js/nephilajs/getlevel.js')}}"></script>
                </div> 
             <!-- last--></div>
               
                <div class="col-md-6">
                 <div id="levelcontainer">
             
                 </div> 
                
                    <div id="coursecontainer">   
                    
                    </div> 
                 </div>
             
            
                  <div class="col-md-6">
                 <div id="trackcontainer">
                 </div> 
             </div>
          <div class="col-md-6">
                 <div id="plancontainer">
                 </div> 
             </div>
        <div class="col-md-12">
                 <div id="discountcontainer">
                 </div> 
             </div>
             </div>  
         
             </div>  
               
      <div class='col-md-6'>
          
          <div id="screendisplay">
            
        </div>
     </div>    
        
    
</div>    
<script src="{{url('/js/nephilajs/getlevel.js')}}"></script>
<script src="{{url('/js/nephilajs/getplan.js')}}"></script>
<script src="{{url('/js/nephilajs/gettrackplan.js')}}"></script>
<script src="{{url('/js/nephilajs/getdiscount.js')}}"></script>
</form>
@stop