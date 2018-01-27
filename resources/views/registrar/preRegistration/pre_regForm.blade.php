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
<div class='col-md-8'>
    <form method="POST" action="{{url('studentinfokto12')}}">
        
        <button type="button" class="btn btn-info col-md-12" data-toggle="collapse" data-target="#demo"><strong>STUDENT DATA</strong></button>
         <div id="demo" class="collapse" >
            <table class='table' border="0" cellspacing="10px" cellpadding="10" width='100%'>
                <tr>
                    <td colspan="4">
                        <label>STUDENT NAME: (Please fill up with the complete name as it appears in the birth certificate)</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter your Family Name" />
                        <p style="text-align: center">(PRINT) FAMILY NAME</p>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter your First Name" />
                        <p style="text-align: center">(PRINT) GIVEN NAME</p>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Enter your Middle Name" />
                        <p style="text-align: center">(PRINT) MIDDLE NAME</p>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="extensionname" id="extensionname" placeholder="Enter extension name" />
                        <p style="text-align: center">(PRINT) EXTENSION NAME</p>
                    </td>
                </tr>

             </table>
             <table width='100%'>
                 <tr>
                     <td><label>Date of Birth:</label></td>
                     <td><input type="text" name="birthDate" id="birthDate" class="form-control datepicker" placeholder="Date of Birth"/></td>
                     <td><label for="age">Age:</label></td>
                     <td><input type="text" class="form-control" name="age" id="age" placeholder="Enter your age" ></td>
                        <td style="width:40px"></td>
                     <td><label>Gender:</label></td>
                     <td>
                         <select class="form-control" name="gender" id="gender">
                            <option value="MALE" selected>MALE</option>
                            <option value="FEMALE">FEMALE</option>
                         </select>
                     </td>
                     <td><label>Civil Status </label> </td>
                     <td>
                         <select class="form-control" name="status" id="status">
                            <option value="SINGLE" selected>SINGLE</option>
                            <option value="MARRIED">MARRIED</option>
                            <option value="DIVORCED">DIVORCED</option>
                            <option value="DECEASED">DECEASED</option>
                            <option value="WWIDOWED">WIDOWED</option>
                            <option value="ANNULLED">ANNULLED</option>
                            <option value="SEPARATED">SEPARATED</option>
                         </select>
                     </td>
                 </tr>
                 <tr>
                     <td><label>Place of Birth:</label></td>
                     <td colspan="3"><input type="text" class="form-control" name="birthPlace" id="birthPlace" placeholder="Place of Birth"></td>
                     <td></td>
                     <td><label>Religion:</label></td>
                     <td colspan="3"><input type="text" class="form-control" name="religion" id="religion" placeholder="Enter Religion"></td>
                 </tr>
                 <tr>
                     <td><label>Citizenship:</label></td>
                     <td colspan="3"><input type="text" class="form-control" name="citizenship" id="citizenship" placeholder="Enter Nationality"></td>
                     <td></td>
                     <td><label>ACR Number:</label></td>
                     <td colspan="3"><input type="text" class="form-control" name="acr" id="acr" placeholder="Enter ACR"></td>
                 </tr>
                 <tr>
                     <td colspan="5"></td>
                     <td><label>Visa Type:</label></td>
                     <td colspan="3"><input type="text" class="form-control" name="visaType" id="visaType" placeholder="Enter Type of Visa"><br></td>
                 </tr>
                 <tr>
                     <td colspan="5"><b>CITY ADDRESS</b></td>
                     <td colspan="4"><b>PROVINCIAL ADDRESS</b></td>
                 </tr>
                 <tr>
                     <td width="130px"><label>House No./Street:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="address1" id="address1"  placeholder="Enter House No. / Street">
                     </td>
                     <td></td>
                     <td width="130px"><label>House No./Street:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="address6" id="address6"  placeholder="Enter House No. / Street">
                     </td>
                 </tr>
                 <tr>
                     <td><label>Vil./Subd./Brgy:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="address2" id="address2"  placeholder="Enter Vil. / Subdiv. / Brgy.">
                     </td>
                     <td></td>
                     <td><label>Vil./Subd./Brgy:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="address7" id="address7" placeholder="Enter Vil. / Subdiv. / Brgy.">
                     </td>

                 </tr>
                 <tr>
                     <td><label>District:</label></td>
                     <td>
                         <input type="text" class="form-control" name="address5" id="address5"  placeholder="Enter District">
                     </td>

                     <td><label>City/Municipality:</label></td>
                     <td>
                         <input type="text" class="form-control" name="address3" id="address3" placeholder="Enter city municipality">
                     </td>
                     <td></td>
                     <td><label>City/Municipality:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="address8" id="address8" placeholder="Enter city municipality">
                     </td>             
                 </tr>
                 <tr>
                     <td><label>Region:</label></td>
                     <td>
                         <input type="text" class="form-control" name="address4" id="address4" placeholder="Enter region">
                     </td>
                     <td><label>Zip Code:</label></td>
                     <td>
                         <input type="text" class="form-control" name="zipcode" id="zipcode"  placeholder="Enter zipcode">
                     </td>
                     <td></td>
                     <td>Province:</td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="address9" id="address9"  placeholder="Enter province">
                     </td>
                 </tr>
                 <tr>
                     <td colspan="9"><br><br></td>
                 </tr>

                 <tr>
                     <td><label>Email:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="email" id="email" placeholder="Enter e-mail">
                     </td>
                     <td></td>
                     <td style="width: 152px;"><label>School Last Attended</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="lastattended" id="lastattended" placeholder="Enter school last attended">
                     </td>
                 </tr>
                 <tr>
                     <td><label>Landline No.:</label></td>
                     <td>
                         <input type="text" class="form-control" name="phone1" id="phone1" placeholder="Enter landline number">
                     </td>
                     <td><label>Mobile No.:</label></td>
                     <td>
                         <input type="text" class="form-control" name="phone2" id="phone2" placeholder="Enter mobile number">
                     </td>
                     <td></td>
                     <td><label>Grade/Year:</label></td>
                     <td>
                         <input type="text" class="form-control" name="lastlevel" id="lastlevel" placeholder="Enter grade year">
                     </td>
                     <td width="96px"><label>School Year:</label></td>
                     <td>
                         <input type="text" class="form-control"  name="lastyear" id="lastyear" placeholder="Enter school year">
                     </td>             
                 </tr>
                 <tr>
                     <td><label>No. Of Children:</label></td>
                     <td>
                         <div class="form-inline">
                             <div class="form-group">
                                 <input type="text" class="form-control" name="countboys" id="noofstudentboys" style="width:74%" placeholder="Enter No. Boys">
                                 <label>boys</label>
                            </div>
                         </div>
                     </td>
                     <td></td>
                     <td>
                         <div class="form-inline">
                                 <div class="form-group">                 
                         <input type="text" class="form-control" name="countgirls" id="noofstudentgirls" style="width:75%" placeholder="Enter No. Girls">
                   <label>girls</label>
                                 </div>
                         </div>
                     </td>
                     <td></td>
                     <td><label>LRN:</label></td>
                     <td colspan="3">
                         <input type="text" class="form-control" name="lrn"  id="lrn" placeholder="Enter LRN No.">
                     </td>
                 </tr>
                 <tr>
                     <td colspan="4"><sup>(INCLUDING THIS STUDENT)</sup></td>
                        <td></td>
                        <td><label>ESC Grantee:</label></td>
                        <td>
                            <input type="checkbox" class="form-check-input form-control" value="1" name="esc" id="esc">
                        </td>
                        <td>ESC No.</td>
                        <td>
                            <input type="text" class="form-control" name="escNo" id="escNo" placeholder="Enter ESC No.">
                        </td>
                 </tr>

             </table>
         </div>

        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo4"><strong>PARENTS DATA</strong></button>
         <div id="demo4" class="collapse">

             <table width="1138px">
                 <tbody>
                     <tr>
                         <td colspan="4"><h5><b>FATHER</b></h5></td>
                         <td></td>
                         <td colspan="4"><h5><b>MOTHER</b></h5></td>
                     </tr>
                     <tr>
                         <td><label>Name:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter name ">
                         </td>
                         <td></td>
                         <td><label>Name:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mname" id="mname" placeholder="Enter name ">
                         </td>
                     </tr>
                     <tr>
                         <td colspan="3"><label>Are you a DBTI-Makati Alumnus?</label></td>
                         <td>
                             <select class="form-control" name="falumnus" id="falumnus">
                              <option value="1" selected>YES</option>
                              <option value="0">NO</option>
                             </select>
                         </td>
                         <td colspan="5"></td>
                     </tr>
                     <tr>
                         <td></td>
                         <td>Year Graduated</td>
                         <td colspan="2">
                             <input type="text" class="form-control" name="fyeargraduated" id="fyeargraduated" placeholder="Enter year"/>
                         </td>
                         <td colspan="5">
                     </tr>
                     <tr>
                         <td width="101px"><label>Date of Birth:</label></td>
                         <td>
                             <input type="text" name="fbirthdate" id="fbirthdate" class="form-control datepicker" placeholder="Date of Birth"/>
                         </td>
                         <td width="92px"><label>Civil Status:</label></td>
                         <td>
                            <select class="form-control" name="fstatus" id="fstatus">
                              <option value="SINGLE" selected>SINGLE</option>
                              <option value="MARRIED">MARRIED</option>
                              <option value="DIVORCED">DIVORCED</option>
                              <option value="DECEASED">DECEASED</option>
                              <option value="WIDOWED">WIDOWED</option>
                              <option value="ANNULLED">ANNULLED</option>
                              <option value="SEPARATED">SEPARATED</option>
                            </select>                     
                         </td>
                         <td width="40px"></td>
                         <td width="101px"><label>Date of Birth:</label></td>
                         <td>
                             <input type="text" name="mbirthdate" id="mbirthdate" class="form-control datepicker" placeholder="Date of Birth"/>
                         </td>
                         <td width="92px"><label>Civil Status:</label></td>
                         <td>
                            <select class="form-control" name="mstatus" id="mstatus">
                              <option value="SINGLE" selected>SINGLE</option>
                              <option value="MARRIED">MARRIED</option>
                              <option value="DIVORCED">DIVORCED</option>
                              <option value="DECEASED">DECEASED</option>
                              <option value="WIDOWED">WIDOWED</option>
                              <option value="ANNULLED">ANNULLED</option>
                              <option value="SEPARATED">SEPARATED</option>
                            </select>                     
                         </td>                 
                     </tr>
                     <tr>
                         <td><label>Religion:</label></td>
                         <td>
                             <input type="text" class="form-control" name="freligion" id="freligion" placeholder="Enter Religion">
                         </td>
                         <td><label>Nationality:</label></td>
                         <td>
                             <input type="text" class="form-control" name="fnationality" id="mnationality"  placeholder="Enter Nationality">
                         </td>
                         <td></td>
                         <td><label>Religion:</label></td>
                         <td>
                             <input type="text" class="form-control" name="mreligion" id="mreligion" placeholder="Enter Religion">
                         </td>
                         <td><label>Nationality:</label></td>
                         <td>
                             <input type="text" class="form-control" name="mnationality" id="mnationality" placeholder="Enter Nationality">
                         </td>
                     </tr>
                     <tr>
                         <td><label>Mobile No.:</label></td>
                         <td>
                             <input type="text" class="form-control" name="fmobile" id="fmobile" placeholder="Enter Mobile No">
                         </td>
                         <td><label>Landline:</label></td>
                         <td>
                             <input type="text" class="form-control" name="flandline" id="flandline" placeholder="Enter Landline No">
                         </td>                 
                         <td></td>
                         <td><label>Mobile No.:</label></td>
                         <td>
                             <input type="text" class="form-control" name="mmobile" id="mmobile" placeholder="Enter Mobile No">
                         </td>
                         <td><label>Landline:</label></td>
                         <td>
                             <input type="text" class="form-control" name="mlandline" id="mlandline" placeholder="Enter Landline No">
                         </td>                 

                     </tr>
                     <tr>
                         <td colspan="4"><label>What course did you take up in college?</label></td>
                         <td></td>
                         <td colspan="4"><label>What course did you take up in college?</label></td>
                     </tr>
                     <tr>
                         <td colspan="4">
                             <input type="text" class="form-control" name="fcourse" id="fcourse" placeholder="Enter year">
                         </td>
                         <td></td>
                         <td colspan="4">
                             <input type="text" class="form-control" name="mcourse" id="mcourse" placeholder="Enter year">
                         </td>
                     </tr>
                     <tr>
                         <td>
                             <br>
                             <br>
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4">
                             <h5><b>Occupation</b></h5>
                         </td>
                         <td></td>
                         <td colspan="4">
                             <h5><b>Occupation</b></h5>
                         </td>
                     </tr>
                     <tr>
                         <td colspan="3"><label>Are you self-employed</label></td>
                         <td>
                             <select class="form-control" name="fselfemployed" id="fselfemployed">
                              <option value="1" selected>YES</option>
                              <option value="0">NO</option>
                             </select>
                         </td>
                         <td></td>
                         <td colspan="3"><label>Are you self-employed</label></td>
                         <td>
                             <select class="form-control" name="mselfemployed" id="mselfemployed">
                                <option value="1" selected>YES</option>
                                <option value="0">NO</option>
                             </select>
                         </td>
                     </tr>
                     <tr>
                         <td><label>Full-time:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="fFulljob" id="fFulljob" placeholder="Enter full time ">
                         </td>
                         <td></td>
                         <td><label>Full-time:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mFulljob" id="mFulljob" placeholder="Enter full time ">
                         </td>
                     </tr>
                     <tr>
                         <td><label>Part-time:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="fPartjob" id="fPartjob" placeholder="Enter part time ">
                         </td>
                         <td></td>
                         <td><label>Part-time:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mPartjob" id="mPartjob" placeholder="Enter part time ">
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4"><label>Position:(Main source of income)</label></td>
                         <td></td>
                         <td colspan="4"><label>Position:(Main source of income)</label></td>
                     </tr>
                     <tr>
                         <td></td>
                         <td colspan="2">
                             <select class="form-control" name="fposition" id="fposition">
                                <option value="NONE">--NONE--</option> 
                                <option value="TOP MANAGEMENT">TOP MANAGEMENT</option>
                                <option value="MIDDLE MANAGEMENT">MIDDLE MANAGEMENT</option>
                                <option value="SUPERVISORY">SUPERVISORY</option>
                                <option value="RANK & FILE">RANK & FILE</option>
                            </select>
                         </td>
                         <td colspan="3"></td>
                         <td colspan="2">
                             <select class="form-control" name="mposition" id="mposition">
                                <option value="NONE">--NONE--</option>
                                <option value="TOP MANAGEMENT">TOP MANAGEMENT</option>
                                <option value="MIDDLE MANAGEMENT">MIDDLE MANAGEMENT</option>
                                <option value="SUPERVISORY">SUPERVISORY</option>
                                <option value="RANK & FILE">RANK & FILE</option>
                             </select>
                         </td>
                         <td></td>
                     </tr>
                     <tr>
                         <td width="123px"><label>Monthly Income:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="fincome" id="fincome" placeholder="Enter course in college">
                         </td>
                         <td></td>
                         <td width="123px"><label>Monthly Income:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mincome" id="mincome" placeholder="Enter course in college">
                         </td>
                     </tr>
                     <tr>
                         <td><label>Company Name:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="fcompany" id="fcompany" placeholder="Enter company name">
                         </td>
                         <td></td>
                         <td><label>Company Name:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mcompany" id="mcompany" placeholder="Enter company name">
                         </td>
                     </tr>
                     <tr>
                         <td colspan="4"><label>Company Address:</label></td>
                         <td></td>
                         <td colspan="4"><label>Company Address:</label></td>
                     </tr>
                     <tr>
                         <td colspan="4">
                             <input typ  e="text" class="form-control" name="fComAdd" id="fComAdd" placeholder="Enter company address">
                         </td>
                         <td></td>
                         <td colspan="4">
                             <input typ  e="text" class="form-control" name="mComAdd" id="mComAdd" placeholder="Enter company address">
                         </td>
                     </tr>
                     <tr>
                         <td><label>Office Tel. No.:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="fOfficePhone" id="fOfficePhone" placeholder="Enter office tel. no.">
                         </td>
                         <td></td>
                         <td><label>Office Tel. No.:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mOfficePhone" id="mOfficePhone" placeholder="Enter office tel. no.">
                         </td>
                     </tr>
                     <tr>
                         <td><label>Office Fax No.:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="ffax" id="ffax" placeholder="Enter office fax no.">
                         </td>
                         <td></td>
                         <td><label>Office Fax No.:</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="mfax" id="mfax" placeholder="Enter office fax no.">
                         </td>
                     </tr>
                     <tr>
                         <td><label>Email Address</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="femail" id="femail" placeholder="Enter email">
                         </td>
                         <td></td>
                         <td><label>Email Address</label></td>
                         <td colspan="3">
                             <input type="text" class="form-control" name="memail" id="memail" placeholder="Enter email">
                         </td>
                     </tr>
                 </tbody>
             </table>
        </div>    


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
@stop