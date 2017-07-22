<?php
use App\Http\Controllers\Registrar\GradeController;
?>
<html>
    <head>
        <script src="{{asset('/js/jquery.js')}}"></script>
        <style type='text/css'>
            
            .hide{
                display:none;
            }
           table tr td{
            font-size:10pt;
            padding-left: 5px;
            padding-right: 5px;
           }
           
           .body{
            font-family: calibri;
            margin-left: auto;
            margin-right: auto;
            width:16.5cm;
            padding-left: .2cm;
            padding-right: .2cm;
            }
            .greyed{
                background-color: rgba(201, 201, 201, 0.79) !important;;
                -webkit-print-color-adjust: exact; 
            }      
            .front{
                border: 1px solid;
                margin-left: -0.8cm;
                padding-left: .8cm;
                margin-right: -0.8cm;
                padding-right: .8cm;                
            }
            .back{
                border: 1px solid;
                margin-left: -0.8cm;
                padding-left: .8cm;
                margin-right: -0.8cm;
                padding-right: .8cm;                
            } 
        </style>    
       
        <style type="text/css" media="print">
            body{width:100%;}
            .front{
                border: none;
                margin-left: 0px;
                padding-left: 0px;
                margin-right: 0px;
                padding-right: 0px;                
            }
            .back{
                border: none;
                margin-left: 0px;
                padding-left: 0px;
                margin-right: 0px;
                padding-right: 0px;               
            }
           .body{
            font-family: calibri;
            margin-left: auto;
            margin-right: 0px;
            width:100%;
            padding-left: .5cm;
            padding-right: .5cm;            
            }            
            body{
                font-family: calibri;
            }
            .greyed{
                background-color: rgba(201, 201, 201, 0.79) !important;
                -webkit-print-color-adjust: exact; 
            }
        </style>
        <link href="{{ asset('/css/print.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        
        <div class="body" id="body">
        <div class="front" style="padding-top: 40px;">
            <div style="z-index: 3;position: relative;max-height: 0px;bottom:5px;right:-30px;">
                <img src="{{asset('images/DBTI.png')}}"  style=";width:140px;">
            </div>
        <table class="parent" width="100%" style="z-index: 1;margin-left: auto;margin-right: auto;margin-bottom: .8cm;">
            <thead>
            <tr>
                <td style="padding-left: 0px;">
                    <table class="head"  border="0" cellpadding="0" cellspacing="0" id="cardHeader" width='100%' style="min-height:140px">
                    <tr>
                        <td style="padding-left: 0px;text-align: center">
                            <span style="font-size:12pt; font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span>
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
                    <div style="text-align: center;font-size:11pt;"><b>JUNIOR HIGH SCHOOL</b></div>
                        </td>
                    </tr>
                    <tr><td style="font-size:2px"><br></td></tr>
                    </table>
                </td>
            </tr>
            </thead>
            <tr>
                <td style="padding-left: 0px;">
               
                    <table class="head" width="100%" border = '0' cellpacing="0" cellpadding = "0">
                        <tr>
                            <td width="16%" style="font-size:10pt;padding-left: 0px;">
                                <b>Name:</b>
                            </td>
                            <td width="47%" style="font-size:10pt;padding-left: 0px;">
                                <b>{{$name}}</b>
                            </td>
                            <td width="16%" style="font-size:10pt;padding-left: 0px;">
                                <b>Student No:</b>
                            </td>
                            <td width="23%" style="font-size:10pt;padding-left: 0px;">
                                <b>{{$idno}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Gr. and Sec:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{str_replace("Grade","",$level)}} - {{$section}}
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Class No:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$class_no}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Adviser:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$adviser}}
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>LRN:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$lrn}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Age:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$totalage}}
                            </td>
                            <td>
                            </td>
                            <td>
                            </td>
                        </tr>                        
                    </table>
                    <div style="height:.3cm;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 0px;">
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports">
                    <tr style="font-weight: bold;text-align:center;">
                        <td width="40%" style="padding: 15px 0 15px 0;">ACADEMIC SUBJECTS</td>
                        <td width="10%">1</td>
                        <td width="10%">2</td>
                        <td width="10%">3</td>
                        <td width="10%">4</td>
                        <td width="10%">FINAL RATING</td>
                        <td width="10%">REMARKS</td>
                    </tr>
                    @foreach($grades as $grade)
                        @if($grade->subjecttype == 0)
                            <tr style="text-align: center;font-size: 8pt;">
                                <td style="text-align: left;padding-left:10px;">{{$grade->subjectname}}</td>
                                <td align="center">{{round($grade->first_grading)}}</td>
                                <td align="center">{{round($grade->second_grading)}}</td>
                                <td align="center">{{round($grade->third_grading)}}</td>
                                <td align="center">{{round($grade->fourth_grading)}}</td>
                                <td align="center">{{round($grade->final_grade)}}</td>
                                <td align="center">
                                    @if((round($grade->final_grade,0)) != 0)
                                           <b> {{round($grade->final_grade,0) >= 75 ? "Passed":"Failed"}}</b>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr style="text-align: center">
                        <td style="text-align: right;">
                            <b>ACADEMIC AVERAGE&nbsp;&nbsp;&nbsp;</b>
                        </td>
                        <td align="center"><b>{{GradeController::gradeQuarterAve(array(0),array(0),1,$grades,$level)}}</b></td>
                        <td align="center"><b>{{GradeController::gradeQuarterAve(array(0),array(0),2,$grades,$level)}}</b></td>
                        <td align="center"><b>{{GradeController::gradeQuarterAve(array(0),array(0),3,$grades,$level)}}</b></td>
                        <td align="center"><b>{{GradeController::gradeQuarterAve(array(0),array(0),4,$grades,$level)}}</b></td>
                        <td align="center"><b>{{GradeController::gradeQuarterAve(array(0),array(0),5,$grades,$level)}}</b></td>

                        <td><b>
                        @if((round(GradeController::gradeQuarterAve(array(0),array(0),5,$grades,$level),0)) != 0)
                            {{round(GradeController::gradeQuarterAve(array(0),array(0),5,$grades,$level),0) >= 75 ? "Passed":"Failed"}}
                        @endif
                        </b></td>
                        
                    </tr>
                </table>                 
                </td>
            </tr>
            <tr><td style="padding-left: 0px;"><br></td></tr>
            <tr>
                <td style="padding-left: 0px;">
                    <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports" style="font-size:12px;">
                        <tr style="font-weight: bold;font-size: 10pt;text-align:center;">
                            <td class="print-size" width="40%" >TECHNICAL SUBJECTS</td>
                            <td class="print-size" width="10%">1</td>
                            <td class="print-size" width="10%">2</td>
                            <td class="print-size" width="10%">3</td>
                            <td class="print-size" width="10%">4</td>
                            <td class="print-size" width="10%">FINAL RATING</td>
                            <td class="print-size" width="10%">REMARKS</td>
                        </tr>
                        @foreach($grades as $grade)
                        @if($grade->subjecttype == 1)
                        <tr style="text-align: center;">
                            <td style="text-align: left;padding-left:10px" class="print-size">
                                {{$grade->subjectname}} 
                                @if($grade->weighted != 0)
                                <span style='float: right;margin-right: 50px'>({{$grade->weighted}}%)</span>
                                @endif
                            </td>
                            <td align="center">{{round($grade->first_grading)}}</td>
                            <td align="center">{{round($grade->second_grading)}}</td>
                            <td align="center">{{round($grade->third_grading)}}</td>
                            <td align="center">{{round($grade->fourth_grading)}}</td>
                            <td align="center">{{round($grade->final_grade)}}</td>
                            <td class="print-size">
                                    @if((round($grade->final_grade,0)) != 0)
                                           <b> {{round($grade->final_grade,0) >= 75 ? "Passed":"Failed"}}</b>
                                    @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
			@if($sy != 2016 &&($level == "Grade 9" || $level == "Grade 10"))
                        <tr style="text-align: center;font-weight: bold">
                            <td class="print-size" style="text-align: right"><b>TECHNICAL AVERAGE&nbsp;&nbsp;&nbsp;</b></td>

                            <td align="center">{{GradeController::weightedgradeQuarterAve(array(1),array(0),1,$grades,$level)}}</td>
                            <td align="center">{{GradeController::weightedgradeQuarterAve(array(1),array(0),2,$grades,$level)}}</td>
                            <td align="center">{{GradeController::weightedgradeQuarterAve(array(1),array(0),3,$grades,$level)}}</td>
                            <td align="center">{{GradeController::weightedgradeQuarterAve(array(1),array(0),4,$grades,$level)}}</td>
                            <td align="center">{{GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grades,$level)}}</td>
                            <td class="print-size">
                                @if((round(GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grades,$level),0)) != 0)
                                    {{round(GradeController::weightedgradeQuarterAve(array(1),array(0),5,$grades,$level),0) >= 75 ? "Passed":"Failed"}}
                                @endif
                            </td>
                        </tr>
                        @else
                        <tr style="text-align: center;font-weight:bold">
                            <td class="print-size" style="text-align: right"><b>TECHNICAL AVERAGE&nbsp;&nbsp;&nbsp;</b></td>

                            <td align="center">{{GradeController::gradeQuarterAve(array(1),array(0),1,$grades,$level)}}</td>
                            <td align="center">{{GradeController::gradeQuarterAve(array(1),array(0),2,$grades,$level)}}</td>
                            <td align="center">{{GradeController::gradeQuarterAve(array(1),array(0),3,$grades,$level)}}</td>
                            <td align="center">{{GradeController::gradeQuarterAve(array(1),array(0),4,$grades,$level)}}</td>
                            <td align="center">{{GradeController::gradeQuarterAve(array(1),array(0),5,$grades,$level)}}</td>
                            <td class="print-size">
                                @if((round(GradeController::gradeQuarterAve(array(1),array(0),5,$grades,$level),0)) != 0)
                                    {{round(GradeController::gradeQuarterAve(array(1),array(0),5,$grades,$level),0) >= 75 ? "Passed":"Failed"}}
                                @endif
                            </td>
                        </tr>
                        @endif
                        
                    </table>                      
                </td>
            </tr>
            <tr><td style="padding-left: 0px;"><br></td></tr>
            <tr>
                <td style="padding-left: 0px;">
                    <table class="greyed" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;background-color: rgba(201, 201, 201, 0.79);">
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
                </td>
            </tr>
            
        </table>
        <div class="page-break"></div>
        </div>

        <div class="back" style="padding-top: 30px;">

        <table class="parent" width="100%" style="padding:10px;margin-left: auto;margin-right: auto;margin-bottom: .8cm;">
        <tr>
            <td colspan="2" style="padding-left: 0px;">
            <?php $rowspan=0;?>
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                    <?php $rowspan++;?>
                    @endif
                @endforeach
                <table border = '0' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                    <tr>
                        <td width="30%" style="border: 1px solid"><b>CONDUCT CRITERIA</b></td>
                        <td width="10%" style="border: 1px solid"><b>Points</b></td>
                        <td width="10%" style="border: 1px solid">1</td>
                        <td width="10%" style="border: 1px solid">2</td>
                        <td width="10%" style="border: 1px solid">3</td>
                        <td width="10%" style="border: 1px solid">4</td>
                        <td width="20%" rowspan="{{$rowspan}}"></td>
                    </tr>
                {{--*/$counter = 0/*--}}
                {{--*/$length = $rowspan/*--}}
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                    {{--*/$counter ++/*--}} 
                    
                        <tr>
                            <td style="border: 1px solid">{{$grade->subjectname}}</td>
                            <td align="center" style="border: 1px solid">{{round($grade->points)}}</td>
                            <td align="center" style="border: 1px solid">{{round($grade->first_grading)}}</td>
                            <td align="center" style="border: 1px solid">{{round($grade->second_grading)}}</td>
                            <td align="center" style="border: 1px solid">{{round($grade->third_grading)}}</td>
                            <td align="center" style="border: 1px solid">{{round($grade->fourth_grading)}}</td>
                            @if($length == $counter)
                            <td style="border: 1px solid;border-right: 1px solid;"><b>FINAL GRADE</b></td>
                            @endif
                            
                        </tr>
                    @endif
                @endforeach
                <tr style="font-weight: bold">
                    <td align="center" style="border: 1px solid">CONDUCT GRADE</td>
                    <td align="center" style="border: 1px solid">100</td>
                    <td align="center" style="border: 1px solid">{{GradeController::conductQuarterAve(3,1,$grades)}}</td>
                    <td align="center" style="border: 1px solid">{{GradeController::conductQuarterAve(3,2,$grades)}}</td>
                    <td align="center" style="border: 1px solid">{{GradeController::conductQuarterAve(3,3,$grades)}}</td>
                    <td align="center" style="border: 1px solid">{{GradeController::conductQuarterAve(3,4,$grades)}}</td>
                    <td align="center" style="border: 1px solid">{{round(GradeController::conductTotalAve($grades,0),0)}}</td>
                </tr>

                </table>
                <br>
                
                <table border='1' cellpadding='0' cellspacing='0' width="100%" style="text-align: center;font-size:11px;">
                <?php $rowspan=0;?>
                 @foreach($grades as $grade)
                     @if($grade->subjecttype == 3)
                     <?php $rowspan++;?>
                     @endif
                 @endforeach
 
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
                <tr>
            <td style="padding-left: 0px;">
                Dear Parent:
                            <p style="text-indent: 20px">This report card shows the ability and progress your child has made in different learning areas as well as his/her core values.</p>
                            <p style="text-indent: 20px">The school welcomes you should you desire to know more about your child's progress.</p>
                            <br>
                            <div style="width:200px;text-align: center;float:right;border-top: 1px solid">
                                                    
                           @if($adviser != null)
                           <span>{{$adviser}}</span>
                           @endif
                                                    <br><span>Class Adviser</span></div>
                            <br>
            </td>
        </tr>
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

                        <td class="print-size" >Date of Issue:__________________</td>

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
            </td>
        </tr>
        
    </table>
    <br>

</td>
</tr>
</table>
                
            </td>
        </tr>
        <tr>
            <td>
                
            </td>
</tr>            
        </table>
        <div style="text-align: right;padding-left: 0px"><b>{{$idno}}</b></div>        
    <div class="page-break"></div>
    </div>
        <script type="text/javascript">
//            var widths = document.getElementById('cardHeader').offsetWidth;
//            var bodywidth = document.getElementById('body').offsetWidth;
//            
//            bodywidth = bodywidth/2
//            widths = (widths+120)/2
//            
//            var placement = bodywidth - widths;
//            document.getElementById("cardHeader").style.marginLeft = placement+"px";
        </script>        
    </div>    
        </body>
</html>


