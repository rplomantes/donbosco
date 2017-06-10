<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">

        <link href="{{ asset('/css/fileinput.css') }}" rel="stylesheet">        
        
        <script src="{{asset('/js/jquery.js')}}"></script>
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>        
        
        <style type='text/css'>
            p{
                font-size: 10px;
            }
            .label{
                font-size: 11px;
                text-align:center;
                margin-top: 1px;
            }            

            .block{
                border:1px solid #a8a8a8;
                padding:2px 15px;
                padding-top: 5px
            }
            input[type="text"] {
                background: none!important;
                border: none!important;
                border-bottom: 1px solid!important;
                box-shadow: none!important;
                border-radius: 0px!important;
                padding-bottom: 0px!important;
                height: 15px;
                text-align: left;
                overflow: visible;
                white-space: nowrap;
                font-size:12px;
                text-align: center;                
                font-size: 11px;
            }            

            
            .col-2{
                width: 49%; 
                display: inline-block;
            }
            .groupname{
                position: relative;
                
                background-color: white;
                left: 10px;
            }
        </style>

        </head>
<body> 
    <table width="100%">
        <tbody width="100%">
            <tr>
                <td colspan="3" style="vertical-align:top">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="right">

                    <tr>
                        <td rowspan="4" style="text-align: right;" class="logo" width="55px">
                            <img src="{{asset('images/logo.png')}}"  style="display: inline-block;width:70px">
                        </td>
                        <td>
                            <span style="font-size:13pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span>
                        </td>
                    </tr>
                    <tr><td style="font-size:10pt;">Chino Roces Ave.</td></tr>
                    <tr><td style="font-size:10pt;">Makati City </td></tr>
                    <tr><td style="font-size:10pt;">School Year 2016 - 2017</td></tr>
                    <tr><td style="font-size:4pt;">&nbsp; </td></tr>
                    <tr><td><span style="font-size"></td></tr>
                    </table>                    
                </td >
                <td colspan="1" style="width:21%;vertical-align: top;">
                    <p style="text-align: right">DBTI-Form 1</p>
                    <span class="label" style="padding-right: 5px;">Gr/Yr:<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width: 80px;"><br>
                    <span class="label" style="padding-right: 5px;">Section:<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width: 80px;"><br>
                    <span class="label" style="margin-right: 10px;">Shop:<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width: 80px;"><br>
                    <span class="label" style="margin-right: 7px;">Student No:<span>&nbsp;&nbsp;<input type="text" value="{{$student->idno}}" style="width: 80px;"><br>
                </td>
                    
            </tr>
        </tbody>
    </table>

<h4 align="center" style="margin-bottom: 10px">Student Information Sheet</h4>
<div class="groupname" style="top:10px;border-bottom:1px solid;width:130px;text-align: center;">STUDENT DATA</div>
<div class="block">
<p>Student's Name: (Please write the complete name as it appears in the birth certificate)</p>

<table style="width:100%;">
    <tbody>
        <tr>
            <td>
                <input type="text" value="{{$student->lastname}}">
                <p class="label" style="padding-left:10px;text-align: left">(PRINT) FAMILY NAME</p>
            </td>
            <td>
                <input type="text" value="{{$student->firstname}}">
                <p class="label" style="padding-left:13px;text-align: left">(PRINT) GIVEN NAME</p>
            </td>            
            <td>
                <input type="text" value="{{$student->middlename}}">
                <p class="label" style="padding-left:10px;text-align: left">(PRINT) MIDDLE NAME</p>
            </td>            
            <td>
                <input type="text" value="{{$student->extensionname}}">
                <p class="label" style="text-align: left">(PRINT) EXTENSION NAME</p>
            </td>            
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Date of  Birth:</span>&nbsp;&nbsp;<input type="text" value="{{$studentInfo->birthDate}}" style="width:110px">&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="label">Age:</span><input id="age" type="text" style="width:60px" value="{{$age}}"><br>
                <span class="label">Place of Birth:</span>&nbsp;<input type="text" value="{{$studentInfo->birthPlace}}" style="width:220px">
            </td>
            
            <td colspan="2" style="padding-left: 20px;">
                
                <span class="label">Gender:</span>&nbsp;&nbsp;&nbsp;<input type="text" value="{{$student->gender}}" style="width:80px">&nbsp;&nbsp;
                <span class="label">Civil Status:</span><input id="age" type="text" value="{{$studentInfo->status}}" style="width:87px"><br>
                <span class="label">Religion:</span>&nbsp;&nbsp;<input id="age" type="text" value="{{$studentInfo->religion}}" style="width:240px">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Citizenship:</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" value="{{$studentInfo->citizenship}}" style="width:219px">
            </td>
            
            <td colspan="2" style="padding-left: 20px;">
                
                <span class="label">ACR Number:</span><input type="text" value="{{$studentInfo->acr}}" style="width:225px"><br>
                <span class="label" style="padding-left:20px">Visa Type:</span><input id="age" type="text" value="{{$studentInfo->visaType}}" style="width:220px">
            </td>
        </tr>        
        <tr>
            <td colspan="2">
                <p><b>CITY ADDRESS</b></p>
                <span class="label">House No/Street:</span><input type="text" value="{{$studentInfo->address1}}" style="width:213px"><br>
                <span class="label">Vil./Subd./Brgy.:</span><input type="text" value="{{$studentInfo->address2}}" style="width:213px"><br>                
                <span class="label">District:</span><input type="text" value="{{$studentInfo->address5}}" style="width:50px">&nbsp;&nbsp;
                <span class="label">City/Municipality:</span><input type="text" value="{{$studentInfo->address3}}" style="width:100px"><br>
                <span class="label">Region:</span><input type="text" value="{{$studentInfo->address4}}" style="width:118px">&nbsp;&nbsp;&nbsp;
                <span class="label">Zip Code:</span><input type="text" value="{{$studentInfo->zipcode}}" style="width:66px"><br>                
            </td>
            
            <td colspan="2" style="padding-left: 20px;">
                <p><b>PROVINCIAL ADDRESS</b></p>
                
                <span class="label">House No/Street:</span><input type="text" value="{{$studentInfo->address6}}" style="width:212px"><br>
                <span class="label">Vil./Subd./Brgy.:</span><input type="text" value="{{$studentInfo->address7}}" style="width:212px"><br>                
                <span class="label">City/Municipality:</span><input type="text" value="{{$studentInfo->address8}}" style="width:207px">
                <span class="label">Province:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" value="{{$studentInfo->address9}}" style="width:215px"><br>
                </td>
        </tr>                
        <tr style="height:1px;">
            <td colspan="4" >
                <br>
            </td>
        </tr>        
        <tr>
            <td colspan="2" >
                <span class="label">Email:</span>&nbsp;&nbsp;&nbsp;<input type="text" value="{{$student->email}}" style="width:246px"><br>
                <span class="label">Landline No:</span><input type="text" value="{{$studentInfo->phone1}}" style="width:80px">&nbsp;
                <span class="label">Mobile No:</span><input type="text" value="{{$studentInfo->phone2}}" style="width:82px">
            </td>            
            <td colspan="2" style="padding-left: 20px;">
                <span class="label">School Last Attended:</span><input type="text" value="{{$studentInfo->lastattended}}" style="width:190px"><br>
                <span class="label">Grade/Year:</span><input type="text" value="{{$studentInfo->lastlevel}}" style="width:90px">&nbsp;
                <span class="label">School Year:</span><input type="text" value="{{$studentInfo->lastyear}}" style="width:70px">
            </td>
        </tr>

        <tr>
            
            <td colspan="2" >
                <span class="label">No of children:</span>
                <input type="text" value="{{$studentInfo->countboys}}" style="width:77px"><span class="label">boys</span>&nbsp;&nbsp;
                <input type="text" value="{{$studentInfo->countgirls}}" style="width:77px"><span class="label">girls</span><br>
                <span class="label">(including this student)</span>
            </td>            
            <td colspan="2" style="padding-left: 20px;">
                <span class="label">LRN:</span>&nbsp;&nbsp;&nbsp;<input type="text" value="{{$studentInfo->lrn}}" style="width:250px"><br>
                <span class="label">ESC Guarantee:</span><input type="checkbox" class="form-control" value="1" name="working" style="margin-left: 15px;width:20px"
             @if($studentInfo != NULL)
                 @if($studentInfo->esc == 1)
                    checked
                 @endif
             @endif
               >&nbsp;
                <span class="label">ESC No:</span><input type="text" value="{{$studentInfo->escNo}}" style="width:135px">
            </td>
        </tr>        
    </tbody>
</table>
</div>
<br>
<div class="groupname" style="top:10px;border-bottom:1px solid;width:130px;text-align: center;">PARENTS DATA</div>
<div class="block">
    <table style="width:100%;">
        <tbody>
            <tr>
                <td style="width:49%;vertical-align: top">
                    <p><h5 style="text-align: center;">FATHER</h5></p>
                    <span class="label">Name:</span>&nbsp;&nbsp;&nbsp;<input type="text" value="{{$studentInfo->fname}}" style="width:250px;"><br>
                    <span class="label">Are you a DBTI-Makati Alumnus?</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    @if($studentInfo->falumnus == 1)
                    <input type="radio" style="border-bottom: 0px;width:20px;" checked><span class="label">Yes</span>&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;"><span class="label">No</span><br>
                    @else
                    <input type="radio" style="border-bottom: 0px;width:20px;"><span class="label">Yes</span>&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" checked><span class="label">No</span>                    
                    @endif
                    <span class="label" style="padding-left:80px;">Year Graduated:</span>&nbsp;&nbsp;&nbsp;<input type="text" value="{{$studentInfo->fyeargraduated}}" style="width:80px;"><br>
                    <span class="label">Birth date:</span>&nbsp;<input type="text" value="{{$studentInfo->fbirthdate}}" style="width:80px;">&nbsp;&nbsp;
                    <span class="label" style="padding-left:2px;">Civil status:</span>&nbsp;<input type="text" value="{{$studentInfo->fstatus}}" style="width:80px;"><br>
                    

                    <span class="label" >Religion:</span>&nbsp;<input type="text" value="{{$studentInfo->freligion}}" style="width:90px;white-space: normal;">&nbsp;
                    <span class="label" >Nationality:</span>&nbsp;<input type="text" value="{{$studentInfo->fnationality}}" style="width:83px;text-align: left;"><br>
                    <span class="label" >Cellphone No:</span>&nbsp;<input type="text" value="{{$studentInfo->fmobile}}" style="width:220px;"><br>
                    <span class="label" >What course did you take-up in college?</span><br><input type="text" value="{{$studentInfo->fcourse}}" style="width:290px;"><br>
                </td>
                <td style="width:49%; vertical-align:top;padding-left: 20px;">
                    <p ><h5 style="text-align: center;">MOTHER</h5></p>
                    <span class="label">Name:</span>&nbsp;&nbsp;&nbsp;<input type="text" value="{{$studentInfo->mname}}" style="width:250px;"><br>
                    <br>
                    <div style="height: 26px"></div>
                    <span class="label">Birth date:</span>&nbsp;<input type="text" value="{{$studentInfo->mbirthdate}}" style="width:80px;">&nbsp;&nbsp;
                    <span class="label" style="padding-left:2px;">Civil status:</span>&nbsp;<input type="text" value="{{$studentInfo->mstatus}}" style="width:80px;"><br>
                    <span class="label" >Religion:</span>&nbsp;<input type="text" value="{{$studentInfo->mreligion}}" style="width:90px;">&nbsp;&nbsp;
                    <span class="label" >Nationality:</span>&nbsp;<input type="text" value="{{$studentInfo->mnationality}}" style="width:80px;"><br>
                    <span class="label" >Cellphone No:</span>&nbsp;<input type="text" value="{{$studentInfo->mmobile}}" style="width:220px;"><br>
                    <span class="label" >What course did you take-up in college?</span><br><input type="text" value="{{$studentInfo->mcourse}}" style="width:290px;"><br>                    
                </td>                
            </tr>
            <tr>
                <td>
                    <p style="font-size: 12px"><b>Occupation</b></p>
                    <span class="label">Are you self employed?</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    @if($studentInfo->fselfemployed == 1)
                    <input type="radio" style="border-bottom: 0px;width:20px;" checked><span class="label">Yes</span>&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;"><span class="label">No</span><br>
                    @else
                    <input type="radio" style="border-bottom: 0px;width:20px;"><span class="label">Yes</span>&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" checked><span class="label">No</span><br>
                    @endif
                    <span class="label" >FULL-TIME:</span><input type="text" value="{{$studentInfo->fFulljob}}" style="width:230px;"><br>
                    <span class="label" >PART-TIME:</span><input type="text" value="{{$studentInfo->fPartjob}}" style="width:230px;"><br>
                    <span class="label" >Position:(In your major occupation/main source of income)</span><br><input type="text" value="{{$studentInfo->fposition}}" style="width:290px;"><br>
                    <span class="label" >Monthly Income:</span><input type="text" value="{{$studentInfo->fincome}}" style="width:213px;"><br>
                    <span class="label" >Company Name:</span><input type="text" value="{{$studentInfo->fcompany}}" style="width:215px;"><br>
                    <span class="label" >Company Address:</span><br><input type="text" value="{{$studentInfo->fComAdd}}" style="width:290px;"><br>
                    <span class="label" >Office Tel.:</span><input type="text" value="{{$studentInfo->fOfficePhone}}" style="width:240px;"><br>
                    <span class="label" >Office Fax:</span><input type="text" value="{{$studentInfo->ffax}}" style="width:240px;"><br>
                    <span class="label" >Email Address:</span><input type="text" value="{{$studentInfo->femail}}" style="width:223px;"><br>
                </td>
                <td style="padding-left: 20px;">
                    <p style="font-size: 12px"><b>Occupation</b></p>
                    <span class="label">Are you self employed?</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    @if($studentInfo->mselfemployed == 1)
                    <input type="radio" style="border-bottom: 0px;width:20px;" checked><span class="label">Yes</span>&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;"><span class="label">No</span><br>
                    @else
                    <input type="radio" style="border-bottom: 0px;width:20px;"><span class="label">Yes</span>&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" checked><span class="label">No</span><br>
                    @endif
                    <span class="label" >FULL-TIME:</span><input type="text" value="{{$studentInfo->mFulljob}}" style="width:230px;"><br>
                    <span class="label" >PART-TIME:</span><input type="text" value="{{$studentInfo->mPartjob}}" style="width:230px;"><br>
                    <span class="label" >Position:(In your major occupation/main source of income)</span><br><input type="text" value="{{$studentInfo->mposition}}" style="width:290px;"><br>
                    <span class="label" >Monthly Income:</span><input type="text" value="{{$studentInfo->mincome}}" style="width:213px;"><br>
                    <span class="label" >Company Name:</span><input type="text" value="{{$studentInfo->mcompany}}" style="width:215px;"><br>
                    <span class="label" >Company Address:</span><br><input type="text" value="{{$studentInfo->mComAdd}}" style="width:290px;"><br>
                    <span class="label" >Office Tel.:</span><input type="text" value="{{$studentInfo->mOfficePhone}}" style="width:240px;"><br>
                    <span class="label" >Office Fax:</span><input type="text" value="{{$studentInfo->mfax}}" style="width:240px;"><br>
                    <span class="label" >Email Address:</span><input type="text" value="{{$studentInfo->memail}}" style="width:223px;"><br>
                </td>                
            </tr>
        </tbody>
    </table>
</div>
<br>
<br>
<br>
<div class="groupname" style="top:10px;border-bottom:1px solid;width:300px;font-size:11;text-align: center;">RESIDENCE AND TRANSPORTATION DATA</div>
<div class="block">
    <table width="100%">
        <tbody width="100%">
            <tr>
                <td>
                    <span class="label" >Residence Type:</span><input type="text" value="{{$studentInfo->residence}}" style="width:230px;"><br>
                    <span class="label" >Number of Household Helper(s):</span><input type="text" value="{{$studentInfo->numHouseHelp}}" style="width:100px;"><br>
    
                </td>
                <td>
    
                    <span class="label" >Ownership of Residence:</span><input type="text" value="{{$studentInfo->ownership}}" style="width:230px;"><br>
                </td>                
            </tr>
            <tr >
                <td colspan="2">
                    <span class="label" >Residence Type:</span>
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->transportation == "COMMUTE"||$studentInfo->transportation == NULL)
                           checked
                           @endif
                           ><span class="label">Commute</span>&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->transportation == "SCHOOL BUS")
                           checked
                           @endif                           
                           ><span class="label">School Bus</span>&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->transportation == "OWN")
                           checked
                           @endif                           
                           ><span class="label">Own Vehicle</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="label" >How Many?:</span><input type="text" value="{{$studentInfo->carcount}}" style="width:100px;"><br>
                    <span class="label">Do you have a computer at home?</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->haveComputer == "1")
                           checked
                           @endif                           
                           ><span class="label">Yes</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->haveComputer == "0" || $studentInfo->transportation == NULL)
                           checked
                           @endif                           
                           ><span class="label">No</span><br>
                    <span class="label">Do you have an internet at home?</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->haveInternet == "1")
                           checked
                           @endif                           
                           ><span class="label">Yes</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->haveInternet == "0" || $studentInfo->transportation == NULL)
                           checked
                           @endif                           
                           ><span class="label">No</span><br>
                    <span class="label" style="padding-left:30px;">If yes what type of internet connection?</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->internetType == "DSL"||$studentInfo->transportation == NULL)
                           checked
                           @endif
                           ><span class="label">DSL</span>&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->internetType == "WIRELESS")
                           checked
                           @endif                           
                           ><span class="label">WIRELESS</span>&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->internetType == "DIAL-UP")
                           checked
                           @endif                           
                           ><span class="label">DIAL-UP</span>&nbsp;&nbsp;&nbsp;
                    <input type="radio" style="border-bottom: 0px;width:20px;" 
                           @if($studentInfo->internetType == "OTHERS")
                           checked
                           @endif                           
                           ><span class="label">OTHERS</span>&nbsp;&nbsp;&nbsp;

                </td>
            </tr>
            
        </tbody>
    </table>
</div>
<br>
<div class="groupname" style="top:10px;border-bottom:1px solid;width:130px;text-align: center;">SIBLING DATA</div>
<div class="block">
  <table width="100%">
    <tbody>
      <tr>
      <td class="label" style="text-align: center;">Name of student according to age (eldest to youngest)</td>
      <td class="label" style="text-align: center;">Birthday</td>
      <td class="label" style="text-align: center;">Gender</td>
      <td class="label" style="text-align: center;">Civil Status</td>
      <td class="label" style="text-align: center;">Working</td>
      <td class="label" style="text-align: center;">Studying</td>
      <td class="label" style="text-align: center;">Where</td>
      </tr>        
<?php 
$numberofrow = 10;
for($counter = 1;$counter<=$numberofrow;$counter++){ ?>      
    <tr>       
    <td> 
        <input type="text" name="sibling" style="width:160px" 
             @if($sibling != NULL)
             value="{{$sibling[$counter-1]->name}}"
             @endif                    
               >
    </td>
    <td> 
        <input type="text" name="siblingbday" style="width:80px" 
             @if($sibling != NULL)
                @if($sibling[$counter-1]->name != NULL)
                    value="{{$sibling[$counter-1]->birthdate}}"
                @endif
             @endif                    
               >
    </td>
    <td> 
      <input type="text" name="siblinggender" style="width:60px"
             @if($sibling != NULL)
                @if($sibling[$counter-1]->name != NULL)             
                    value="{{$sibling[$counter-1]->gender}}"
                @endif
             @endif             
             >
    </td>
    <td>
      <input type="text" name="siblingstatus"  style="width:80px" 
             @if($sibling != NULL)
                @if($sibling[$counter-1]->name != NULL)             
                    value="{{$sibling[$counter-1]->status}}"
                @endif
             @endif                    
             >
    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="working" style="margin-left: 15px"
             @if($sibling != NULL)
                 @if($sibling[$counter-1]->working == 1)
                    checked
                 @endif
             @endif
               >

    </td>
    <td>
        <input type="checkbox" class="form-control" value="1" name="studying" style="margin-left: 15px"
             @if($sibling != NULL)
                 @if($sibling[$counter-1]->studying == 1)
                    checked
                 @endif
             @endif               
               >
    </td>
    <td> 
        <input type="text" class="form-control" name="where"
             @if($sibling != NULL)
                 value="{{$sibling[$counter-1]->where}}"
             @endif               
               >
    </td>    
    </tr>
<?php }?>    
        </tbody>
    </table>
</div>
<br>
<div class="groupname" style="top:10px;border-bottom:1px solid;width:300px;text-align: center;font-size:14px">CONTACT PERSON IN CASE OF EMERGENCY</div>
<div class="block">
<table style="width:100%;">
    <tbody width="100%">
        <tr>
            <td>
                <span class="label" >Name:</span><input type="text" value="{{$studentInfo->guardianname}}" style="width:150px;">
            </td>
            <td>
                <span class="label" >Contact No:</span><input type="text" value="{{$studentInfo->guardianmobile}}" style="width:120px;">
            </td>            
            <td style="padding-left: 50px">
                <span class="label" >Relationship:</span><input type="text" value="{{$studentInfo->guardianrelationship}}" style="width:190px;">
            </td>            
        </tr>
     </tbody>
    </table>
</div>
</body>

</html>
