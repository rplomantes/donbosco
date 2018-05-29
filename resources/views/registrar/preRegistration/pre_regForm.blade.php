@extends('app')
@section('content')
<style>
            .form-control {
                background: none!important;
                border: none!important;
                border-bottom: 1px solid!important;
                box-shadow: none!important;
                border-radius: 0px!important;
                padding-bottom: 0px!important;
            }
</style>
<div class='col-md-9'>
    <form method="POST" action="{{url('studentinfokto12')}}">
        @include('registrar.preRegistration.information.studentInfo')
        @include('registrar.preRegistration.information.parentInfo')


        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo5"><strong>RESIDENCE AND TRANSPORTATION DATA</strong></button>
         <div id="demo5" class="collapse">
             <table width="1138px">
                 <tbody>
                     <tr>
                         <td colspan="2"><label>Residence Type: </label> </td>
                         <td>
                             <select class="form-control" name="residence" id="residence">
                                <option value="HOUSE" selected>HOUSE</option>
                                <option value="APARTMENT">APARTMENT</option>
                                <option value="CONDOMINIUM">CONDOMINIUM</option>
                                <option value="TOWNHOUSE">TOWNHOUSE</option>
                             </select> 
                         </td>
                     </tr>
                     <tr>
                         <td colspan="2"><label>Ownership of Residence:</label></td>
                         <td >
                             <select class="form-control" name="ownership" id="ownership">
                                <option value="OWN" selected>OWN</option>
                                <option value="RENTED">RENTED</option>
                                <option value="WITH PARENTS">LIVING WITH PARENTS</option>
                             </select>
                         </td>
                     </tr>
                     <tr>
                         <td colspan="2"><label>Number of Household Helper(s):</label></td>
                         <td>
                             <input type="text" class="form-control" name="numHouseHelp" id="numHouseHelp" placeholder="Enter number"
                             >
                         </td>
                     </tr>
                     <tr>
                         <td><label>Means of Transportation</label></td>
                         <td>
                             <select class="form-control" name="transportation" id="transportation">
                                <option value="COMMUTE" selected>COMMUTE</option>
                                <option value="SCHOOL BUS">SCHOOL BUS</option>
                                <option value="OWN">OWN VEHICLE</option>
                             </select>
                         </td>
                         <td><label>How many?</label></td>
                         <td>
                             <input type="text" class="form-control" name="carcount" placeholder="Enter number">
                         </td>
                     </tr>
                     <tr>
                         <td colspan="2"><label>Do you have any computer at home?</label></td>
                         <td>
                             <select  class="form-control" name="haveComputer" id="haveComputer">
                                <option value="1" selected>YES</option>
                                <option value="0">NO</option>
                             </select>
                         </td>
                     </tr>
                     <tr>
                         <td colspan="2"><label>Do you have an internet connection at home?</label></td>
                         <td>
                             <select  class="form-control" name="haveInternet" id="haveInternet">
                                <option value="1" selected>YES</option>
                                <option value="0">NO</option>
                             </select>
                         </td>
                     </tr>
                     <tr>
                         <td colspan="2">If yes, what type of internet connection:</td>
                         <td>
                             <select  class="form-control" name="internetType" id="internetType">
                                <option value="DSL" selected>DSL</option>
                                <option value="WIRELESS">WIRELESS</option>
                                <option value="DIAL-UP">DIAL-UP</option>
                                <option value="OTHERS">OTHERS</option>        
                             </select>
                         </td>
                     </tr>
                 </tbody>
             </table>

        </div>
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo6 "><strong>SIBLING DATA</strong></button>
        <div class="collapse" id="demo6">
          <table width="1138px">
              <thead>
              <th class="col-sm-2">Name of student according to age (eldest to youngest)</th>
              <th class="col-sm-2">Birthday</th>
              <th class="col-sm-2">Gender</th>
              <th class="col-sm-2">Civil Status</th>
              <th class="col-sm-1">Working</th>
              <th class="col-sm-1">Studying</th>
              <th class="col-sm-1" style="text-align: center">DBTI Student</th>
              <th class="col-sm-2">Where</th>
              </thead>
            <tbody>
        <?php 
        $numberofrow = 10;
        for($counter = 1;$counter<=$numberofrow;$counter++){ ?>


            <tr>       
            <td> 
                <input type="text" class="form-control" name="sibling<?php echo $counter;?>" placeholder="Enter name" >
            </td>
            <td> 
                <input type="text" name="siblingbday<?php echo $counter;?>" class="form-control datepicker"/>
            </td>
            <td> 
              <select class="form-control" name="siblinggender<?php echo $counter;?>" id="siblinggender<?php echo $counter;?>">
                <option selected>MALE</option>
                <option>FEMALE</option>
              </select>
            </td>
            <td>
              <select class="form-control" name="siblingstatus<?php echo $counter;?>" id="siblingstatus<?php echo $counter;?>">
                <option value="SINGLE" selected>SINGLE</option>
                <option value="MARRIED">MARRIED</option>
                <option value="DIVORCED">DIVORCED</option>
                <option value="DECEASED">DECEASED</option>
                <option value="WIDOWED">WIDOWED</option>
                <option value="ANNULLED">ANNULLED</option>
                <option value="SEPARATED">SEPARATED</option>
              </select>        
            </td>
            <td>
                <input type="checkbox" class="form-control" value="1" name="working<?php echo $counter;?>" id="working<?php echo $counter;?>">

            </td>
            <td>
                <input type="checkbox" class="form-control" value="1" name="studying<?php echo $counter;?>" id="studying<?php echo $counter;?>">
            </td>
            <td>
                <input type="checkbox" class="form-control" value="1" name="dbti<?php echo $counter;?>" id="dbti<?php echo $counter;?>">
            </td>    
            <td> 
                <input type="text" class="form-control" name="where<?php echo $counter;?>">
            </td>    
            </tr>
            <?php } ?> 
          </tbody>
          </table>    
        </div>

        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo7 "><strong>CONTACT PERSON IN CASE OF EMERGENCY</strong></button>
        <div class="collapse" id="demo7">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <div class="form-group">
                            <div class="col-md-4">
                            <label>NAME:</label>
                            <input type="text" class="form-control" name="guardianname" id="guardianname" placeholder="Name">
                            </div>
                            <div class="col-md-4">
                            <label>CELL NO:</label>
                            <input type="text" class="form-control" name="guardianmobile" id="guardianmobile" placeholder="Contact No.">
                            </div>    
                            <div class="col-md-4">
                            <label>RELATIONSHIP:</label>
                            <input type="text" class="form-control" name="guardianrelationship" id="guardianrelationship" placeholder="Relationship">
                            </div>                        
                            </div>                        
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </form>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label class="control-label col-md-3">Temporary ID</label>
        <input value="">
    </div>
    <h4>Hits of Student Name</h4>
    <div class="row">
        
        <div  id="hits">

        </div>
    </div>

</div>
<script>
    $("#firstname,#lastname").keyup(function(){
        var arrays={};
        arrays['firstname']=$("#firstname").val();
        arrays['lastname']=$("#lastname").val();
        $.ajax({
            type:"GET",
            url:"/studentHits",
            data:arrays,
            success:function(data){
                $("#hits").html(data);
            }
        })
        
    });
</script>
@stop