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
    <div width="100%" @if($header != 1)style="visibility:hidden;@endif">
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
                <div>{{$oldrec[1]}} - {{$oldrec[1]+1}}</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Date Entered</div>
                <div>{{$oldrec[4]}}</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Date Left</div>
                <div>{{$oldrec[5]}}</div>
            </td>
            <td >
                <div style="font-weight: bold">School Attended</div>
                <div>{{$oldrec[0]}}</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Grade</div>
                <div>{{$oldrec[6]}}</div>
            </td>
            <td width="10%">
                <div style="font-weight: bold">Days Present</div>
                <div>{{$oldrec[3]}}</div>
            </td>
            <td width="9%">
                <div style="font-weight: bold">Final Rating</div>
                <div>{{$oldrec[2]}}</div>
            </td>
            <td width="9%">
                <div style="font-weight: bold">Prom./Ret.</div>
                <div> {{$oldrec[7]}}</div>
            </td>
        </tr>
    </table>
    </div>
    
    <div width="100%">
        <table width="100%" cellspacing="1">
            <tr>
                <td width="49.5%">@if($grade1 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 1")!!}@endif</td>
                <td width="1%"></td>
                <td width="49.5%">@if($grade2 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 2")!!}@endif</td>
            </tr>
        </table>
    </div>
    

    <div width="100%">
        <table width="100%" cellspacing="1">
            <tr>
                <td width="49.5%">@if($grade3 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 3")!!}@endif</td>
                <td width="1%"></td>
                <td width="49.5%">@if($grade4 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 4")!!}@endif</td>
            </tr>
        </table>
    </div>
    
    <div width="100%">
        <table width="100%" cellspacing="1">
            <tr>
                <td width="49.5%">@if($grade5 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 5")!!}@endif</td>
                <td width="1%"></td>
                <td width="49.5%">@if($grade6 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 6")!!}@endif</td>
            </tr>
        </table>
    </div>
</body>
</html>