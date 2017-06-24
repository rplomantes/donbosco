<!DOCTYPE html>
<?php
use App\Http\Controllers\Registrar\GradeController;
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="John Vincent Villanueva">
        <meta poweredby = "Nephila Web Technology, Inc">
        
        <style>
            html{
               margin-left:38.88px;
               margin-right:38.88px; 
            }
           .grades table tr td,.grades table thead tr th,#back table tr td,#back table thead tr th{
            font-size:10pt;
           }
           .greyed{
                background-color: rgba(201, 201, 201, 0.79) !important;
                -webkit-print-color-adjust: exact;
           }  
        </style>
    </head>
    <body>
        <div id="header">
            <table width="100%">
            <tr>
            <td style="padding-left: 0px;text-align: center;">
            <span style="font-size:12pt;font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span>
            </td>
            </tr>
            <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">Chino Roces Ave., Makati City </td></tr>
            <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">PAASCU Accredited</td></tr>
            <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">School Year {{$sy}} - {{intval($sy)+1}}</td></tr>
            <tr><td style="font-size:9pt;padding-left: 0px;">&nbsp; </td></tr>
            <tr><td><span style="font-size:5px"></td></tr>
            <tr>
            <td colspan="2" style="padding-left: 0px;">
            <div style="text-align: center;font-size:11pt;"><b>STUDENT PROGRESS REPORT CARD</b></div>
            <div style="text-align: center;font-size:11pt;"><b>ELEMENTARY DEPARTMENT</b></div>
            <br>
            </td>
            </tr>
            <tr><td style="font-size:3px"><br></td></tr>
            </table>
            <img src="{{asset('images/DBTI.png')}}"  style="position:absolute;width:108px;top:0px;">
            
            <table width="100%">
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td width="15%">Name:</td>
                    <td width="50%">{{$name}}</td>
                    <td width="15%">Student No:</td>
                    <td width="20%">{{$idno}}</td>
                </tr>
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td>Gr. and Sec:</td>
                    <td>{{$level}} - {{$section}}</td>
                    <td>Class No:</td>
                    <td>{{$idno}}</td>
                </tr>
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td>Adviser:</td>
                    <td>{{$adviser}}</td>
                    <td>LRN:</td>
                    <td>{{$lrn}}</td>
                </tr>
                <tr>
                    <td style="font-size:10pt;padding-left: 0px;font-weight: bold">
                        <b>Age:</b>
                    </td>
                    <td style="font-size:10pt;padding-left: 0px;font-weight: bold">
                        {{$totalage}}
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        </div>
        <div class="grades" style="page-break-after: always;">
            <table cellpadding="1" cellspacing="0" border="1" width="100%">
                <thead>
                    <tr>
                    <th width="38%">SUBJECTS</th>
                    <th width="10%">1</th>
                    <th width="10%">2</th>
                    <th width="10%">3</th>
                    <th width="10%">4</th>
                    <th width="12%">FINAL RATING</th>
                    <th width="20%">REMARKS</th>
                    </tr>
                </thead>
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 0)
                        <tr>
                            <td>{{$grade->subjectname}}</td>
                            <td align="center">{{round($grade->first_grading)}}</td>
                            <td align="center">{{round($grade->second_grading)}}</td>
                            <td align="center">{{round($grade->third_grading)}}</td>
                            <td align="center">{{round($grade->fourth_grading)}}</td>
                            <td align="center">{{round($grade->final_grade)}}</td>
                            <td align="center">{{$grade->remarks}}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td align="right"><b>GENERAL AVERAGE</b></td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),1,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),2,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),3,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),4,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0   ),5,$grades,$level)}}</td>
                    <td></td>             
                </tr>
            </table>
            <br>
            <table class="greyed" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;">
                <tr style="font-weight:bold;">
                    <td width="36%" class="descriptors">
                        DESCRIPTORS
                    </td>
                    <td width="32%" class="scale">
                        GRADING SCALE
                    </td>            
                    <td width="32%" class="remarks">
                        REMARKS
                    </td>                        
                </tr>
                <tr>
                    <td>Outstanding</td><td>90 - 100</td><td>Passed</td>
                </tr>
                <tr>
                    <td>Very Satisfactory</td><td>85 - 89</td><td>Passed</td>
                </tr>
                <tr>
                    <td>Satisfactory</td><td>80 - 84</td><td>Passed</td>
                </tr>
                <tr>
                    <td>Fairly Satisfactory</td><td>75 - 79</td><td>Passed</td>
                </tr>
                <tr>
                    <td>Did Not Meet Expectations</td><td>Below 75</td><td>Failed</td>
                </tr>
            </table>
            <br>
            <table class="greyed" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                <tr>
                    <td style="font-weight: bold">
                        CHRISTIAN LIVING DESCRIPTORS
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="50%" style="font-weight: bold">
                        LEVEL OF "FRIENDSHIP WITH JESUS"
                    </td>
                    <td style="font-weight: bold">
                        GRADING SCALE
                    </td>
                </tr>
                <tr>
                    <td>Best Friend of Jesus</td>
                    <td>95 - 100</td>
                </tr>
                <tr>
                    <td>Loyal Friend of Jesus</td>
                    <td>89 - 94</td>
                </tr>     
                <tr>
                    <td>Trustworthy Friend of Jesus</td>
                    <td>83 - 88</td>
                </tr>
                <tr>
                    <td>Good Friend of Jesus</td>
                    <td>77 - 82</td>
                </tr>
                <tr>
                    <td>Common Friend of Jesus</td>
                    <td>76 and Below</td>
                </tr>
            </table>
        </div>
        <div id="back">
            <table cellpadding="1" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th width="35%">SUBJECTS</th>
                    <th width="8%">Points</th>
                    <th width="8%">1</th>
                    <th width="8%">2</th>
                    <th width="8%">3</th>
                    <th width="8%">4</th>
                    <th width="15%"></th>
                    </tr>
                </thead>
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                        <tr>
                            <td>{{$grade->subjectname}}</td>
                            <td align="center">{{round($grade->points)}}</td>
                            <td align="center">{{round($grade->first_grading)}}</td>
                            <td align="center">{{round($grade->second_grading)}}</td>
                            <td align="center">{{round($grade->third_grading)}}</td>
                            <td align="center">{{round($grade->fourth_grading)}}</td>
                            <td align="center">FINAL GRADE</td>
                        </tr>
                    @endif
                @endforeach
                <tr style="font-weight: bold">
                    <td align="right">CONDUCT GRADE</td>
                    <td align="center">100</td>
                    <td align="center">{{GradeController::conductQuarterAve(3,2,$grades)}}</td>
                    <td align="center">{{GradeController::conductQuarterAve(3,3,$grades)}}</td>
                    <td align="center">{{GradeController::conductQuarterAve(3,4,$grades)}}</td>
                    <td align="center">{{GradeController::conductQuarterAve(3,4,$grades)}}</td>
                    <td align="center">{{GradeController::conductTotalAve($grades,0)}}</td>
                    <td></td>             
                </tr>
            </table>
        </div>
    </body>
</html>