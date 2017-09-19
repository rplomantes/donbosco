<?php 
use App\Http\Controllers\Registrar\PermanentRecord;
use App\Http\Controllers\Registrar\Helper as Registrarhelper;

$info = Registrarhelper::info($idno);
?>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta author="John Vincent Villanueva">
    <meta poweredby = "Nephila Web Technology, Inc">
    
    <style>
        body{
            margin-left: -20px;
            margin-right: -20px;
            margin-top: -40px;
            font-family: dejavu sans;
            
        }
        .underscore{
            border-bottom: 1px solid;
        }
        
    </style>
</head>
<body>
    <div width="100%" style="position:absolute;top:10px;left:0pt;">
    @if($header == 1)
    <table width="100%">
        <tr><td style="padding-left: 0px;text-align: center;"><span style="font-size:11pt;font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span></td></tr>
        <tr><td style="font-size:8pt;text-align: center;">Chino Roces Ave., Makati City 1200</td></tr>
        <tr><td style="font-size:8pt;text-align: center;">Tel. No. (02) 892.0101 to 08; Fax (02) 893.84.67</td></tr>
        <tr><td style="font-size:8pt;text-align: center;"><span style="border-bottom: 1px solid">www.donboscomakati.edu.ph</span></td></tr>
        <tr><td style="font-size:8pt;text-align: center;">PAASCU Accredited</td></tr>
        <tr><td style="font-size:8pt;padding-left: 0px;padding-top: 8pt;"></td></tr>
        <tr>
            <td colspan="1" style="padding-left: 0px;">
                <div style="text-align: center;font-size:10pt;"><b>JUNIOR HIGH SCHOOL PERMANENT RECORD</b></div>
                <div style="text-align: center;font-size:9pt;"><b>(FORM 137-OFFICIAL TRANSCRIPT OF RECORDS)</b></div>
            </td>
        </tr>
    
    </table>
    <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png"  style="position:absolute;width:108px;height:auto;top:0px;left:80px;">
    <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/boscorale.png"  style="position:absolute;width:100px;height:auto;top:0px;right:80px;">
    
    <table width="100%" style="font-size: 9pt" cellspacing="2" >
        <tr>
            <td width="11.5%">Name:</td>
            <td width="34.5%" class="underscore">{{$info->name}}</td>
            <td width="4%" style="padding-top: 9pt;"></td>
            <td width="10%">Gender:</td>
            <td width="11%" class="underscore">{{$info->gender}}</td>
            <td width="2%" style="padding-top: 9pt;"></td>
            <td width="11%" style="font-size: 8.5pt">Date of Birth:</td>
            <td width="17%" class="underscore">{{$info->age}}</td>
        </tr>
        <tr>
            <td style="font-size: 8.5pt">Place of Birth:</td>
            <td class="underscore">{{$info->birthPlace}}</td>
            <td style="padding-top: 9pt;"></td>
            <td style="font-size: 8.5pt">Student No.:</td>
            <td class="underscore">{{$info->idno}}</td>
            <td style="padding-top: 9pt;"></td>
            <td>LRN:</td>
            <td class="underscore">{{$info->lrn}}</td>
        </tr>

        <tr style="vertical-align: top">
            <td>Father:</td>
            <td class="underscore">{{$info->fname}}</td>
            <td style="padding-top: 9pt;"></td>
            <td>Address:</td>
            <td colspan="4" rowspan="2" style="font-size:8.5pt;vertical-align: bottom">
                <div class="underscore">
                    {{$info->address1}}
                    @if($info->address2 != "")
                    ,{{$info->address2}}
                    @endif
                    @if($info->address3 != "")
                    , {{$info->address3}}
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>Mother:</td>
            <td class="underscore">{{$info->mname}}</td>
            <td style="padding-top: 9pt;"></td>
            <td></td>
        </tr>
    </table>
    
    <table width="100%" style="font-size: 9pt">
        <tr>
            <td width="26%">Elementary Education Completed:</td>
            <td width="22%" class="underscore">{{$oldrec[0]}}</td>
            <td width="9%">Schoolyear:</td>
            <td width="16%" class="underscore">{{$oldrec[1]}} - {{$oldrec[1]+1}}</td>
            <td width="7%">Average:</td>
            <td width="10%" class="underscore">{{$oldrec[2]}}</td>
        </tr>
    </table>
    @endif
    </div>
    
    <div width="100%" style="position:absolute;top:300px;left:0pt;">
        <table cellspacing="3">
            <tr>
                <td width="49%">@if($grade7 == 1){!!PermanentRecord::hsGradeTemp($idno,"Grade 7")!!}@endif</td>
                <td width="2px"></td>
                <td width="49%">@if($grade9 == 1){!!PermanentRecord::hsGradeTemp($idno,"Grade 9")!!}@endif</td>
            </tr>
        </table>
    </div>
    <div width="100%" style="position:absolute;top:810px;left:0pt;">
        <table cellspacing="3">
            <tr>
                <td width="49%">@if($grade8 == 1){!!PermanentRecord::hsGradeTemp($idno,"Grade 8")!!}@endif</td>
                <td width="2px"></td>
                <td width="49%">@if($grade10 == 1){!!PermanentRecord::hsGradeTemp($idno,"Grade 10")!!}@endif</td>
            </tr>
        </table>
    </div>
    

</body>
</html>