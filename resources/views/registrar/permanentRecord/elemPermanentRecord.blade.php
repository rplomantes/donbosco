<?php 
use App\Http\Controllers\Registrar\PermanentRecord;
use App\Http\Controllers\Registrar\Helper as Registrarhelper;

$info = Registrarhelper::info($idno);
$student = \App\User::where('idno',$idno)->first();
?>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta author="John Vincent Villanueva">
    <meta poweredby = "Nephila Web Technology, Inc">
    
    <style>
        body{
            margin-left: -30px;
            margin-right: -30px;
            margin-top: -40px;
            font-family: dejavu sans;
            page-break-after: avoid;
        }
        .underscore{
            border-bottom: 1px solid;
        }
        
    </style>
</head>
<body>
    <div width="100%" @if($header != 1)style="visibility:hidden;"@endif>
    <table width="100%">
        <tr><td style="padding-left: 0px;text-align: center;"><span style="font-size:11pt;font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span></td></tr>
        <tr><td style="font-size:8pt;text-align: center;">Chino Roces Ave., Makati City 1200</td></tr>
        <tr><td style="font-size:8pt;text-align: center;">Tel. No. (02) 892.0101 to 08; Fax (02) 893.84.67</td></tr>
        <tr><td style="font-size:8pt;text-align: center;"><span style="border-bottom: 1px solid">www.donboscomakati.edu.ph</span></td></tr>
        <tr><td style="font-size:8pt;text-align: center;">PAASCU Accredited</td></tr>
        <tr><td style="font-size:8pt;padding-left: 0px;padding-top: 8pt;"></td></tr>
        <tr>
            <td colspan="1" style="padding-left: 0px;">
                <div style="text-align: center;font-size:10pt;"><b>ELEMENTARY SCHOOL PERMANENT RECORD</b></div>
                <div style="text-align: center;font-size:9pt;"><b>(FORM 137-OFFICIAL TRANSCRIPT OF RECORDS)</b></div>
            </td>
        </tr>
    
    </table>
    <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png"  style="position:absolute;width:108px;height:auto;top:0px;left:80px;">
    <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/boscorale.png"  style="position:absolute;width:100px;height:auto;top:0px;right:80px;">
    
    <table width="100%" style="font-size: 9pt" cellspacing="2" >
        <tr style='text-align: center;font-size: 10pt'>
            <td rowspan="2" width='14%' style="font-size: 9pt">
                <div><b>STUD. NO.</b></div>
                <div style='border: 1px solid'>{{$info->idno}}</div>
            </td>
            <td><b>{{strtoupper($student->lastname)}}</b></td>
            <td><b>{{strtoupper($student->firstname)}}</b></td>
            <td><b>{{strtoupper($student->middlename)}}</b></td>
        </tr>
        <tr style='text-align: center'>
            <td>Family Name</td>
            <td>First Name</td>
            <td>Middle Name</td>
        </tr>
    </table>
    <table width="100%" style="font-size: 9pt" cellspacing="2" >
        <tr>
            <td>Date of Birth:</td>
            <td>{{date_format(date_create($info->birthDate),"F d,Y")}}</td>
            <td style="font-size: 8.5pt">Place of Birth:</td>
            <td>{{$info->birthPlace}}</td>
        </tr>
        <tr>
            <td>Parent or Guardian:</td>
            <td>{{$info->fname != ""?$info->fname:$info->mname}}</td>
            <td style="font-size: 8.5pt">Occupation:</td>
            <td>{{$info->fname != ""?$info->fFulljob:$info->mFulljob}}</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 8pt">
                Address of Parent or Guardian:                    
                    {{$info->address1}}
                    @if($info->address2 != "")
                    ,{{$info->address2}}
                    @endif
                    @if($info->address3 != "")
                    , {{$info->address3}}
                    @endif
            </td>
            <td>
                LRN:{{$info->lrn}}
            </td>
        </tr>
    </table>
    <hr style="border:2px solid;">
    <div style="text-align: center;font-weight: bold;">PREPARATORY</div>
    <table border="1" cellspacing="0" width="100%" style="font-size: 7pt;">
        <tr style="text-align: center;vertical-align: top">
            <td width="9%">
                <div style="font-weight: bold">School Year</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Date Entered</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Date Left</div>
            </td>
            <td >
                <div style="font-weight: bold">School Attended</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Grade</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Days Present</div>
            </td>
            <td width="9%">
                <div style="font-weight: bold">Final Rating</div>
            </td>
            <td width="9%">
                <div style="font-weight: bold">Prom./Ret.</div>
            </td>
        </tr>
        @foreach($oldrec as $rec)
        <tr>
            <td>{{$rec->sy}}</td>
        </tr>
        @endforeach
    </table>
    </div>
    
    <div width="100%">
        <table width="100%" cellspacing="3">
            <tr>
                <td width="49%" style='vertical-align: top;'><span @if($grade1 == 0)style="visibility:hidden;"@endif>{!!PermanentRecord::elemGradeTemp($idno,"Grade 1")!!}</span></td>
                <td width="2%"  style='vertical-align: top;'></td>
                <td width="49%" style='vertical-align: top;'><span @if($grade2 == 0)style="visibility:hidden;"@endif>{!!PermanentRecord::elemGradeTemp($idno,"Grade 2")!!}</span></td>
            </tr>
        </table>
    </div>
    

    <div width="100%">
        <table width="100%" cellspacing="3">
            <tr style="min-height: 800px">
                <td width="49%" style='vertical-align: top;'><span @if($grade3 == 0)style="visibility:hidden;"@endif>{!!PermanentRecord::elemGradeTemp($idno,"Grade 3")!!}</span></td>
                <td width="2%" style="min-height: 800px;vertical-align: top;">&nbsp;</td>
                <td width="49%" style='vertical-align: top;'><span @if($grade4 == 0)style="visibility:hidden;"@endif>{!!PermanentRecord::elemGradeTemp($idno,"Grade 4")!!}</span></td>
            </tr>
        </table>
    </div>
    
    <div width="100%">
        <table width="100%" cellspacing="3">
            <tr style="min-height: 800px">
                <td width="49%" style='vertical-align: top;'><span @if($grade5 == 0)style="visibility:hidden;"@endif>{!!PermanentRecord::elemGradeTemp($idno,"Grade 5")!!}</span></td>
                <td width="2%" style='vertical-align: top;'></td>
                <td width="49%" style='vertical-align: top;'><span @if($grade6 == 0)style="visibility:hidden;"@endif>{!!PermanentRecord::elemGradeTemp($idno,"Grade 6")!!}</span></td>
            </tr>
        </table>
    </div>
    
    <div width="100%" style="position:fixed;bottom:450px">
        <h4 style="text-align: center">CERTIFICATE OF TRANSFER</h4>
        <p style='font-size:11pt;font-weight: 500;font-family:initial'><b><i>TO WHOM IT MAY CONCERN:</i></b></p>
        <p style="text-indent: 100px;font-size:11pt;font-weight: 500;font-family:initial"><b><i>
            This is to certify that this is a True Record of the Elementary School Permanent Record of <br>
	    <div style="position:absolute;left:-80px;text-align:left;">{{strtoupper($student->lastname)}}, {{strtoupper($student->firstname)}} {{substr(strtoupper($student->middlename),0,1)}}</div>_______________________________________. He is eligible for transfer and admission to ___________________.
	    He has no financial or Property Responsibility in this school as of this date of issuance.
	</i></b>
        </p>

            <table width="100%" style="font-size:11pt;margin-top:50px">
                <tr>
                    <td></td>
                    <td width='30%'></td>
                    <td style='text-align: center;'>
                        <b>Ms. Violeta F. Roxas</b><br>
                        <span style="font-size:9pt">REGISTRAR</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 30px"></td>
                </tr>
                <tr>
                    <td style="padding-bottom:10px">Verified by:</td>
                    <td></td>
                    <td style="padding-bottom:10px">Prepared by:</td>
                </tr>
                <tr style="text-align: center;vertical-align: top;">
                    <td>
                        <b>Ms. Danica Shane W.Vila</b><br>
                        <span style="font-size:9pt">Office Assistant</span>
                    </td>
                    <td></td>
                    <td>
                        <b>Ms. Bernadette B. Quejada</b><br>
                        <span style="font-size:9pt">GS-Records Assistant<br>
			Date: {{date('d-M-y')}}
                        </span>
                    </td>
                </tr>
            </table>
	<p style="font-size:9pt">
		COPY OF THIS RECORD IS SENT TO THE PRINCIPAL OF ________________________________ON _____________________<br>
		<span style="padding-left:390px">(School)</span><span style="padding-left:150px">(Date)</span> 
	</p>

    </div>
</body>
</html>

