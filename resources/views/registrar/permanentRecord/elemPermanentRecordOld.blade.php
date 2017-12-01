<?php 
use App\Http\Controllers\Registrar\PermanentRecord;
use App\Http\Controllers\Registrar\Helper as Registrarhelper;
use App\Http\Controllers\Registrar\AttendanceController;

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
        html{
            margin-left: 0px;
            margin-right: 0px;
        }
        body{
            margin-left: 15px;
            margin-right: 15px;
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
    <div style="page-break-after:always;">
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
    sas
    <div width="100%" style='position: fixed;top:570px;'>
        <table width="100%" cellspacing="1">
            <tr>
                <td width="47%" style='vertical-align: top;'>@if($grade1 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 1")!!}@endif</td>
                <td width="4%" style='vertical-align: top;'></td>
                <td width="49%"style='vertical-align: top;'>@if($grade2 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 2")!!}@endif</td>
            </tr>
        </table>
    </div>
    

    <div width="100%" style='position: fixed;top:570px;'>
        <table width="100%" cellspacing="1">
            <tr>
                <td width="47%" style='vertical-align: top;'>@if($grade3 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 3")!!}@endif</td>
                <td width="6%" style='vertical-align: top;'></td>
                <td width="47%"style='vertical-align: top;'>@if($grade4 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 4")!!}@endif</td>
            </tr>
        </table>
    </div>
    
    <div width="100%" style='position: fixed;top:920px;'>
        <table width="100%" cellspacing="1">
            <tr>
                <td width="47%" style='vertical-align: top;'>@if($grade5 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 5")!!}@endif</td>
                <td width="4%" style='vertical-align: top;'></td>
                <td width="49%" style='vertical-align: top;'>@if($grade6 == 1){!!PermanentRecord::elemGradeTempOld($idno,"Grade 6")!!}@endif</td>
            </tr>
        </table>
    </div>
    </div>
    <div style="margin-right:30px;margin-left: 5px;">
        <div style="position: relative;top: 696.6px">
            <div style="visibility: hidden;font-size: 9pt;margin-bottom:10px">ATTENDANCE RECORD</div>
            <table style="font-size: 8pt;text-align: center" cellspacing="0">
                <tr style="color: white">
                    <td width="33.5px">GRADE</td>
                    <td width="68px">NO. OF SCHOOL DAYS</td>
                    <td width="73px">NO. OF SCHOOL DAYS ABSENT</td>
                    <td width="106px">CAUSE</td>
                    <td width="69px">NO. OF TIMES TARDY</td>
                    <td width="94px">CAUSE</td>
                    <td width="71px">NO. OF SCHOOL DAYS PRESENT</td>
                </tr>
                <?php 
                $grade = 1;
                $grades = array($grade1,$grade2,$grade3,$grade4,$grade5,$grade6);
                $height = array(10.8,19.8,19,19.2,18.8,22.6);
                ?>
                @while($grade <= 6)
                <?php 
                $dayp = array();
                $daya = array();
                $dayt = array();
                for($i=1; $i < 5 ;$i++){
                    
                    $attendance  = AttendanceController::studentQuarterAttendance($idno,2016,$i,$grades[$grade-1]); 
                    $dayp [] = $attendance[0];
                    $daya [] = $attendance[1];
                    $dayt [] = $attendance[2];
                }
                ?>
                <tr  @if($grades[$grade-1] == 1) style="color: black" @else style="color: white" @endif>
                    <td height="{{$height[$grade-1]}}px"></td>
                    <td  height="{{$height[$grade-1]}}px">{{$dayp[0]+$daya[0]+$dayp[1]+$daya[1]+$dayp[2]+$daya[2]+$dayp[3]+$daya[3]}}</td>
                    <td>{{$daya[0]+$daya[1]+$daya[2]+$daya[3]}}</td>
                    <td></td>
                    <td>{{$dayt[0]+$dayt[1]+$dayt[2]+$dayt[3]}}</td>
                    <td></td>
                    <td>{{$dayp[0]+$dayp[1]+$dayp[2]+$dayp[3]}}</td>
                </tr>
                <?php $grade++; ?>
                @endwhile

            </table>
            <table width="100%" style="font-size:11pt;margin-top:50px">
                <tr>
                    <td>

                    </td>
                    <td width='50%'></td>
                    <td style='text-align: center;'>
                        <b>Ms. Violeta F. Roxas</b><br>
                        <span style="font-size:9pt">REGISTRAR</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="margin-top: 30px"></td>
                </tr>
                <tr>
                    <td style="padding-bottom:30px">Verified by:</td>
                    <td></td>
                    <td style="padding-bottom:30px">Prepared by:</td>
                </tr>
                <tr style="text-align: center;">
                    <td>
                        <b>Ms. Danica Shane W.Villa</b><br>
                        <span style="font-size:9pt">Office Assistant</span>
                    </td>
                    <td></td>
                    <td>
                        <b>Ms. Bernadette B. Quejada</b><br>
                        <span style="font-size:9pt">GS-Records Assistant
                        </span>
                    </td>
                </tr>
            </table>
        </div>    
    </div>
</body>
</html>

