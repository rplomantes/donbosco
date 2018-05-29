<div class='col-md-12 panel-body'>
<button type="button" class="btn btn-info col-md-12" data-toggle="collapse" data-target="#demo"><strong>STUDENT DATA</strong></button>
 <div id="demo" class="collapse" >
     
    <table class='table' border="0" cellspacing="10px" cellpadding="10" width='100%' id='studentname'>
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
     
     <table border="1" cellspacing="10" cellpadding="10" width='100%' id='basicInfo'>
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
     </table>
     <table border="0" cellspacing="10px" cellpadding="5" width='100%' id='basicInfo'>
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
</div>