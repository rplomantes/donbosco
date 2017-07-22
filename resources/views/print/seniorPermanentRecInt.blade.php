<!DOCTYPE html>
<?php
use App\Http\Controllers\Registrar\GradeController;
$c1 = 0;
$c2 = 0;
$a1 = 0;
$a2 = 0;

foreach($grades as $grade){
    if($grade->semester == 1 && $grade->subjecttype == 5){
        $c1++;
    }elseif($grade->semester == 2 && $grade->subjecttype == 5){
        $c2++;
    }elseif($grade->semester == 1 && $grade->subjecttype == 6){
        $a1++;
    }elseif($grade->semester == 2 && $grade->subjecttype == 6){
        $a2++;
    }
    
    if($c1 > $c2){
        $corerows = $c1;
    }else{
        $corerows = $c2;
    }
    
    if($a1 > $a2){
        $approws = $a1;
    }else{
        $approws = $a2;
    }
}
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
            font-family: dejavu sans;
            
        }
        .underscore{
            border-bottom: 1px solid;
        }
        
    </style>
</head>
<body>
    <table width="100%">
    <tr>
    <td style="padding-left: 0px;text-align: center;"><span style="font-size:11pt;font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span></td>
    </tr>
    <tr><td style="font-size:8pt;text-align: center;">Chino Roces Ave., Makati City 1200</td></tr>
    <tr><td style="font-size:8pt;text-align: center;">Tel. No. (02) 892.0101 to 08; Fax (02) 893.84.67</td></tr>
    <tr><td style="font-size:8pt;text-align: center;"><span style="border-bottom: 1px solid">www.donboscomakati.edu.ph</span></td></tr>
    <tr><td style="font-size:8pt;text-align: center;">PAASCU Accredited</td></tr>
    <tr><td style="font-size:8pt;padding-left: 0px;padding-top: 8pt;"></td></tr>
    <tr>
    <td colspan="1" style="padding-left: 0px;">
    <div style="text-align: center;font-size:10pt;"><b>SENIOR HIGH SCHOOL PERMANENT RECORD</b></div>
    <div style="text-align: center;font-size:9pt;"><b>(FORM 137-OFFICIAL TRANSCRIPT OF RECORDS)</b></div>
    <br>
    </td>
    </tr>
    
    </table>
    <img src="{{asset('images/DBTI.png')}}"  style="position:absolute;width:108px;height:auto;top:0px;left:80px;">
    <img src="{{asset('images/boscorale.png')}}"  style="position:absolute;width:100px;height:auto;top:0px;right:80px;">
    
    <table width="100%" style="font-size: 9pt" cellspacing="2" >
        <tr>
            <td width="11%">Name:</td>
            <td width="35%" class="underscore">{{$info->name}}</td>
            <td width="4%" style="padding-top: 9pt;"></td>
            <td width="10%">Gender:</td>
            <td width="11%" class="underscore">{{$info->gender}}</td>
            <td width="2%" style="padding-top: 9pt;"></td>
            <td width="11%">Date of Birth:</td>
            <td width="17%" class="underscore">{{$info->birthDate}}</td>
        </tr>
        <tr>
            <td>Place of Birth:</td>
            <td class="underscore">{{$info->birthPlace}}</td>
            <td style="padding-top: 9pt;"></td>
            <td>Student No.:</td>
            <td class="underscore">{{$idno}}</td>
            <td style="padding-top: 9pt;"></td>
            <td>LRN:</td>
            <td class="underscore">{{$info->lrn}}</td>
        </tr>

        <tr style="vertical-align: top">
            <td>Father:</td>
            <td class="underscore">{{$info->fName}}</td>
            <td style="padding-top: 9pt;"></td>
            <td>Address:</td>
            <td colspan="4" rowspan="2" style="font-size:8.5pt;"><div class="underscore">
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
            <td class="underscore">{{$info->mName}}</td>
            <td style="padding-top: 9pt;"></td>
            <td></td>
        </tr>
    </table>
    <br>
    <table width="100%" style="font-size: 9pt">
        <tr>
            <td width="200px">Junior High School Completed:</td>
            <td class="underscore">{{$jhschool}}</td>
            
        </tr>
    </table>
    <table width="100%" style="font-size: 9pt">
        <tr>
            <td width="11%">School Year:</td>
            <td width="30%" class="underscore">
                @if($jhsSy != "")
                {{$jhsSy}} - {{$jhsSy+1}}
                @endif
            </td>
            <td width="5%" style="padding-top: 9pt;"></td>
            <td width="15%">General Average:</td>
            <td width="39%" class="underscore">
                @if($jhsaverage != 0)
                {{round($jhsaverage,2)}}
                @endif
            </td>
        </tr>
    </table>
    <br>
    <table width="75%" style="font-size: 9pt">
        <tr>
            <td width="45%">{{strtoupper($level)}} - {{$section}}</td>
            <td width="30%" style="text-align: right">School ID</td>
            <td width="25%" style="text-align: center">School Year</td>
        </tr>
        <tr>
            <td width="45%">School: DON BOSCO-MAKATI</td>
            <td width="30%" style="text-align: right">1403014</td>
            <td width="25%" style="text-align: center">{{$sy}} - {{$sy+1}}</td>
        </tr>
    </table>
    <br>
    <div style="font-weight: bold;font-size: 9pt;">ACADEMIC TRACK: 
    @if($strand == "ABM")
    Accountancy, Business and Management (ABM)
    @elseif($strand == "STEM")
    Science, Technology, Engineering and Mathematics (STEM)
    @endif
    </div>
    <br>
    <table width="100%" cellspacing="0">
        <tr style="font-size: 9pt">
            <td width="49%">
                <b>FIRST SEMESTER</b>
            </td>
            <td width="2%" style="padding-top: 9pt;"></td>
            <td width="49%">
                <b>SECOND SEMESTER</b>
            </td>
        </tr>
        <tr><td colspan="3" ></td></tr>
        <tr>
            <td valign="top">
                <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr style="text-align: center">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">FIRST<br>QUARTER</td>
                        <td style="font-size: 7pt">SECOND<br>QUARTER</td>
                        <td style="font-size: 7pt">FINAL<br>GRADE</td>
                    </tr>
                    <tr>
                        <td>CONDUCT GRADE</td>
                        <td style="font-size: 7pt;text-align: center;">{{round(GradeController::conductQuarterAve(3,1,$grades),0)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{round(GradeController::conductQuarterAve(3,2,$grades),0)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{round(GradeController::conductTotalAve($grades,1),0)}}</td>
                    </tr>
                    <tr ><td colspan="4" style="padding-left: 45px;font-size: 8pt"><b>CORE SUBJECTS</b></td></tr>
                    <?php 
                    $core1 = 1;
                    $core2 = 1;
                    $app1 = 1;
                    $app2 = 1;
                    ?>
                    @foreach($grades as $grade)
                        @if($grade->semester == 1 && $grade->subjecttype == 5)
                        <tr id="core1{{$core1}}">
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        <?php $core1++;?>
                        @endif
                    @endforeach
                    @if($c1<$c2)
                        <?php $rows = $c2 - $c1; ?>
                        @for ($i = 0; $i < $rows; $i++)
                            <tr id="core1{{$core1}}">
                                <td style="padding-top: 7pt;height:7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                            </tr>
                            <?php $core1++;?>
                        @endfor
                    @endif
                    <tr ><td colspan="4" style="padding-left: 45px;font-size: 8pt"><b>APPLIED AND SPECIALIZED SUBJECTS</b></td></tr>
                    @foreach($grades as $grade)
                        @if($grade->semester == 1 && $grade->subjecttype == 6)
                        <tr id="app1{{$app1}}">
                            
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->first_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->second_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        <?php $app1++;?>
                        @endif
                    @endforeach
                    @if($a1<$a2)
                        <?php $rows = $a2 - $a1; ?>
                        @for ($i = 0; $i < $rows; $i++)
                            <tr  id="app1{{$app1}}">
                                <td style="padding-top: 7pt;height:7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                            </tr>
                        @endfor
                    @endif
                    <tr ><td colspan="4" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                    <tr style='font-weight: bold'>
                        <td>GENERAL AVERAGE FOR THE SEMESTER</td>
                        <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(1),1,$grades,$level)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(1),2,$grades,$level)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(1),5,$grades,$level)}}</td>
                    </tr>
                    <tr>
                        <td>DAYS OF SCHOOL</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1daya[0] + $q1dayp[0]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1daya[1] + $q1dayp[1]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{($q1daya[1] + $q1dayp[1])+($q1daya[0] + $q1dayp[0])}}</td>
                    </tr>
                    <tr>
                        <td>DAYS ABSENT</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1daya[0]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1daya[1]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1daya[0]+$q1daya[1]}}</td>
                    </tr>
                    <tr>
                        <td>DAYS TARDY</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1dayt[0]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1dayt[1]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q1dayt[0]+$q1dayt[1]}}</td>
                    </tr>
                </table>
            </td>
            <td width="2%" style="padding-top:8pt"></td>
            <td valign="top">
                <table style="font-size: 8pt" width="100%" border="1" cellspacing="0">
                    <tr style="text-align: center">
                        <td width="60%"><b>SUBJECTS</b></td>
                        <td style="font-size: 7pt">FIRST<br>QUARTER</td>
                        <td style="font-size: 7pt">SECOND<br>QUARTER</td>
                        <td style="font-size: 7pt">FINAL<br>GRADE</td>
                    </tr>
                    <tr>
                        <td>CONDUCT GRADE</td>
                        <td style="font-size: 7pt;text-align: center;">{{round(GradeController::conductQuarterAve(3,3,$grades),0)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{round(GradeController::conductQuarterAve(3,4,$grades),0)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{round(GradeController::conductTotalAve($grades,2),0)}}</td>
                    </tr>
                    <tr ><td colspan="4" style="padding-left: 45px;font-size: 8pt"><b>CORE SUBJECTS</b></td></tr>
                    @foreach($grades as $grade)
                        @if($grade->semester == 2 && $grade->subjecttype == 5)
                        <tr id="core2{{$core2}}">
                            <td>
                                {{strtoupper($grade->subjectname)}}
                            </td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        <?php $core2++; ?>
                        @endif
                    @endforeach
                    @if($c2<$c1)
                        <?php $rows = $c1 - $c2; ?>
                        @for ($i = 0; $i < $rows; $i++)
                            <tr id="core2{{$core2}}">
                                <td style="padding-top: 7pt;height:7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                            </tr>
                            <?php $core2++; ?>
                        @endfor
                    @endif
                    <tr ><td colspan="4" style="padding-left: 45px;font-size: 8pt"><b>APPLIED AND SPECIALIZED SUBJECTS</b></td></tr>
                    @foreach($grades as $grade)
                        @if($grade->semester == 2 && $grade->subjecttype == 6)
                        <tr  id="app2{{$app2}}">
                            <td>{{strtoupper($grade->subjectname)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->third_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->fourth_grading,0)}}</td>
                            <td style="font-size: 7pt;text-align: center;">{{round($grade->final_grade,0)}}</td>
                        </tr>
                        <?php $app2++; ?>
                        @endif
                    @endforeach
                    @if($a2<$a1)
                        <?php $rows = $a1 - $a2; ?>
                        @for ($i = 0; $i < $rows; $i++)
                            <tr id="app2{{$app2}}">
                                <td style="padding-top: 7pt;height:7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                                <td style="padding-top: 7pt;"></td>
                            </tr>
                            <?php $app2++; ?>
                        @endfor
                    @endif
                    <tr ><td colspan="4" style="padding-left: 45px;padding-top: 10pt;height:10pt;"></td></tr>
                    <tr style='font-weight: bold'>
                        <td>GENERAL AVERAGE FOR THE SEMESTER</td>
                        <td id="moom" style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(2),3,$grades,$level)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(2),4,$grades,$level)}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{GradeController::gradeQuarterAve(array(0,5,6),array(2),5,$grades,$level)}}</td>
                    </tr>
                    <tr>
                        <td>DAYS OF SCHOOL</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2daya[0] + $q2dayp[0]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2daya[1] + $q2dayp[1]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{($q2daya[0] + $q2dayp[0])+($q2daya[1] + $q2dayp[1])}}</td>
                    </tr>
                    <tr>
                        <td>DAYS ABSENT</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2daya[0]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2daya[1]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2daya[0]+$q2daya[1]}}</td>
                    </tr>
                    <tr>
                        <td>DAYS TARDY</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2dayt[0]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2dayt[1]}}</td>
                        <td style="font-size: 7pt;text-align: center;">{{$q2dayt[0]+$q2dayt[1]}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" cellspacing='0'>
        <tr>
            <td colspan="4" style="text-align: center;font-size: 9pt"><b>CERTIFICATE OF TRANSFER</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;font-size: 8pt"><b>This is to certify that this is a true record of <span style='border-bottom:1px solid;'>{{$info->name}}</span>.</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;padding-top: 10pt"></td>
        </tr>
        <tr style="text-align: left;font-size: 8pt;vertical-align: top">
            <td width='100px'>Prepared by:</td>
            <td><div style="width:40%;text-align: center">Mrs. Asuncion G. Torrefiel<br>HS Records Assistant</div></td>
            <td  width='200px'>
                This record is valid for:
                <div style='border-bottom: 1px solid;padding-top: 8pt;height:8pt'></div>
                <div style='border-bottom: 1px solid;padding-top: 8pt;height:8pt'></div>
            </td>
            <td width='100px' style="padding-left: 100px"></td>
        </tr>
        <tr style="text-align: left;font-size: 8pt;vertical-align: top">
            <td width='100px'>Verified by:</td>
            <td><div style="width:40%;text-align: center">Miss. Danica Shane W.Villa<br>Office Assistant</div></td>
        <td></td>
            <td width='100px' style="padding-top: 8pt"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;padding-top: 10pt"></td>
        </tr>
        <tr style="text-align: left;font-size: 8pt;vertical-align: top">
            <td colspan="2" style="font-size: 9pt;font-weight: bold"><div style="width:80%;text-align: center;font-size: 9pt;">Miss. Violeta F. Roxas<br>School Registrar</div></td>
            <td>
                NOT VALID WITHOUT SCHOOL SEAL<br>
                OR WITH ERASURE OR ALTERATION<br>
                <span style='font-size: 7pt'>Original Copy</span>
            </td>
            <td width='100px' style="padding-top: 7pt"></td>
        </tr>
    </table>
    <div>
<script type="text/javascript">
    
    @for($i = 1; $i < $corerows+1; $i++)
        var core1{{$i}} =document.getElementById('core1{{$i}}').offsetHeight;
        var core2{{$i}} =document.getElementById('core2{{$i}}').offsetHeight;
        
        if(core1{{$i}} > core2{{$i}}){
            document.getElementById('core2{{$i}}').style.height = core1{{$i}}+"px"; 
        }else if(core1{{$i}} < core2{{$i}}){
            document.getElementById('core1{{$i}}').style.height = core2{{$i}}+"px"; 
        }
    @endfor
    
    @for($i = 1; $i < $approws+1; $i++)
        var app1{{$i}} =document.getElementById('app1{{$i}}').offsetHeight;
        var app2{{$i}} =document.getElementById('app2{{$i}}').offsetHeight;
        
        if(app1{{$i}} > app2{{$i}}){
            document.getElementById('app2{{$i}}').style.height = app1{{$i}}+"px"; 
        }else if(app1{{$i}} < app2{{$i}}){
            document.getElementById('app1{{$i}}').style.height = app2{{$i}}+"px";
        }
        
    @endfor
        
</script>
    </div>
</body>
</html>
