@extends('appcashier')
@section('content')
<div class="container-fluid">
     <form method="POST" action="{{url('/getsoasummary')}}">
            {!! csrf_field() !!}
    <div class="col-md-6">
        <h5>Plan</h5>
       
        <div class="form form-group col-md-12">
           <!--
            <input type="checkbox" name="whatplan[]" value="Monthly 1"> Monthly 1
            <input type="checkbox" name="whatplan[]" value="Monthly 2"> Monthly 2
            <input type="checkbox" name="whatplan[]" value="Quartely"> Quarterly
            <input type="checkbox" name="whatplan[]" value="Semi Annual"> Semi Annual
            <input type="checkbox" name="whatplan[]" value="Annual"> Annual-->
          
            <!--select id="plan" name="plan" class="form form-control">
                <option value="monthly1monthly2">Monthly 1 / Monthly 2 </option>
                <option value="Quarterly">Quarterly</option>
                <option value="Semi Annual">Semi Annual</option>
                <option value="Annual">Annual</option>
            </select-->   
            
           <label><input type="checkbox" name="plan[]" value="Monthly 1"> Monthly 1</label><br>
           <label><input type="checkbox" name="plan[]" value="Monthly 2"> Monthly 2</label><br>
           <label><input type="checkbox" name="plan[]" value="Quarterly"> Quarterly</label><br>
           <label><input type="checkbox" name="plan[]" value="Semi Annual"> Semi Annual</label><br>
           <label><input type="checkbox" name="plan[]" value="Annual"> Annual</label><br>
         </div>   
        <h5>Due Date</h5>
        <div class="form form-group col-md-4">
            <label>Month</label>
            <select id="month" name="month" class="form form-control">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="form form-group col-md-4">
            <label>Day</label>
            <select id="day" name="day" class="form form-control">
                <?php
                for($i=1;$i<=31;$i++){
                  echo "<option value = '$i'>$i</option>";
                }
                ?>
            </select>    
        </div>  
        <div class="form form-group col-md-4">
            <label>year</label>
            <select id="year" name="year" class="form form-control">
                <option ="{{$sy}}">{{$sy}}</option>
                <option = "{{$sy+1}}">{{$sy+1}}</option>
            </select>    
        </div>  
      
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
            <div id="overamount">
                <label>Amount Over
                    <input style="text-align: right" type="text" id="amtover" name = "amtover" value="1000" class="form-control">
            </div>    
        </div> 
        <div class="form form-group col-md-12">
            <label>Custom reminder</label>
            <textarea row="4" id="reminder" name="reminder" class="form form-control"></textarea>
         </div>   
        <div class="form form-group col-md-12">
            <div id="mybutton">
             <!--   <input type="button" value="Show SOA" name="submit" class="btn btn-primary">-->
                <input type="submit" class="btn btn-primary" value="Show SOA">
            </div>    
        </div> 
</div>
    
    <div class="col-md-6">
    <div id="soasummary">
            
    </div>
        
    </div>    
    </form>
</div>      

<script>
    $('#level').change(function(){
        
        $.ajax({
            type: "GET", 
            url: "/getsection2/" + $('#level').val(), 
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
     var plan = $("#plan").val();
     var amtover = $("#amtover").val();
     if(amtover == ""){
         amtover = 0;
     }
   
     if(document.getElementById('strand')){
      strand = $("#strand").val();
     }
     
     var section = $('#section').val();
     document.location = "/getsoasummary/" + level + "/" + strand + "/" + section + "/" + trandate + "/" + plan + "/" + amtover;
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
