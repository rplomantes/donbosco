<?php use App\Http\Controllers\Registrar\GradeController; ?>
<html>
    <head>
        <script src="{{asset('/js/jquery.js')}}"></script>
        <style type='text/css'>
            
            .hide{
                display:none;
            }
           table tr td{
            font-size:10.5pt;
            padding-left: 5px;
            padding-left: 5px;
           }
           
           .body{
            font-family: calibri;
            margin-left: auto;
            margin-right: auto;
            width:16.6cm;
            padding-left: .8cm;
            padding-right: .8cm;            
            }
            .greyed{
                background-color: rgba(201, 201, 201, 0.79) !important;
                -webkit-print-color-adjust: exact; 
            }            
        </style>    
       
        <style type="text/css" media="print">
 
           .body{
            font-family: calibri;
            width:100%;
            padding-left: .5cm;
            padding-right: .54cm;
            }            
            body{
                font-family: calibri;
            }
            .greyed{
                background-color: rgba(201, 201, 201, 0.79) !important;;
                -webkit-print-color-adjust: exact; 
            }
        </style>
        <link href="{{ asset('/css/print.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>

        <div class="body" id="body">
        <div class="front" style="padding-top: 50px;">
            <div style="z-index: 3;position: relative;max-height: 0px;bottom:5px;right:-30px;">
                <img src="{{asset('images/DBTI.png')}}"  style=";width:140px;">
            </div>
        <table class="parent" width="100%" style="z-index: 1;padding:10px;margin-left: auto;margin-right: auto;margin-bottom: .8cm;">
            <thead>
            <tr>
                <td style="padding-left: 0px;">
                    <table class="head"  border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding-left: 0px;text-align: center;">
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
                    <div style="text-align: center;font-size:11pt;"><b>SENIOR HIGH SCHOOL</b></div>
                    <div style="text-align: center;font-size:11pt;"><b>
                            @if($sem ==1)
                            FIRST SEMESTER
                            @else
                            SECOND SEMESTER
                            @endif
                        </b></div>
                    <br>
                        </td>
                    </tr>
                    <tr><td style="font-size:3px"><br></td></tr>
                    </table>
                </td>
            </tr>
            </thead>
            <tr>
                <td style="padding-left: 0px;">
                    <table width="100%" border = '0' cellpacing="0" cellpadding = "0">
                        <tr>
                            <td width="15%" style="font-size:10pt;padding-left: 0px;">
                                <b>Name:</b>
                            </td>
                            <td width="45%" style="font-size:10pt;padding-left: 0px;">
                                <b>{{$name}}</b>
                            </td>
                            <td width="15%" style="font-size:10pt;padding-left: 0px;">
                                <b>Student No:</b>
                            </td>
                            <td width="25%" style="font-size:10pt;padding-left: 0px;">
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
                                <b>Age:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$totalage}}
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;"  >
                                <b>LRN:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$lrn}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Sex:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;vertical-align: top;">
                                {{$gender}}
                            </td>
                            <td colspan="2" style="font-size:10pt;padding-left: 0px;"  >
                                <b style="display: inline-block;vertical-align: top;">Adviser:&nbsp;</b><div style="display: inline-block;width: 75%;">{{$adviser}}</div>
                            </td>

                        </tr>
                        <tr><td style="font-size:5pt;">&nbsp;</td></tr>
                        <tr>
                            <td colspan="4" style="font-size:10pt;padding-left: 0px;">
                                <b>ACADEMIC TRACK</b>:@if($status->strand == "STEM")
                                <span>Science,Technology, Engineering, and Mathematics (STEM)</span>
                                @else
                                <span>Accountancy, Business, and Management (ABM)</span>
                                @endif
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
                        <td width="50%" rowspan="2" style="padding: 10px 0 10px 0;text-align: center">SUBJECTS</td>
                        <td colspan="2" style="text-align: center">QUARTER</td>
                        <td width="15%" rowspan="2" style="text-align: center">FINAL GRADE</td>
                    </tr>
                    
                        <tr>
                            <td width="15%" style="text-align:center"><b>FIRST</b></td>
                            <td width="15%" style="text-align:center"><b>SECOND</b></td>
                        </tr>
                    <tr>
                        <td style="text-align:center"><b>Core Subjects</b></td>
                        <td colspan="3"></td>
                    </tr>
                    @foreach($grades as $grade)
                        @if($grade->subjecttype == 5)
                            <tr style="text-align: center;font-size: 8pt;">
                                <td style="text-align: left;padding-left: 10px">
                                    {{$grade->subjectname}}
                                </td>
                                <td>
                                    @if($sem ==1)
                                        {{round($grade->first_grading,0)}}
                                    @else
                                        {{round($grade->third_grading,0)}}
                                    @endif
                                </td>
                                <td>
                                    @if($sem ==1)
                                        {{round($grade->second_grading,0)}}
                                    @else
                                        {{round($grade->fourth_grading,0)}}
                                    @endif
                                </td >
                                <td>
                                    {{round($grade->final_grade,0)}}
                                </td>                
                            </tr>
                        @endif
                    @endforeach
                    
                    <tr>
                        <td style="text-align:center"><b>Applied and Specialized Subjects</b></td>
                        <td colspan="3"></td>
                    </tr>                    
                    
                    @foreach($grades as $grade)
                        @if($grade->subjecttype == 6)
                    <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left;padding-left: 10px">
                            {{$grade->subjectname}}
                        </td>
                            <td>
                                @if($sem ==1)
                                    {{round($grade->first_grading,0)}}
                                @else
                                    {{round($grade->third_grading,0)}}
                                @endif
                            </td>
                            <td>
                                @if($sem ==1)
                                    {{round($grade->second_grading,0)}}
                                @else
                                    {{round($grade->fourth_grading,0)}}
                                @endif
                            </td>
                        <td>
                            {{round($grade->final_grade,0)}}
                        </td>
                    </tr>    
                        @endif
                    @endforeach
                    <tr style="text-align: center">
                        <td></td>
                        <td colspan="2" style="text-align: right;padding-right: 10px">
                            <b>GENERAL AVERAGE for the Semester</b>
                        </td>
                        <td>
                            {{GradeController::gradeQuarterAve(array(0,5,6),array($sem),5,$grades,$level)}}
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td style="padding-left: 0px;">
                   
                </td>
            </tr>
            <tr><td><span style="height:10pt"></td></tr>
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
            <td style="padding-left: 0px;">
            <?php $rowspan=0;?>
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                    <?php $rowspan++;?>
                    @endif
                @endforeach
                <table border = '0' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                    <tr>
                        <td width="30%" style="padding-top: 15px;padding-bottom: 15px;border:1px solid"><b>CONDUCT CRITERIA</b></td>
                        <td width="10%" style="border:1px solid"><b>Points</b></td>
                        <td width="10%" style="border:1px solid"><b>1</b></td>
                        <td width="10%" style="border:1px solid"><b>2</b></td>
                        <td width="20%" rowspan="{{$rowspan}}"></td>
                    </tr>
                {{--*/$counter = 0/*--}}
                {{--*/$length = $rowspan/*--}}
                @foreach($grades as $grade)
                    @if($grade->subjecttype == 3)
                    {{--*/$counter ++/*--}}                   
                    <tr>
                        <td style="text-align: left;padding-left: 10px;border: 1px solid">{{$grade->subjectname}}</td>
                        <td style="border:1px solid">{{$grade->points}}</td>
                        @if($sem ==1)
                            <td style="border:1px solid">
                                {{round($grade->first_grading)}}
                            </td>
                            <td style="border:1px solid">
                                {{round($grade->second_grading)}}
                            </td>
                        @else
                            <td style="border:1px solid">
                                {{round($grade->third_grading)}}
                            </td>
                            <td style="border:1px solid">
                                {{round($grade->fourth_grading)}}
                            </td>
                        @endif

                        @if($length == $counter)
                        <td style="border:1px solid"><b>FINAL GRADE</b></td>
                        @endif
                        

                    </tr>
                    @endif
                @endforeach                
                        <tr>
                            <td style="text-align:center;border:1px solid"><b>CONDUCT GRADE</b></td>
                            <td style="border:1px solid"><b>100</b></td>
                            @if($sem ==1)
                                <?php 
                                    $qtr1 = GradeController::conductQuarterAve(3,1,$grades);
                                    $qtr2 = GradeController::conductQuarterAve(3,2,$grades);
                                ?>
                            @else
                                <?php 
                                    $qtr1 = GradeController::conductQuarterAve(3,3,$grades);
                                    $qtr2 = GradeController::conductQuarterAve(3,4,$grades);
                                ?>
                            @endif
                            <td align="center" style="border: 1px solid"><b>{{$qtr1}}</b></td>
                            <td align="center" style="border: 1px solid"><b>{{$qtr2}}</b></td>
                            <?php $conduct = round(($qtr1+$qtr2)/2,0); ?>
                            <td style="border:1px solid"><b>
                                    @if($qtr2 != 0)
                                    {{$conduct}}
                                    @endif
                                </b></td>
                            
                        </tr>
                </table>
                <br>
                
                <table border='1' cellpadding='0' cellspacing='0' width="100%" style="text-align: center;font-size:11px;">
                    <tr style="font-size:12px;">
                        <td style="padding-bottom:5px;padding-top:5px">
                            <b>ATTENDANCE</b>
                        </td>
                        @if($sem == 1)
                        <td>Jun</td><td>Jul</td><td>Aug</td><td>Sept</td><td>Oct</td>

                        @else
                        <td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td>
                        @endif
                        <td>TOTAL</td>
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
                    </tr>
                    @foreach($ctr_attendances as $ctr_attendance)
                    <tr style="font-size:11px;">
                        <td style="text-align: left">Days of School</td>
                        @if($sem ==1)
                        <td>@if($ctr_attendance->jun != 0){{round($ctr_attendance->jun,1)}}@endif</td>
                        <td>@if($ctr_attendance->jul != 0){{round($ctr_attendance->jul,1)}}@endif</td>
                        <td>@if($ctr_attendance->aug != 0){{round($ctr_attendance->aug,1)}}@endif</td>
                        <td>@if($ctr_attendance->sept != 0){{round($ctr_attendance->sept,1)}}@endif</td>
                        <td>@if($ctr_attendance->oct != 0){{round($ctr_attendance->oct,1)}}@endif</td>
                        <td><b>@if($ctr_attendance->oct != 0){{round($ctr_attendance->sem1,1)}}@endif</b></td>
                        @else
                        <td>@if($ctr_attendance->nov != 0){{round($ctr_attendance->nov,1)}}@endif</td>
                        <td>@if($ctr_attendance->dece != 0){{round($ctr_attendance->dece,1)}}@endif</td>
                        <td>@if($ctr_attendance->jan != 0){{round($ctr_attendance->jan,1)}}@endif</td>
                        <td>@if($ctr_attendance->feb != 0){{round($ctr_attendance->feb,1)}}@endif</td>
                        <td>@if($ctr_attendance->mar != 0){{round($ctr_attendance->mar,1)}}@endif</td>
                        <td><b>@if($ctr_attendance->mar != 0){{round($ctr_attendance->sem2,1)}}@endif</b></td>
                        @endif
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
                        @if($sem ==1)
                        <td>@if($jun != 0){{round($attendance->jun,1)}}@endif</td>
                        <td>@if($jul != 0){{round($attendance->jul,1)}}@endif</td>
                        <td>@if($aug != 0){{round($attendance->aug,1)}}@endif</td>
                        <td>@if($sept != 0){{round($attendance->sept,1)}}@endif</td>
                        <td>@if($oct != 0){{round($attendance->oct,1)}}@endif</td>
                        <td><b>@if($oct != 0){{round($attendance->sem1,1)}}@endif</b></td>
                        @else
                        <td>@if($nov != 0){{round($attendance->nov,1)}}@endif</td>
                        <td>@if($dece != 0){{round($attendance->dece,1)}}@endif</td>
                        <td>@if($jan != 0){{round($attendance->jan,1)}}@endif</td>
                        <td>@if($feb != 0){{round($attendance->feb,1)}}@endif</td>
                        <td>@if($mar != 0){{round($attendance->mar,1)}}@endif</td>
                        <td><b>@if($mar != 0){{round($attendance->sem2,1)}}@endif</b></td>
                        @endif
                    </tr>
                    @endforeach
                </table>
                <br>
               
            </td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">
                Dear Parent:
                            <p style="text-indent: 20px">This report card shows the ability and progress your child has made in different learning areas as well as his/her core values.</p>
                            <p style="text-indent: 20px">The school welcomes you should you desire to know more about your child's progress.</p>
                            <br>
                            <div style="width:200px;text-align: center;float:right;border-top: 1px solid">
                                                    

                           <span>{{$adviser}}</span>
                                                    <br><span>Class Adviser</span></div>
                            <br>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">
                <br>
                 <table width="100%">
                    <tr>
                        <td class="print-size"  width="49%">
                            <b>Certificate of Eligibility for Promotion</b>
                        </td>
                        <td class="print-size" >
                            <b>Cancellation of Eligibility to Transfer</b>
                        </td>                                                    
                    </tr>
                    <tr>
                        <td class="print-size" >
                            The student is eligible for transfer and
                        </td>
                        <td class="print-size" >
                            Admitted in:_____________________
                        </td>                                                    
                    </tr>
                    <tr>
                        <td class="print-size" >admission to:___________________</td>

                        <td class="print-size" >Grade:__________________________</td>
                    </tr>
                    <tr>
                        <td class="print-size" >Retained in ____________________</td>
                        <td class="print-size" >Date ___________________________</td>
                    </tr>
                    <tr>
                        <td class="print-size" >Date of Issue:__________________</td>

                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><br></td>
                    </tr>
                                                                    <tr style="text-align: center">
                        <td class="print-size"></td>
                        <td class="print-size"><div style="border-bottom: 1px solid;width: 80%;margin-left: auto;margin-right: auto"><img src="{{asset('images/HS_PRINCIPAL.png')}}"  style="display: inline-block;width:180px"></div></td> 
                    </tr>
                    <tr style="text-align: center;">
                        <td></td>
                        <td class="print-size" >Ms. Violeta F. Roxas</td>
                    </tr>
                    <tr style="text-align: center">
                        <td class="print-size" ></td>
                        <td class="print-size" ><b>Principal - High School Department</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        
    </table>
    

</td>
</tr>
</table>
                
            </td>
        </tr>
            
        </table>
        <div style="text-align: right;padding-left: 0px"><b>{{$idno}}</b></div>
    <div class="page-break"></div>
    </div>


        </div>
    </body>
</html>
