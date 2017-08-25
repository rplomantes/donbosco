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
            <img src="{{asset('images/DBTI.png')}}"  style="position:absolute;width:108px;top:0px;left:20px;">
            
            <table width="100%">
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td width="15%">Name:</td>
                    <td width="50%">{{$name}}</td>
                    <td width="15%">Student No:</td>
                    <td width="20%">{{$idno}}</td>
                </tr>
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td>Gr. and Sec:</td>
                    <td>{{str_replace("Grade","",$level)}} - {{$section}}</td>
                    <td>Class No:</td>
                    <td>{{$class_no}}</td>
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
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),array(0),1,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),array(0),2,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),array(0),3,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),array(0),4,$grades,$level)}}</td>
                    <td align="center">{{GradeController::gradeQuarterAve(array(0),array(0),5,$grades,$level)}}</td>
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
            <?php $rowspan=0;?>
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                    <?php $rowspan++;?>
                    @endif
                @endforeach
            <table cellpadding="1" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th width="30%" style="border: 1.5px solid">SUBJECTS</th>
                    <th width="10%" style="border: 1.5px solid">Points</th>
                    <th width="10%" style="border: 1.5px solid">1</th>
                    <th width="10%" style="border: 1.5px solid">2</th>
                    <th width="10%" style="border: 1.5px solid">3</th>
                    <th width="10%" style="border: 1.5px solid">4</th>
                    <th width="20%" rowspan="{{$rowspan}}"></th>
                    </tr>
                </thead>
                {{--*/$counter = 0/*--}}
                {{--*/$length = $rowspan/*--}}
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                    {{--*/$counter ++/*--}} 
                    
                        <tr>
                            <td style="border: 1.5px solid">{{$grade->subjectname}}</td>
                            <td align="center" style="border: 1.5px solid">{{round($grade->points)}}</td>
                            <td align="center" style="border: 1.5px solid">{{round($grade->first_grading)}}</td>
                            <td align="center" style="border: 1.5px solid">{{round($grade->second_grading)}}</td>
                            <td align="center" style="border: 1.5px solid">{{round($grade->third_grading)}}</td>
                            <td align="center" style="border: 1.5px solid">{{round($grade->fourth_grading)}}</td>
                            @if($length == $counter)
                            <td style="border: 1.5px solid;border-right: 1px solid;"><b>FINAL GRADE</b></td>
                            @endif
                            
                        </tr>
                    @endif
                @endforeach
                <tr style="font-weight: bold">
                    <td align="center" style="border: 1.5px solid">CONDUCT GRADE</td>
                    <td align="center" style="border: 1.5px solid">100</td>
                    <td align="center" style="border: 1.5px solid">{{GradeController::conductQuarterAve(3,1,$grades)}}</td>
                    <td align="center" style="border: 1.5px solid">{{GradeController::conductQuarterAve(3,2,$grades)}}</td>
                    <td align="center" style="border: 1.5px solid">{{GradeController::conductQuarterAve(3,3,$grades)}}</td>
                    <td align="center" style="border: 1.5px solid">{{GradeController::conductQuarterAve(3,4,$grades)}}</td>
                    <td align="center" style="border: 1.5px solid">{{GradeController::conductTotalAve($grades,0)}}</td>
                </tr>
            </table>
                <br>
            <table border='1' cellpadding='0' cellspacing='0' width="100%" style="text-align: center;font-size:11px;">
                <tr style="font-size:12px;">
                    <td style="padding-bottom:5px;padding-top:5px">
                        <b>ATTENDANCE</b>
                    </td>

                    <td>Jun</td><td>Jul</td><td>Aug</td><td>Sept</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td>
                    <td>TOTAL</td>
                </tr>
                <?php
                $jun = 0;
                $jul = 0;
                $aug = 0;
                $sept = 0;
                $oct = 0;
                $nov = 0;
                $dece = 0;
                $jan = 0;
                $feb = 0;
                $mar = 0;
                ?>
                @foreach($ctr_attendances as $ctr_attendance)
                <tr>
                <td style="text-align: left">Days of School</td>
                <td>@if($ctr_attendance->jun != 0){{round($ctr_attendance->jun,1)}}@endif</td>
                <td>@if($ctr_attendance->jul != 0){{round($ctr_attendance->jul,1)}}@endif</td>
                <td>@if($ctr_attendance->aug != 0){{round($ctr_attendance->aug,1)}}@endif</td>
                <td>@if($ctr_attendance->sept != 0){{round($ctr_attendance->sept,1)}}@endif</td>
                <td>@if($ctr_attendance->oct != 0){{round($ctr_attendance->oct,1)}}@endif</td>
                <td>@if($ctr_attendance->nov != 0){{round($ctr_attendance->nov,1)}}@endif</td>
                <td>@if($ctr_attendance->dece != 0){{round($ctr_attendance->dece,1)}}@endif</td>
                <td>@if($ctr_attendance->jan != 0){{round($ctr_attendance->jan,1)}}@endif</td>
                <td>@if($ctr_attendance->feb != 0){{round($ctr_attendance->feb,1)}}@endif</td>
                <td>@if($ctr_attendance->mar != 0){{round($ctr_attendance->mar,1)}}@endif</td>
                <td>@if($ctr_attendance->mar != 0){{round($ctr_attendance->total,1)}}@endif</td>
                </tr>
                
                <?php
                $jun = $ctr_attendance->jun;
                $jul = $ctr_attendance->jul;
                $aug = $ctr_attendance->aug;
                $sept = $ctr_attendance->sept;
                $oct = $ctr_attendance->oct;
                $nov = $ctr_attendance->nov;
                $dece = $ctr_attendance->dece;
                $jan = $ctr_attendance->jan;
                $feb = $ctr_attendance->feb;
                $mar = $ctr_attendance->mar;
                ?>
                @endforeach
                @foreach($attendances as $attendance)
                <tr>
                <td style="text-align: left">{{$attendance->attendanceName}}</td>
                <td>@if($jun != 0){{round($attendance->jun,1)}}@endif</td>
                <td>@if($jul != 0){{round($attendance->jul,1)}}@endif</td>
                <td>@if($aug != 0){{round($attendance->aug,1)}}@endif</td>
                <td>@if($sept != 0){{round($attendance->sept,1)}}@endif</td>
                <td>@if($oct != 0){{round($attendance->oct,1)}}@endif</td>
                <td>@if($nov != 0){{round($attendance->nov,1)}}@endif</td>
                <td>@if($dece != 0){{round($attendance->dece,1)}}@endif</td>
                <td>@if($jan != 0){{round($attendance->jan,1)}}@endif</td>
                <td>@if($feb != 0){{round($attendance->feb,1)}}@endif</td>
                <td>@if($mar != 0){{round($attendance->mar,1)}}@endif</td>
                <td>@if($mar != 0){{round($attendance->total,1)}}@endif</td>
                </tr>
                @endforeach
            </table>
            <br>
            <div>
                <span style="font-size:10pt">Dear Parent:</span>
                <p style="font-size:10pt;text-indent: 20px">This report card shows the ability and progress your child has made in different learning areas as well as his/her core values.</p>
                <p style="font-size:10pt;text-indent: 20px">The school welcomes you should you desire to know more about your child's progress.</p>
                <br>
                <table width="100%">
                    <tr>
                        <td width="62%"></td>
                        <td width="38%">
                            <div width="100%">
                            @if($adviser != null)
                                <div style="font-size:10pt;width:200px;text-align: center;float:right;border-top: 1px solid">
                                    <span>{{$adviser}}</span>
                                    <br><span>Class Adviser</span>
                                </div>
                            @endif
                            </div>
                        </td>
                    </tr>

                </table>
            </div>
            <br>
            
            <table width="100%" class="cert" >
                <tr>
                    <td class="print-size"  width="50%">
                        <b>Certificate of Eligibility for Promotion</b>
                    </td>
                    <td rowspan="8" width="9%">

                    </td>
                    <td class="print-size" style="text-align: justify;font-weight: bold">
                        Cancellation of Eligibility to Transfer
                    </td>                                                    
                </tr>
                <tr>
                    <td class="print-size" >
                        The student is eligible for transfer and
                    </td>
                    <td class="print-size" >
                        Admitted in:__________________
                    </td>                                                    
                </tr>
                <tr>

                    <td class="print-size" >admission to:___________________</td>

                    <td class="print-size" >Grade:_________ Date:_________</td>
                </tr>                       
                <tr>
                    
                     <td class="print-size" >Date of Issue:<div style="display: inline-block;border-bottom: 1px solid;height: 16px;width: 145px;text-align: center;"><i>April 11, 2017</i></div></td>
                    
                    <td></td>                                                    
                </tr>
                <tr>
                    <td colspan="2"><br><br><br></td>                                                    
                </tr>
                                                                <tr style="text-align: center">
                    <td class="print-size"></td>
                    <td class="print-size"><div style="border-bottom: 1px solid;width: 80%;margin-left: auto;margin-right: auto;height:25px"><img src="{{asset('images/HS_PRINCIPAL.png')}}"  style="display: inline-block;width:180px"></div></td> 
                </tr>
                <tr style="text-align: center;">
                    <td class="print-size" >

                    </td>
                    <td class="print-size" >Ms. Violeta F. Roxas</td>
                </tr>
                <tr style="text-align: center">
                    <td class="print-size" ></td>
                    <td class="print-size" ><b>High School - Principal</b></td>
                </tr>
            </table>
            <div style="position: absolute;bottom:-10px;right: -10px;"><b>{{$idno}}</b></div>
        </div>
    </body>
</html>