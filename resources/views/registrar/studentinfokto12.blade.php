<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       <link href="{{ asset('/css/app_1.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/fileinput.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/datepicker.css') }}" rel="stylesheet">

       
        <script src="{{asset('/js/jquery.js')}}"></script>
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('/js/fileinput.js')}}"></script>
        <script src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
        
        
        
        <style type="text/css">
            .form-control {
                background: none!important;
                border: none!important;
                border-bottom: 1px solid!important;
                box-shadow: none!important;
                border-radius: 0px!important;
                padding-bottom: 0px!important;
            }
            button[type='button']{
                width: 100%;
            }
            td{
                border: none!important;
                padding:0px 10px;
            }
            .collapse{
                border: 1px solid;
                border-top: none;
                padding-bottom: 10px
            }
            .error{
                border: 2px solid #cf2020;
                text-align: center;
            }
            button[type="button"]{
                border-bottom-right-radius: 0px;
                border-bottom-left-radius: 0px;
            }
        </style>
<body> 
    <div class="container">
    <table border = '1' cellpacing="0" cellpadding = "0" width="100%" align="center">
        <tr><td rowspan="5" width="65" style="vertical-align: top"><img src="{{asset('/images/logo.png')}}" width="60"></td><td><span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span></td><td>Gr/Batch</td><td>@if($status != NULL){{$status->level}}{{$status->period}} @endif</td></tr>
        <tr><td style="font-size:10pt;">Chino Roces Ave., Makati City </td><td>Section</td><td>@if($status!= NULL){{$status->section}} @endif</td></tr>
        <tr><td style="font-size:10pt;">Tel No : 892-01-01</td><td width="5%">Shop/Course</td><td width="33%">@if($status!= NULL)<div id="currcourse">{{$status->strand}}{{$status->course}}
                @if($status->course != "")
                <button class="btn" onclick="updateCourse()">Change</button>
                @endif</div>
                <div id="changecourse">
                    <form method="POST" action="{{url('/changecourses/'.$status->period.'/'.$student->idno)}}">
                        {!!csrf_field()!!}
                        <?php $courses = \App\CtrSubjects::distinct()->select('course')->where('department','TVET')->get();?>
                        
                        <select name="course" style="width: 60%;">
                            @foreach($courses as $course)
                            <option value="{{$course->course}}" @if($status->course == $course->course)
                                    selected
                                    @endif>{{$course->course}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="#" class="btn btn-danger" onclick="cancelChange()">Cancel</a>
                    </form>
                </div>
                @endif</td></tr>
        <tr><td></td><td>Student No</td><td id="studno">@if($student != NULL){{$student->idno}}@endif</td></tr>
        @if($student != NULL)
            <tr>
                <td></td><td>Status</td>
                <td id="status">
                @if($status!= NULL)
                    @if($status->status == 0)
                        Registered
                    @elseif($status->status == 1)
                   Assessed
                    @elseif($status->status == 2)
                    Enrolled <button class='btn btn-primary' onclick="drop()">Drop Student</button>
                    @elseif($status->status == 3)
                        Dropped
                    @endif
                @else
                    Not Registered
                @endif
            
            </td>
            </tr>
        @endif
    </table>

    @if(count($errors)>0)
    <div class="error">
        @foreach($errors->all() as $error)
         <li>{{$error}}</li>
        @endforeach        
    </div>
    @endif

    
     @if($student != NULL)
     <form method="POST" action="{{url('studentinfokto12/'.$student->idno)}}">
     @else
     <form method="POST" action="{{url('studentinfokto12')}}">
     @endif
    {!! csrf_field() !!}
    <input type="hidden" name="idno" id="idno"
             @if($student != NULL)
             value="{{$student->idno}}"
             @endif
             /> 
    
 <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo"><strong>STUDENT DATA</strong></button>
 <div id="demo" class="collapse" >
     <table border="0" cellspacing="10px" cellpadding="10" width="1138px">
         
             <tr>
                 <td colspan="4">
                     <label>STUDENT NAME: (Please fill up with the complete name as it appears in the birth certificate)</label>
                 </td>
             </tr>
             <tr>
                 <td>
                  <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter your Family Name" 
                         @if($student != NULL)
                         value="{{$student->lastname}}"
                         @endif
                         />
                         <p style="text-align: center">(PRINT) FAMILY NAME</p>
                 </td>
                 <td>
                  <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter your First Name" 
                         @if($student != NULL)
                         value="{{$student->firstname}}"
                         @endif
                         />
                         <p style="text-align: center">(PRINT) GIVEN NAME</p>
                 </td>
                 <td>
                  <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Enter your Middle Name" 
                         @if($student != NULL)
                         value="{{$student->middlename}}"
                         @endif             
                         />
                         <p style="text-align: center">(PRINT) MIDDLE NAME</p>
                 </td>
                 <td>
                  <input type="text" class="form-control" name="extensionname" id="extensionname" placeholder="Enter extension name" 
                         @if($student != NULL)
                         value="{{$student->extensionname}}"
                         @endif             
                         />
                         <p style="text-align: center">(PRINT) EXTENSION NAME</p>

                 </td>
             </tr>
         
     </table>
     <table width="1138px">
         <tr>
             <td><label>Date of Birth:</label></td>
             <td><input type="text" name="birthDate" id="birthDate" class="form-control datepicker" placeholder="Date of Birth" 
               @if($studentInfo != NULL)
               value="{{$studentInfo->birthDate}}"
               @endif             
               />
             </td>
             <td><label for="age">Age:</label></td>
             <td>
                 <input type="text" class="form-control" name="age" id="age" placeholder="Enter your age" >
             </td>
             <td style="width:40px"></td>
             <td width="103px"><label>Gender:</label></td>
             <td>
                 <input type="text" class="form-control" name="gender" id="gender" placeholder="Enter Gender"
                 @if($student != NULL)
                 value="{{$student->gender}}"
                 @endif           
                 >
             </td>
             <td width="90px"><label>Civil Status </label> </td>
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
             <td width="110px"><label>Place of Birth:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="birthPlace" id="birthPlace" placeholder="Place of Birth"
                   @if($studentInfo != NULL)
                    value="{{$studentInfo->birthPlace}}"
                   @endif           
                  >
             </td>
             <td style="width:40px"></td>
             <td><label>Religion:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="religion" id="religion" placeholder="Enter Religion"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->religion}}"
                 @endif           
                 >
             </td>
         </tr>
         <tr>
             <td><label>Citizenship:</label></td>
             <td colspan="3">
                <input type="text" class="form-control" name="citizenship" id="citizenship" placeholder="Enter Nationality"
                @if($studentInfo != NULL)
                value="{{$studentInfo->citizenship}}"
                @endif           
                >
             </td>
             <td style="width:40px;"></td>
             <td><label>ACR Number:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="acr" id="acr" placeholder="Enter ACR"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->acr}}"
                 @endif             
                 >
             </td>
         </tr>
         <tr>
             <td colspan="5"></td>
             <td><label>Visa Type:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="visaType" id="visaType" placeholder="Enter Type of Visa"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->visaType}}"
                 @endif             
                 ><br>
             </td>
         </tr>
         <tr>
             <td colspan="5"><b>CITY ADDRESS</b></td>
             <td colspan="4"><b>PROVINCIAL ADDRESS</b></td>
         </tr>
         <tr>
             <td width="130px"><label>House No./Street:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="address1" id="address1"  placeholder="Enter House No. / Street"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address1}}"
                         @endif             
                       >
             </td>
             <td></td>
             <td width="130px"><label>House No./Street:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="address6" id="address6"  placeholder="Enter House No. / Street"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address6}}"
                         @endif            
                       >
             </td>
         </tr>
         <tr>
             <td><label>Vil./Subd./Brgy:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="address2" id="address2"  placeholder="Enter Vil. / Subdiv. / Brgy."
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address2}}"
                         @endif             
                       >
             </td>
             <td></td>
             <td><label>Vil./Subd./Brgy:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="address7" id="address7" placeholder="Enter Vil. / Subdiv. / Brgy."
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address7}}"
                         @endif             
                       >
             </td>
             
         </tr>
         <tr>
             <td><label>District:</label></td>
             <td>
                 <input type="text" class="form-control" name="address5" id="address5"  placeholder="Enter District"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address5}}"
                         @endif             
                       >
             </td>
             
             <td><label>City/Municipality:</label></td>
             <td>
                 <input type="text" class="form-control" name="address3" id="address3" placeholder="Enter city municipality"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address3}}"
                         @endif            
                       >
             </td>
             <td></td>
             <td><label>City/Municipality:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="address8" id="address8" placeholder="Enter city municipality"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address8}}"
                         @endif            
                       >
             </td>             
         </tr>
         <tr>
             <td><label>Region:</label></td>
             <td>
                 <input type="text" class="form-control" name="address4" id="address4" placeholder="Enter region"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address4}}"
                         @endif            
                       >
             </td>
             <td><label>Zip Code:</label></td>
             <td>
                 <input type="text" class="form-control" name="zipcode" id="zipcode"  placeholder="Enter zipcode"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->zipcode}}"
                         @endif            
                       >
             </td>
             <td></td>
             <td>Province:</td>
             <td colspan="3">
                 <input type="text" class="form-control" name="address9" id="address9"  placeholder="Enter province"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->address9}}"
                         @endif            
                       >
             </td>
         </tr>
         <tr>
             <td colspan="9"><br><br></td>
         </tr>
       
         <tr>
             <td><label>Email:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="email" id="email" placeholder="Enter e-mail"
                 @if($student != NULL)
                 value="{{$student->email}}"
                 @endif            
                 >
             </td>
             <td></td>
             <td style="width: 152px;"><label>School Last Attended</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="lastattended" id="lastattended" placeholder="Enter school last attended"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->lastattended}}"
                 @endif            
                 >
             </td>
         </tr>
         <tr>
             <td><label>Landline No.:</label></td>
             <td>
                 <input type="text" class="form-control" name="phone1" id="phone1" placeholder="Enter landline number"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->phone1}}"
                 @endif            
                 >
             </td>
             <td><label>Mobile No.:</label></td>
             <td>
                 <input type="text" class="form-control" name="phone2" id="phone2" placeholder="Enter mobile number"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->phone2}}"
                 @endif            
                 >
             </td>
             <td></td>
             <td><label>Grade/Year:</label></td>
             <td>
                 <input type="text" class="form-control" name="lastlevel" id="lastlevel" placeholder="Enter grade year"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->lastlevel}}"
                 @endif            
                 >
             </td>
             <td width="96px"><label>School Year:</label></td>
             <td>
                 <input type="text" class="form-control"  name="lastyear" id="lastyear" placeholder="Enter school year"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->lastyear}}"
                 @endif            
                 >
             </td>             
         </tr>
         <tr>
             <td><label>No. Of Children:</label></td>
             <td>
                 <div class="form-inline">
                     <div class="form-group">
                         <input type="text" class="form-control" name="countboys" id="noofstudentboys" style="width:74%" placeholder="Enter No. Boys"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->countboys}}"
                         @endif                       
                         >
                         <label>boys</label>
                    </div>
                 </div>
             </td>
             <td></td>
             <td>
                 <div class="form-inline">
                         <div class="form-group">                 
                 <input type="text" class="form-control" name="countgirls" id="noofstudentgirls" style="width:75%" placeholder="Enter No. Girls"
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->countgirls}}"
                 @endif                       
                 >
           <label>girls</label>
                         </div>
                 </div>
             </td>
             <td></td>
             <td><label>LRN:</label></td>
             <td colspan="3">
                 <input type="text" class="form-control" name="lrn"  id="lrn" placeholder="Enter LRN No."
                 @if($studentInfo != NULL)
                 value="{{$studentInfo->lrn}}"
                 @endif
                 >
             </td>
         </tr>
         <tr>
             <td colspan="4"><sup>(INCLUDING THIS STUDENT)</sup></td>
                <td></td>
                <td><label>ESC Grantee:</label></td>
                <td>
                    <input type="checkbox" class="form-control" value="1" name="esc" id="esc">
                </td>
                <td>ESC No.</td>
                <td>
                    <input type="text" class="form-control" name="escNo" id="escNo" placeholder="Enter ESC No."
                    @if($studentInfo != NULL)
                    value="{{$studentInfo->escNo}}"
                    @endif                       
                    >
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
                     <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter name "
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fname}}"
                     @endif                       
                     >
                 </td>
                 <td></td>
                 <td><label>Name:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mname" id="mname" placeholder="Enter name "
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mname}}"
                     @endif                       
                     >
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
                     <input type="text" class="form-control" name="fyeargraduated" id="fyeargraduated" placeholder="Enter year"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fyeargraduated}}"
                     @endif                       
                     />
                 </td>
                 <td colspan="5">
             </tr>
             <tr>
                 <td width="101px"><label>Date of Birth:</label></td>
                 <td>
                     <input type="text" name="fbirthdate" id="fbirthdate" class="form-control datepicker" placeholder="Date of Birth"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fbirthdate}}"
                     @endif                         
                     />
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
                     <input type="text" name="mbirthdate" id="mbirthdate" class="form-control datepicker" placeholder="Date of Birth"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mbirthdate}}"
                     @endif                         
                     />
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
                     <input type="text" class="form-control" name="freligion" id="mreligion" placeholder="Enter Religion"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->mreligion}}"
                         @endif
                       >
                 </td>
                 <td><label>Nationality:</label></td>
                 <td>
                     <input type="text" class="form-control" name="fnationality" id="mnationality"  placeholder="Enter Nationality"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->mnationality}}"
                         @endif
                       >
                 </td>
                 <td></td>
                 <td><label>Religion:</label></td>
                 <td>
                     <input type="text" class="form-control" name="mreligion" id="mreligion" placeholder="Enter Religion"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->mreligion}}"
                         @endif
                       >
                 </td>
                 <td><label>Nationality:</label></td>
                 <td>
                     <input type="text" class="form-control" name="mnationality" id="mnationality" placeholder="Enter Nationality"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->mnationality}}"
                         @endif
                       >
                 </td>
             </tr>
             <tr>
                 <td><label>Mobile No.:</label></td>
                 <td>
                     <input type="text" class="form-control" name="fmobile" id="fmobile" placeholder="Enter Mobile No"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->fmobile}}"
                         @endif
                       >
                 </td>
                 <td><label>Landline:</label></td>
                 <td>
                     <input type="text" class="form-control" name="flandline" id="flandline" placeholder="Enter Landline No"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->flandline}}"
                         @endif
                       >
                 </td>                 
                 <td></td>
                 <td><label>Mobile No.:</label></td>
                 <td>
                     <input type="text" class="form-control" name="mmobile" id="mmobile" placeholder="Enter Mobile No"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->mmobile}}"
                         @endif
                       >
                 </td>
                 <td><label>Landline:</label></td>
                 <td>
                     <input type="text" class="form-control" name="mlandline" id="mlandline" placeholder="Enter Landline No"
                         @if($studentInfo != NULL)
                         value="{{$studentInfo->mlandline}}"
                         @endif
                       >
                 </td>                 
                 
             </tr>
             <tr>
                 <td colspan="4"><label>What course did you take up in college?</label></td>
                 <td></td>
                 <td colspan="4"><label>What course did you take up in college?</label></td>
             </tr>
             <tr>
                 <td colspan="4">
                     <input type="text" class="form-control" name="fcourse" id="fcourse" placeholder="Enter year"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fcourse}}"
                     @endif                                  
                     >
                 </td>
                 <td></td>
                 <td colspan="4">
                     <input type="text" class="form-control" name="mcourse" id="mcourse" placeholder="Enter year"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mcourse}}"
                     @endif                                  
                     >
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
                     <input type="text" class="form-control" name="fFulljob" id="fFulljob" placeholder="Enter full time "
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fFulljob}}"
                     @endif                                             
                   >
                 </td>
                 <td></td>
                 <td><label>Full-time:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mFulljob" id="mFulljob" placeholder="Enter full time "
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mFulljob}}"
                     @endif                                             
                   >
                 </td>
             </tr>
             <tr>
                 <td><label>Part-time:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="fPartjob" id="fPartjob" placeholder="Enter part time "
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fPartjob}}"
                     @endif
                   >
                 </td>
                 <td></td>
                 <td><label>Part-time:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mPartjob" id="mPartjob" placeholder="Enter part time "
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mPartjob}}"
                     @endif
                   >
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
                     <input type="text" class="form-control" name="fincome" id="fincome" placeholder="Enter course in college"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fincome}}"
                     @endif                                             
                     >
                 </td>
                 <td></td>
                 <td width="123px"><label>Monthly Income:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mincome" id="mincome" placeholder="Enter course in college"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mincome}}"
                     @endif                                             
                     >
                 </td>
             </tr>
             <tr>
                 <td><label>Company Name:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="fcompany" id="fcompany" placeholder="Enter company name"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fcompany}}"
                     @endif                                             
                     >
                 </td>
                 <td></td>
                 <td><label>Company Name:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mcompany" id="mcompany" placeholder="Enter company name"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mcompany}}"
                     @endif                                             
                     >
                 </td>
             </tr>
             <tr>
                 <td colspan="4"><label>Company Address:</label></td>
                 <td></td>
                 <td colspan="4"><label>Company Address:</label></td>
             </tr>
             <tr>
                 <td colspan="4">
                     <input typ  e="text" class="form-control" name="fComAdd" id="fComAdd" placeholder="Enter company address"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fComAdd}}"
                     @endif                                             
                     >
                 </td>
                 <td></td>
                 <td colspan="4">
                     <input typ  e="text" class="form-control" name="mComAdd" id="mComAdd" placeholder="Enter company address"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mComAdd}}"
                     @endif                                             
                     >
                 </td>
             </tr>
             <tr>
                 <td><label>Office Tel. No.:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="fOfficePhone" id="fOfficePhone" placeholder="Enter office tel. no."
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->fOfficePhone}}"
                     @endif                                             
                     >
                 </td>
                 <td></td>
                 <td><label>Office Tel. No.:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mOfficePhone" id="mOfficePhone" placeholder="Enter office tel. no."
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mofficePhone}}"
                     @endif                                             
                     >
                 </td>
             </tr>
             <tr>
                 <td><label>Office Fax No.:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="ffax" id="ffax" placeholder="Enter office fax no."
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->ffax}}"
                     @endif                                             
                     >
                 </td>
                 <td></td>
                 <td><label>Office Fax No.:</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="mfax" id="mfax" placeholder="Enter office fax no."
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->mfax}}"
                     @endif                                             
                     >
                 </td>
             </tr>
             <tr>
                 <td><label>Email Address</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="femail" id="femail" placeholder="Enter email"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->femail}}"
                     @endif                                             
                     >
                 </td>
                 <td></td>
                 <td><label>Email Address</label></td>
                 <td colspan="3">
                     <input type="text" class="form-control" name="memail" id="memail" placeholder="Enter email"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->memail}}"
                     @endif                                             
                     >
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
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->numHouseHelp}}"
                     @endif             
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
                     <input type="text" class="form-control" name="carcount" placeholder="Enter number"
                     @if($studentInfo != NULL)
                     value="{{$studentInfo->carcount}}"
                     @endif             
                     >
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
        <input type="text" class="form-control" name="sibling<?php echo $counter;?>" placeholder="Enter name" 
             @if($sibling != NULL)
             value="{{$sibling[$counter-1]->name}}"
             @endif                
               >
    </td>
    <td> 
        <input type="text" name="siblingbday<?php echo $counter;?>" class="form-control datepicker" 
             @if($sibling != NULL)
             value="{{$sibling[$counter-1]->birthdate}}"
             @endif                
               />
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
        <input type="text" class="form-control" name="where<?php echo $counter;?>"
             @if($sibling != NULL)
             value="{{$sibling[$counter-1]->where}}"
             @endif                
               >
    </td>    
    </tr>
    <?php } ?>
<!--    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling2" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling2bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling2gender" id="sibling2gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling2civilstatus" id="sibling2civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling2working" id="sibling2working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling2studying" id="sibling2studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling2workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling3" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling3bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling3gender" id="sibling3gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling3civilstatus" id="sibling3civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling3working" id="sibling3working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling3studying" id="sibling3studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling3workingstudyingwhere">
    </td>    
    </tr> 
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling4" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling4bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling4gender" id="sibling4gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling4civilstatus" id="sibling4civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling4working" id="sibling4working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling4studying" id="sibling4studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling4workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling5" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling5bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling5gender" id="sibling5gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling5civilstatus" id="sibling5civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling5working" id="sibling5working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling5studying" id="sibling5studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling5workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling6" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling6bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling6gender" id="sibling6gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling6civilstatus" id="sibling6civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling6working" id="sibling6working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling6studying" id="sibling6studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling6workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling7" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling7bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling7gender" id="sibling7gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling7civilstatus" id="sibling7civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling7working" id="sibling7working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling7studying" id="sibling7studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling7workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling8" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling8bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling8gender" id="sibling8gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling8civilstatus" id="sibling8civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling8working" id="sibling8working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling8studying" id="sibling8studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling8workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling9" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling9bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling9gender" id="sibling9gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling9civilstatus" id="sibling9civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling9working" id="sibling9working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling9studying" id="sibling9studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling9workingstudyingwhere">
    </td>    
    </tr>
    <tr>       
    <td> 
        <input type="text" class="form-control" name="sibling10" placeholder="Enter name ">
    </td>
    <td> 
        <input type="text" name="sibling10bday" class="form-control" />
    </td>
    <td> 
      <select class="form-control" name="sibling10gender" id="sibling10gender">
        <option>MALE</option>
        <option>FEMALE</option>
      </select>
    </td>
    <td>
      <select class="form-control" name="sibling10civilstatus" id="sibling10civilstatus">
        <option>SINGLE</option>
        <option>MARRIED</option>
        <option>DIVORCED</option>
        <option>DECEASED</option>
        <option>WIDOWED</option>
        <option>ANNULLED</option>
        <option>SEPARATED</option>
      </select>        
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling10working" id="sibling10working">
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="sibling10studying" id="sibling10studying">
    </td>
    <td> 
        <input type="text" class="form-control" name="sibling10workingstudyingwhere">
    </td>    
    </tr>-->    
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
                    <input type="text" class="form-control" name="guardianname" id="guardianname" placeholder="Name"
             @if($studentInfo != NULL)
             value="{{$studentInfo->guardianname}}"
             @endif
                           >
                    </div>
                    <div class="col-md-4">
                    <label>CELL NO:</label>
                    <input type="text" class="form-control" name="guardianmobile" id="guardianmobile" placeholder="Contact No."
             @if($studentInfo != NULL)
             value="{{$studentInfo->guardianmobile}}"
             @endif                           
                           >
                    </div>    
                    <div class="col-md-4">
                    <label>RELATIONSHIP:</label>
                    <input type="text" class="form-control" name="guardianrelationship" id="guardianrelationship" placeholder="Relationship"
             @if($studentInfo != NULL)
             value="{{$studentInfo->guardianrelationship}}"
             @endif                           
                           >
                    </div>                        
                    </div>                        
                </td>
            </tr>
        </tbody>
    </table>

</div>
<button type="submit" class="btn btn-primary">Save</button>
 @if($student != NULL)
    <a href="#" id="delete" class="btn btn-primary">Delete</a>
    <a href="{{url('studentinfokto12/'.$student->idno.'/print')}}" id="print" class="btn btn-primary">Print</a>
    
 @endif
    </form>
    
<script>
    @if($student == NULL)
        @if(Auth::guest())
            var varid = 4;
        @else
            var varid = {{Auth::User()->id}};
        @endif
        
        $.ajax({
         type: "GET", 
         url: "/getpreregid/" + varid , 
         success:function(data){  
             
            document.getElementById('idno').value = data;
            $("#studno").html(data);
         }       
        });
    @endif

    $( '#transportation' ).change(function() {
        if ($(this).val() == "OWN"){
            $("#carcount").show();
        }
        else{
            $("#carcount").hide();
        }
    });
    $( "#birthDate" ).change(function() {
        getAge();
    });
    $( ".datepicker" ).click(function() {
        getAge();
    });    
    $( "#age" ).focus(function() {
        getAge();
    });  
    
    @if($status!= NULL)
        function drop(){
            var drop = confirm("Do you really want to drop this student?\n\
        \n\
        Please ask help from administrator if needed to be changed back.");
            if(drop == true){
                $.ajax({
                    type: "GET", 
                    url: "/dropStudent/" + {{$student->idno}}, 
                    success:function(data){
                        $("#status").html(data);
                    }
                });    
            }
        }
    @endif

function getAge(){
    var bdate = document.getElementById("birthDate").value;
    var date1 = new Date(bdate);
    var date2 = new Date();
    var timeDiff = Math.abs(date2.getYear() - date1.getYear());
    var month =0
    if(date2.getMonth() < date1.getMonth()){
        timeDiff = timeDiff-1;
        month = 12-date1.getMonth();
        month = month+date2.getMonth();
    } else {
        month=date2.getMonth()-date1.getMonth();
    }

    document.getElementById("age").value = timeDiff + "." + month;    
}

@if($student != NULL )
    @if($studentInfo->transportation == "OWN")
            $("#carcount").show();
    @else
            $("#carcount").hide();
    @endif
    
    @if($studentInfo->esc == 1)
        document.getElementById("esc").checked = true;
    @endif    
    
    @if(count($sibling)>0)    
        <?php $rows = 10;
        for($counter = 1;$counter<=$numberofrow;$counter++){ ?>    
            document.getElementById("siblinggender<?php echo $counter;?>").value = "{{$sibling[$counter-1]->gender== NULL ? 'MALE' : $sibling[$counter-1]->gender}}";
            document.getElementById("siblingstatus<?php echo $counter;?>").value = "{{$sibling[$counter-1]->status== NULL ? 'SINGLE' : $sibling[$counter-1]->status}}";                
            @if($sibling[$counter-1]->working == 1)
                document.getElementById("working<?php echo $counter;?>").checked = true;
            @endif 
            @if($sibling[$counter-1]->studying == 1)
                document.getElementById("studying<?php echo $counter;?>").checked = true;
            @endif                 
            @if($sibling[$counter-1]->dbti == 1)
                document.getElementById("dbti<?php echo $counter;?>").checked = true;
            @endif                             
        <?php } ?>
    @endif
/*
    var date1 = new Date("{{$studentInfo->birthDate}}");
    var date2 = new Date();
    var timeDiff = Math.abs(date2.getYear() - date1.getYear());
    if(date2.getMonth() < date1.getMonth()){
        timeDiff = timeDiff-1;
    }

    document.getElementById("age").value = timeDiff;
 */
    getAge();
    
    document.getElementById("status").value = "{{$studentInfo->status == NULL ? 'SINGLE' : $studentInfo->status }}"; 
    
    document.getElementById("fselfemployed").value = "{{$studentInfo->fselfemployed== NULL ? '0' : $studentInfo->fselfemployed}}";
    document.getElementById("falumnus").value = "{{$studentInfo->falumnus== NULL ? '0' : $studentInfo->falumnus}}";    
    document.getElementById("fstatus").value = "{{$studentInfo->fstatus== NULL ? 'SINGLE' : $studentInfo->fstatus}}";
    document.getElementById("fposition").value = "{{$studentInfo->fposition== NULL ? 'TOP MANAGEMENT' : $studentInfo->fposition}}";

    document.getElementById("mselfemployed").value = "{{$studentInfo->mselfemployed == NULL ? '0' : $studentInfo->mselfemployed}}";
    document.getElementById("mstatus").value = "{{$studentInfo->mstatus== NULL ? 'SINGLE' : $studentInfo->mstatus}}";
    document.getElementById("mposition").value = "{{$studentInfo->mposition== NULL ? 'TOP MANAGEMENT' : $studentInfo->mposition}}";
    
    document.getElementById("residence").value = "{{$studentInfo->residence== NULL ? 'HOUSE' : $studentInfo->residence}}";
    document.getElementById("ownership").value = "{{$studentInfo->ownership== NULL ? 'OWN' : $studentInfo->ownership}}";    
    document.getElementById("transportation").value = "{{$studentInfo->transportation== NULL ? 'COMMUTE' : $studentInfo->transportation}}";
    document.getElementById("haveComputer").value = "{{$studentInfo->haveComputer== NULL ? '0' : $studentInfo->haveComputer}}";    
    document.getElementById("haveInternet").value = "{{$studentInfo->haveInternet== NULL ? '0' : $studentInfo->haveInternet}}";    
    document.getElementById("internetType").value = "{{$studentInfo->internetType== NULL ? 'DSL' : $studentInfo->internetType}}";    

    $( "#delete" ).click(function() {
        var r = confirm("Are you sure you want to delete this student's information");
        if (r === true) {
           var url="{{url('studentinfokto12/'.$student->idno.'/delete')}}";
           $(location).attr('href',url);
        }      
    });    

@endif
$("#changecourse").hide();
function updateCourse(){
    $("#currcourse").hide();
    $("#changecourse").show();
}

function cancelChange(){
    $("#currcourse").show();
    $("#changecourse").hide();
}

</script>    
</div>
</html>