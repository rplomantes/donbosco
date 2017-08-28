<html>
    <head>
        <script src="{{asset('/js/jquery.js')}}"></script>
        <style type='text/css'>
            .hide{
                display:none;
            }            
           .padded tr td{
               padding-top: 7px;
               padding-bottom: 7px;
           }

           .padded tr td{
               padding-top: 2px;
               padding-bottom: 2px;
           }

           table tr td{font-size:10pt;}
           body{
                font-family: calibri;
                margin-left: auto;
                
                    margin:0px;
            }

            td{vertical-align:top}
        </style>    
        <style type="text/css" media="print">
                       body{
		
                font-family: calibri;
                margin-left: none;
                margin-right: none;
                
            }
		.front{
            -ms-transform:rotate(180deg);
        -o-transform:rotate(180deg);
        transform:rotate(180deg);	

}
        </style>
        <link href="{{ asset('/css/print.css') }}" rel="stylesheet">

               
    </head>
    <body style="margin:0px;">
        

        <div class="back">
            <table style="margin-top: 30px;margin-bottom:30px;margin-left: .5cm;margin-right:.5cm">
                <tr>
                    <td style="width:8.33cm" id="com0">
                        <table class="padded grades" border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports" style="margin-top: auto;margin-bottom: auto;">
                            <tr><td colspan="6" align="center"><b>QUARTERLY GRADES</b></td></tr>
                            <tr style="font-weight: bold;text-align:center;">
                                <td width="35%" style="padding: 15px 0 15px 0;">LEARNING AREAS</td>
                                <td width="10%">1st</td>
                                <td width="10%">2nd</td>
                                <td width="10%">3rd</td>
                                <td width="10%">4th</td>
                                <td width="12%">FINAL RATING</td>
                            </tr>
                            @foreach($grades as $grade)
                                @if($grade->subjecttype == 0)
                                <tr style="text-align: center;font-size: 8pt;">
                                    <td style="text-align: left;padding-left:5px;">
                                        {{$grade->subjectname}}
                                    </td>
                                    <td>
                                        @if(round($grade->first_grading,2) != 0)
                                            {{round($grade->first_grading,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(round($grade->second_grading,2) != 0)
                                            {{round($grade->second_grading,2)}}
                                        @endif
                                    </td >
                                    <td>
                                        @if(round($grade->third_grading,2) != 0)
                                            {{round($grade->third_grading,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(round($grade->fourth_grading,2) != 0)
                                            {{round($grade->fourth_grading,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(round($grade->final_grade,2) != 0)
                                            {{number_format(round($final_grade,2),2)}}
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            <tr style="text-align: center">
                                <td style="text-align: right;padding-right:10px">
                                    <b>ACADEMIC AVERAGE</b>
                                </td>
                            <td>
                                <b>{{GradeController::gradeQuarterAve(array(0),array(0),1,$grades,$level)}}</b>
                            </td>
                            <td>
                                <b>{{GradeController::gradeQuarterAve(array(0),array(0),2,$grades,$level)}}</b>
                            </td>
                            <td>
                                <b>{{GradeController::gradeQuarterAve(array(0),array(0),3,$grades,$level)}}</b>
                            </td>
                            <td>
                                <b>{{GradeController::gradeQuarterAve(array(0),array(0),4,$grades,$level)}}</b>
                            </td>
                            <td>
                                <b>{{GradeController::gradeQuarterAve(array(0),array(0),5,$grades,$level)}}</b>
                            </td>
                            </tr>
                        </table>
                        <br>
                        <table class="padded acad" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;margin-top: auto;margin-bottom: auto;">
                            <tr><td colspan="3"><b>ACADEMIC DESCRIPTORS</b></td></tr>
                            <tr style="font-weight:bold;">
                                <td width="36%" class="descriptors">
                                    DESCRIPTORS
                                </td>
                                <td width="32%" class="scale">
                                    GRADING SCALE
                                </td>            
                                <td width="32%" class="remarks">
                                    NUMERIC EQUIVALENT
                                </td>                        
                            </tr>
                            <tr>
                                <td>Outstanding</td><td>O</td><td>90 - 100</td>
                            </tr>
                            <tr>
                                <td>Very Satisfactory</td><td>VS</td><td>85 - 89</td>
                            </tr>
                            <tr>
                                <td>Satisfactory</td><td>S</td><td>80 - 84</td>
                            </tr>
                            <tr>
                                <td>Fairly Satisfactory</td><td>FS</td><td>75 - 79</td>
                            </tr>
                            <tr>
                                <td>Did Not Meet Expectations</td><td>DNME</td><td>Below 75</td>
                            </tr>
                        </table>
                        <br>
<table border='1' cellpadding='0' cellspacing='0' width="100%" style="text-align: center;font-size:11px;">
                            <tr style="font-size:12px;">
                                <td style="padding-bottom:5px;padding-top:5px">
                                    <b>ATTENDANCE</b>
                                </td>
                                <td>Jun</td><td>Jul</td><td>Aug</td><td>Sept</td><td>Oct</td><td>Nov</td>
                            </tr>
                            <?php
                            $jun = 0;
                            $jul = 0;
                            $aug = 0;
                            $sept = 0;
                            $oct = 0;
                            $nov = 0;
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
                            </tr>

                            <?php
                            $jun = $ctr_attendance->jun;
                            $jul = $ctr_attendance->jul;
                            $aug = $ctr_attendance->aug;
                            $sept = $ctr_attendance->sept;
                            $oct = $ctr_attendance->oct;
                            $nov = $ctr_attendance->nov;
                            ?>
                            @endforeach
                            @if(count($ctr_attendances)==0)
                            <tr>
                                <td style="text-align: left">Days of School</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                            @foreach($attendances as $attendance)
                            <tr>
                            <td style="text-align: left">{{$attendance->attendanceName}}</td>
                            <td>@if($jun != 0){{round($attendance->jun,1)}}@endif</td>
                            <td>@if($jul != 0){{round($attendance->jul,1)}}@endif</td>
                            <td>@if($aug != 0){{round($attendance->aug,1)}}@endif</td>
                            <td>@if($sept != 0){{round($attendance->sept,1)}}@endif</td>
                            <td>@if($oct != 0){{round($attendance->oct,1)}}@endif</td>
                            <td>@if($nov != 0){{round($attendance->nov,1)}}@endif</td>
                            </tr>
                            @endforeach
                            @if(count($attendances)==0)
                            <tr><td style="text-align: left">Days Present</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr><td style="text-align: left">Days Absent </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr><td style="text-align: left">Days Tardy  </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            @endif
                        </table>
                        <br>
			<table border='1' cellpadding='0' cellspacing='0' width="100%" style="text-align: center;font-size:11px;">
                            <tr style="font-size:12px;">
                                <td style="padding-bottom:5px;padding-top:5px">
                                    <b>ATTENDANCE</b>
                                </td>
                                <td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td>
                                <td>TOTAL</td>
                            </tr>
                            <?php
                            $dece = 0;
                            $jan = 0;
                            $feb = 0;
                            $mar = 0;
                            ?>
                            @foreach($ctr_attendances as $ctr_attendance)
                            <tr>
                            <td style="text-align: left">Days of School</td>
                            <td>@if($ctr_attendance->dece != 0){{round($ctr_attendance->dece,1)}}@endif</td>
                            <td>@if($ctr_attendance->jan != 0){{round($ctr_attendance->jan,1)}}@endif</td>
                            <td>@if($ctr_attendance->feb != 0){{round($ctr_attendance->feb,1)}}@endif</td>
                            <td>@if($ctr_attendance->mar != 0){{round($ctr_attendance->mar,1)}}@endif</td>
                            <td>@if($ctr_attendance->mar != 0){{round($ctr_attendance->total,1)}}@endif</td>
                            </tr>

                            <?php
                            $dece = $ctr_attendance->dece;
                            $jan = $ctr_attendance->jan;
                            $feb = $ctr_attendance->feb;
                            $mar = $ctr_attendance->mar;
                            ?>
                            @endforeach
                            @if(count($ctr_attendances)==0)
                            <tr>
                                <td style="text-align: left">Days of School</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endif
                            @foreach($attendances as $attendance)
                            <tr>
                            <td style="text-align: left">{{$attendance->attendanceName}}</td>
                            <td>@if($dece != 0){{round($attendance->dece,1)}}@endif</td>
                            <td>@if($jan != 0){{round($attendance->jan,1)}}@endif</td>
                            <td>@if($feb != 0){{round($attendance->feb,1)}}@endif</td>
                            <td>@if($mar != 0){{round($attendance->mar,1)}}@endif</td>
                            <td>@if($mar != 0){{round($attendance->total,1)}}@endif</td>
                            </tr>
                            @endforeach
                            @if(count($attendances)==0)
                            <tr><td style="text-align: left">Days Present</td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr><td style="text-align: left">Days Absent </td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr><td style="text-align: left">Days Tardy  </td><td></td><td></td><td></td><td></td><td></td></tr>
                            @endif
                        </table>

                    </td>
                    <td style="width:1cm"></td>
                    <td style="width:8.33cm" id="com1">
                        <div id="con">
                        <table class="padded conduct" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;margin-top: auto;margin-bottom: auto;">
                            <tr>
                                <td width="30%"><b>CONDUCT CRITERIA</b></td>
                                <td width="9%"><b>Points</b></td>
                                <td width="9%"><b>1</b></td>
                                <td width="9%"><b>2</b></td>
                                <td width="9%"><b>3</b></td>
                                <td width="9%"><b>4</b></td>
                            </tr>
                            @foreach($grades as $grade)
                                @if($grade->subjecttype == 3)
                                <tr>
                                    <td style="text-align: left;padding-left:10px">{{$grade->subjectname}}</td>
                                    <td>{{$grade->points}}</td>
                                    <td>
                                        @if(round($grade->first_grading,2) != 0)
                                            {{round($grade->first_grading,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(round($grade->second_grading,2) != 0)
                                            {{round($grade->second_grading,2)}}
                                        @endif
                                    </td >
                                    <td>
                                        @if(round($grade->third_grading,2) != 0)
                                            {{round($grade->third_grading,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(round($grade->fourth_grading,2) != 0)
                                            {{round($grade->fourth_grading,2)}}
                                        @endif
                                    </td>

                                </tr>
                                @endif
                            @endforeach                   
                            <tr>
                                <td><b>CONDUCT GRADE</b></td>
                                <td><b>100</b></td>
                                <td><b>{{GradeController::conductQuarterAve(3,1,$grades)}}</b></td>
                                <td><b>{{GradeController::conductQuarterAve(3,2,$grades)}}</b></td>
                                <td><b>{{GradeController::conductQuarterAve(3,3,$grades)}}</b></td>
                                <td><b>{{GradeController::conductQuarterAve(3,4,$grades)}}</b></td>
                            </tr>
                                <tr>
                                    <td><b>FINAL GRADE</b></td>
                                    <td colspan="5">{{GradeController::conductQuarterAve(3,5,$grades)}}</td>
                                </tr>
                        </table>
                        <br>
                        </div>

                        <table class="padded cond" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                            <tr><td colspan="3"><b>CONDUCT DESCRIPTORS</b></td></tr>
                                <tr style="font-weight:bold;">
                                    <td width="36%" class="descriptors">
                                        DESCRIPTORS
                                    </td>
                                    <td width="32%" class="scale">
                                        GRADING SCALE
                                    </td>            
                                    <td width="32%" class="remarks">
                                        NUMERIC EQUIVALENT
                                    </td>                        
                                </tr>
                                <tr>
                                    <td>Excellent</td><td>E</td><td>96 - 100</td>
                                </tr>
                                <tr>
                                    <td>Very Good</td><td>VG</td><td>91 - 95</td>
                                </tr>
                                <tr>
                                    <td>Good</td><td>G</td><td>86 - 90</td>
                                </tr>
                                <tr>
                                    <td>Fair</td><td>Fair</td><td>80 - 85</td>
                                </tr>
                                <tr>
                                    <td>Failed</td><td>Failed</td><td>75 and Below</td>
                                </tr>
                            </table>
                        <br>
                        <div  id="CL">
                            <table border="1" width="100%" cellpadding="0" cellspacing="0">
                                <tr style="text-align: center"><td colspan="2"><b>MATHEMATICS</b></td></tr>
                                @foreach($competencies as $comp)
                                    @if($comp->subject == "Mathematics")
                                    <tr>
                                        <td width="85%" style="padding-left: 15px;">{{$comp->description}}</td>
                                        <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>  
                            <br>
                        </div>
                    </td>
                    <td style="width:1cm"></td>
                    <td style="width:8.33cm" id="com2">
                        <div  id="FIL">
                            <table border="1" width="100%" cellpadding="0" cellspacing="0">
                                <tr style="text-align: center"><td colspan="2"><b>FILIPINO</b></td></tr>
                                @foreach($competencies as $comp)
                                    @if($comp->subject == "Filipino")
                                    <tr>
                                        <td width="85%" style="padding-left: 15px;">{{$comp->description}}</td>
                                        <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>  
                            <br>
                        </div>
                        <div  id="ENGL">
                            <table border="1" width="100%" cellpadding="0" cellspacing="0">
                                <tr style="text-align: center"><td colspan="2"><b>ENGLISH</b></td></tr>
                                @foreach($competencies as $comp)
                                    @if($comp->subject == "English")
                                    <tr>
                                        <td width="85%" style="padding-left: 15px;">{{$comp->description}}</td>
                                        <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>  
                            <br>
                        </div>
                    </td>
                </tr>
            </table>

        <div class="page-break"></div>
	        </div>

        <div class="front">
        <table style="margin-top: 110px;margin-bottom:30px;margin-left: .5cm;margin-right:.5cm" align="center">
                    <tr>
                        <td style="width:8.33cm" id="com3">
                        <div  id="MATH">
                            <table border="1" width="100%" cellpadding="0" cellspacing="0">
                                <tr style="text-align: center"><td colspan="2"><b>CHRISTIAN LIVING EXPERIENCE</b></td></tr>
                                @foreach($competencies as $comp)
                                    @if($comp->subject == "Christian Living")
                                    <tr>
                                        <td width="85%" style="padding-left: 15px;">{{$comp->description}}</td>
                                        <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>  
                            <br>
                        </div>
                        </td>
                        <td style="width:1cm"></td>
                        <td style="width:8.33cm" id="com4">
                            <div id="cert">
                                <table width="100%">
                            <tr>
                                <td class="print-size"  width="49%" style="font-size: 11pt">
                                    <b>Certificate of Eligibility for Promotion</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="print-size" >
                                    The student is eligible for transfer and admission to:
                                </td>              
                            </tr>
                            <tr>
                                <td class="print-size" ><b>Grade:</b>___________________________</td>
                            </tr>
                            <tr>
                                <td class="print-size" ><b>Date of Issue:</b>__________________________</td>
                            </tr>
                            <tr>
                                <td colspan="2"><br><br></td>                                                    
                            </tr>
                            <tr style="text-align: center">
                                <td class="print-size">________________________________</td>
                            </tr>
                            <tr style="text-align: center;">
                                <td class="print-size" >
                                    {{$adviser}}
                                </td>
                            </tr>
                            <tr style="text-align: center">
                                <td class="print-size" ><b>Class Adviser</b></td>
                            </tr>
                            </table>
                                <br>
                                <table width="100%">
                                    <tr>
                                        <td class="print-size" style="font-size: 11pt">
                                            <b>Cancellation of Eligibility to Transfer</b>
                                        </td>  
                                    </tr>
                                    <tr>
                                        <td class="print-size" >
                                            <b>Admitted in:</b>____________________________
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td class="print-size" ><b>Grade:_________   Date:__________________</b></td>
                                    </tr>
                                    <tr><td><br><br></td></tr>
                                    <tr>
                                        <td class="print-size" style="text-align: center;"><div style="border-bottom: 1px solid;width: 80%;margin-left: auto;margin-right: auto;height: 30px;"><img src="{{asset('images/elem_sig.png')}}"  style="display: inline-block;width:180px"></div></td>                                        
                                    </tr>
                                    <tr>
                                        <td class="print-size" style="text-align: center">Mrs. Ma. Dolores F. Bayocboc</td>
                                    </tr>
                                    <tr>
                                        <td class="print-size" style="text-align: center">Elementary Principal</td>
                                    </tr>                                    
                                </table>
                            </div>
                        </td>
                        <td style="width:1cm"></td>
                        <td style="width:8.33cm" id="front" style="padding-left: 20px;padding-right: 20px">
                            <div style="text-align: center;">
                                <span style="font-size: 12pt;"><b>DON BOSCO TECHNICAL INSTITUTE</b></span><br>
                                <span style="font-size: 10pt;">Chino Roces Ave., Brgy. Pio del Pilar</span><br>
                                <span style="font-size: 10pt;">Makati City</span>
                                <div>
                                <img src="{{asset('images/DBTI.png')}}"  style="display: inline-block;width:200px;padding-top: 60px;padding-bottom: 60px">
                                <br>
                                
                                <br>
                                <span style="font-size: 10pt;font-weight: bold;text-align: center">ELEMENTARY DEPARTMENT</span><br>
                                <span style="font-size: 10pt;font-weight: bold;text-align: center">{{$sy}} - {{$sy+1}}</span>
                                </div><br>
                                <div style="font-size: 10pt;font-weight: bold">DEVELOPMENTAL CHECKLIST</div>
                                <div style="font-size: 10pt;font-weight: bold">
                                    @if($quarter == 1)
                                    FIRST QUARTER
                                    @elseif($quarter == 2)
                                    SECOND QUARTER
                                    @elseif($quarter == 3)
                                    THIRD QUARTER
                                    @else
                                    FOURTH QUARTER
                                    @endif
                                </div>
                                <br>
                            </div>
                            <div class="parent" style="border: 1px solid; padding: 20px 10px 10px;border-radius: 40px;">
                                <div style="text-align:center;font-size: 11pt;"><b>KINDERGARTEN</b></div>
                                <br>
                            <div><div style="display:inline-block;width:55px;vertical-align: top"><b>Name: </b></div><div style="display:inline-block;width:200px">{{$name}}</div></div>
                            <div><div style="display:inline-block;width:55px;vertical-align: top"><b>ID No: </b></div><div style="display:inline-block;width:200px">{{$idno}}</div></div>
                            <div><div style="display:inline-block;width:55px;"><b>LRN: </b></div>{{$lrn}}</div>
                            <div><div style="display:inline-block;width:55px;"><b>Age: </b></div>{{$totalage}}</div>
                            <div><div style="display:inline-block;width:55px;"><b>Section: </b></div>{{strtoupper($section)}}</div>
                            <div><div style="display:inline-block;width:55px;"><b>Adviser: </b></div>{{strtoupper($adviser)}}</div>
                            </div>
                        </td>
                    </tr>

        </table>
        </div>

        <div class="page-break"></div>        
        
        <script>
                $(document).ready(function() {
                    var x = $('.grades').height();
                    $('.conduct').css('height', x);
                    
                    var x = $('.acad').height();
                    $('.cond').css('height', x);
                });
        </script>
    </body>
</html>
