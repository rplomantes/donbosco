<html>
    <head>
        <script src="{{asset('/js/jquery.js')}}"></script>
        <style type='text/css'>
            
            .hide{
                display:none;
            }
           table tr td{
            font-size:9.5pt;
            padding-left: 5px;
            padding-right: 5px;            
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
                background-color: rgba(201, 201, 201, 0.79) !important;;
                -webkit-print-color-adjust: exact; 
            }  
            .cert tr td{
            padding-left: 0px;
            padding-right: 0px;                 
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
            
            body{
                width:100%;
            }
           .body{
            font-family: calibri;
            width:100%;
            padding-left: .5cm;
            padding-right: .5cm;

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
    <body >
        <div class="body" id="body">
        <?php $card = 1;?>    
        @foreach($collection as $info)
        <div class="front"  style="padding-top: 20px;">
        <table class="parent" width="100%" style="margin-bottom: .2cm;">
            <thead>
            <tr>
                <td style="padding-left: 0px;padding-right: 0px;">
                    <table class="head"  border="0" cellpadding="0" cellspacing="0" id="cardHeader{{$card}}">

                    <tr>
                        <td rowspan="7" style="text-align: right;padding-left: 0px;width: 140px;vertical-align: top" class="logo">
                            <img src="{{asset('images/DBTI.png')}}"  style="display: inline-block;width:100%">
                        </td>
                        <td style="padding-left: 0px;">
                            <span style="font-size:12pt; font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span>
                        </td>
                    </tr>
                    <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">Chino Roces Ave., Makati City </td></tr>
                    <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">PAASCU Accredited</td></tr>
                    <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">School Year {{$schoolyear->schoolyear}} - {{intval($schoolyear->schoolyear)+1}}</td></tr>
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
                </td>
            </tr>
            </thead>
            <tr>
                <td style="padding-left: 0px;padding-right: 0px;">
                    <table class="head" width="100%" border = '0' cellpacing="0" cellpadding = "0">
                        <tr>
                            <td width="16%" style="font-size:10pt;padding-left: 0px;">
                                <b>Name:</b>
                            </td>
                            <td width="47%" style="font-size:10pt;padding-left: 0px;"><b>
                                {{$info['info']->lastname}}, {{$info['info']->firstname}} {{substr($info['info']->middlename, 0,1)}}. {{$info['info']->extensionname}}
                                </b>
                            </td>
                            <td width="16%" style="font-size:10pt;padding-left: 0px;">
                                <b>Student No:</b>
                            </td>
                            <td width="23%" style="font-size:10pt;padding-left: 0px;">
                                <b>{{$info['info']->idno}}</b>
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
                                {{$info['info']->class_no}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Adviser:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                @if($teacher != null)
                                {{$teacher->adviser}}
                                @endif
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;"  >
                                <b>LRN:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$info['info']->lrn}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Age:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$info['info']->age}}
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
                <td style="padding-left: 0px;padding-right: 0px;">
                @if(sizeOf($info['aca'])!= 0)
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports">
                    <tr style="font-weight: bold;text-align:center;">
                        <td width="40%" style="padding-top: 15px;padding-bottom: 15px;">SUBJECTS</td>
                        <td width="10%">1</td>
                        <td width="10%">2</td>
                        <td width="10%">3</td>
                        <td width="10%">4</td>
                        <td width="10%">FINAL RATING</td>
                        <td width="10%">REMARKS</td>
                    </tr>
                    {{--*/$first=0/*--}}
                    {{--*/$second=0/*--}}
                    {{--*/$third=0/*--}}
                    {{--*/$fourth=0/*--}}
                    {{--*/$final=0/*--}}
                    {{--*/$count=0/*--}}
                    @foreach($info['aca'] as $key=>$academics)
                    <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left;padding-left: 10px;">
                            {{$academics->subjectname}}
                        </td>
                        <td>
                            @if(round($academics->first_grading,2) != 0)
                                {{round($academics->first_grading,2)}}
                            @endif
                            {{--*/$first = $first + round($academics->first_grading,2)/*--}}
                        </td>
                        <td>
                            @if(round($academics->second_grading,2) != 0)
                                {{round($academics->second_grading,2)}}
                            @endif
                            {{--*/$second = $second + round($academics->second_grading,2)/*--}}
                        </td >
                        <td>
                            @if(round($academics->third_grading,2) != 0)
                                {{round($academics->third_grading,2)}}
                            @endif
                            {{--*/$third = $third + round($academics->third_grading,2)/*--}}
                        </td>
                        <td>
                            @if(round($academics->fourth_grading,2) != 0)
                                {{round($academics->fourth_grading,2)}}
                            @endif
                            {{--*/$fourth = $fourth + round($academics->fourth_grading,2)/*--}}
                        </td>
                        <td>
                            @if(round($academics->final_grade,2) != 0)
                            
                            {{round($academics->final_grade,2)}}
                            @endif
                            {{--*/$final = $final + round($academics->final_grade,2)/*--}}
                        </td>
                        <td>
                            {{$academics->remarks}}
                            {{--*/$count ++/*--}}
                        </td>                         
                    </tr>
                    @endforeach
                    <tr style="text-align: center">
                        <td style="text-align: right;">
                            <b>GENERAL AVERAGE&nbsp;&nbsp;&nbsp;</b>
                        </td>
                        <td><b>
                            @if(round($first/$count,2) != 0)
                                {{round($first/$count,2)}}
                            @endif</b>
                        </td>
                        <td><b>
                            @if(round($second/$count,2) != 0)
                                {{round($second/$count,2)}}
                            @endif
                            </b>
                        </td>
                        <td><b>
                            @if(round($third/$count,2) != 0)
                            {{round($third/$count,2)}}
                            @endif</b>
                        </td>
                        <td><b>
                            @if(round($fourth/$count,2) != 0)
                            {{round($fourth/$count,2)}}
                            @endif</b>
                        </td>
                        <td>
                            @if(round($fourth/$count,2) != 0)
                                {{round($final/$count,2)}}
                            @endif
                        </td>

                        <td>
                        @if((round($final/$count,2)) != 0)
                        {{round($final/$count,2) >= 75 ? "Passed":"Failed"}}
                        @endif
                        </td>
                        
                    </tr>
                </table>
                @endif                    
                </td>
            </tr>
            <tr><td style="padding-left: 0px;padding-right: 0px;"><br></td></tr>
            <tr>
                <td style="padding-left: 0px;padding-right: 0px;">
                    <table class="greyed" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
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
            <tr>
                <td><br></td>
            </tr>
            <tr>
                <td style="padding-left: 0px;padding-right: 0px;">
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
                </td>
            </tr>
        </table>
        <div class="page-break"></div>
        </div>

        <div class="back" style="padding-top: 20px;" >
        
        <table class="parent" width="100%" style="margin-bottom: .8cm;">
            <tr>
                <td colspan="2" style="padding-left: 0px;padding-right: 0px;">
                    <table cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                        <tr>
                            <td width="35%" style="border: 1px solid"><b>CONDUCT CRITERIA</b></td>
                            <td width="8%" style="border: 1px solid"><b>Points</b></td>
                            <td width="8%" style="border: 1px solid">1</td>
                            <td width="8%" style="border: 1px solid">2</td>
                            <td width="8%" style="border: 1px solid">3</td>
                            <td width="8%" style="border: 1px solid">4</td>
                            <td width="15%" rowspan="{{count($info['con'])}}"></td>
                        </tr>
                            {{--*/$first=0/*--}}
                            {{--*/$second=0/*--}}
                            {{--*/$third=0/*--}}
                            {{--*/$fourth=0/*--}}
                            {{--*/$counter = 0/*--}}
                            {{--*/$length = count($info['con'])/*--}}
                            @foreach($info['con'] as $key=>$conducts)
                            {{--*/$counter ++/*--}}                    
                        <tr>
                            <td style="text-align: left;border: 1px solid;padding-left: 10px;vertical-align: middle">{{$conducts->subjectname}}</td>
                            <td style="border: 1px solid"><b>{{$conducts->points}}</b></td>
                            <td style="border: 1px solid">
                                @if(!round($conducts->first_grading,2)==0)
                                {{round($conducts->first_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($conducts->first_grading,2)/*--}}
                            </td>
                            <td style="border: 1px solid">
                                @if(!round($conducts->second_grading,2)==0)
                                {{round($conducts->second_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($conducts->second_grading,2)/*--}}
                            </td>
                            <td style="border: 1px solid">
                                @if(!round($conducts->third_grading,2)==0)
                                {{round($conducts->third_grading,2)}}
                                @endif
                                {{--*/$third = $third + round($conducts->third_grading,2)/*--}}
                            </td>
                            <td style="border: 1px solid">
                                @if(!round($conducts->fourth_grading,2)==0)
                                {{round($conducts->fourth_grading,2)}}
                                @endif
                                {{--*/$fourth = $fourth + round($conducts->fourth_grading,2)/*--}}
                            </td>
                            @if($length == $counter)
                            <td style="border: 1px solid"><b>FINAL GRADE</b></td>
                            @endif


                        </tr>
                            @endforeach                    
                            <tr>
                                <td style="border: 1px solid"><b>CONDUCT GRADE</b></td>
                                <td style="border: 1px solid"><b>100</b></td>
                                <td style="border: 1px solid"><b>@if(!$first == 0){{$first}}@endif</b></td>
                                <td style="border: 1px solid"><b>@if(!$second == 0){{$second}}@endif</b></td>
                                <td style="border: 1px solid"><b>@if(!$third == 0){{$third}}@endif</b></td>
                                <td style="border: 1px solid"><b>@if(!$fourth == 0){{$fourth}}@endif</b></td>

                                <td style="border: 1px solid"><b>
                                    @if(!$fourth == 0)
                                    {{round(($first+$second+$third+$fourth)/4,2)}}
                                @endif</b></td>

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
                        <tr style="font-size:11px;">
                            <td style="text-align: left">Days of School</td>
                            <?php $tsd  = \App\CtrAttendance::Select(DB::raw('*,Jun+Jul+Aug+Sept+Oct+Nov+Dece+Jan+Feb+Mar as total'))->where('schoolyear',$schoolyear->schoolyear)->where('level',$level)->first();?>
                            <td>@if($tsd->Jun != 0){{round($tsd->Jun,1)}}@endif</td>
                            <td>@if($tsd->Jul != 0){{round($tsd->Jul,1)}}@endif</td>
                            <td>@if($tsd->Aug != 0){{round($tsd->Aug,1)}}@endif</td>
                            <td>@if($tsd->Sept != 0){{round($tsd->Sept,1)}}@endif</td>
                            <td>@if($tsd->Oct != 0){{round($tsd->Oct,1)}}@endif</td>
                            <td>@if($tsd->Nov != 0){{round($tsd->Nov,1)}}@endif</td>
                            <td>@if($tsd->Dece != 0){{round($tsd->Dece,1)}}@endif</td>
                            <td>@if($tsd->Jan != 0){{round($tsd->Jan,1)}}@endif</td>
                            <td>@if($tsd->Feb != 0){{round($tsd->Feb,1)}}@endif</td>
                            <td>@if($tsd->Mar != 0){{round($tsd->Mar,1)}}@endif</td>
                            <td>@if($tsd->Mar != 0){{round($tsd->total,1)}}@endif</td>
                        </tr>      
                        <?php $curr_month = \App\Attendance::Select(DB::raw('max(Jun) as jun,max(Jul) as jul,max(Aug) as aug,max(Sept) as sept,max(Oct) as oct,max(Nov) as nov,max(Dece) as dece,max(Jan) as jan,max(Feb) as feb,max(Mar) as mar'))->first(); ?>
                        @foreach($info['att'] as $key=>$attend)
                        <tr>
                            <td style="text-align: left">
                                {{$attend->attendanceName}}
                            </td>                    
                            <td>@if($curr_month->jun != 0){{round($attend->Jun,1)}}@endif</td>
                            <td>@if($curr_month->jul != 0){{round($attend->Jul,1)}}@endif</td>
                            <td>@if($curr_month->aug != 0){{round($attend->Aug,1)}}@endif</td>
                            <td>@if($curr_month->sept != 0){{round($attend->Sept,1)}}@endif</td>
                            <td>@if($curr_month->oct != 0){{round($attend->Oct,1)}}@endif</td>
                            <td>@if($curr_month->nov != 0){{round($attend->Nov,1)}}@endif</td>
                            <td>@if($curr_month->dece != 0){{round($attend->Dece,1)}}@endif</td>
                            <td>@if($curr_month->jan != 0){{round($attend->Jan,1)}}@endif</td>
                            <td>@if($curr_month->feb != 0){{round($attend->Feb,1)}}@endif</td>
                            <td>@if($curr_month->mar != 0){{round($attend->Mar,1)}}@endif</td>                        
                            <td>@if($curr_month->mar != 0){{round($attend->Nov+$attend->Dece+$attend->Jan+$attend->Feb+$attend->Mar+$attend->Jun+$attend->Jul+$attend->Aug+$attend->Sep+$attend->Oct,1)}}@endif</td>

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

                               @if($teacher != null)
                               <span>{{$teacher->adviser}}</span>
                               @endif
                                                        <br><span>Class Adviser</span></div>
                                <br>
                </td>
            </tr>  
            <tr>
                <td colspan="2" style="padding-left: 0px;padding-right: 0px;">            
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
                            Admitted in:____________________
                        </td>                                                    
                    </tr>
                    <tr>
                        <td class="print-size" >admission to:___________________</td>
                        <td class="print-size" >Grade:_________ Date:___________</td>                                                    
                    </tr>                       
                    <tr>
                        <td class="print-size" >Date of Issue:___________________</td>
                        <td></td>                                                    
                    </tr>
                    <tr>
                        <td colspan="2"><br><br><br></td>                                                    
                    </tr>
                                                                    <tr style="text-align: center">
                        <td class="print-size"></td>
                        <td class="print-size" ><div style="border-bottom: 1px solid;width: 80%;margin-left: auto;margin-right: auto;height:36px"><img src="{{asset('images/elem_sig.png')}}"  style="display: inline-block;width:180px;"></div></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td class="print-size" >

                        </td>
                        <td class="print-size" >Mrs. Ma.Dolores F. Bayocboc</td>
                    </tr>
                    <tr style="text-align: center">
                        <td class="print-size" ></td>
                        <td class="print-size" ><b>Grade School - Principal</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        
    </table>

        <br>
        <br>
        <br>
        <div style="text-align: right;padding-left: 0px"><b>{{$info['info']->idno}}</b></div>
    <div class="page-break"></div>
    </div>
        <script type="text/javascript">
            var widths = document.getElementById('cardHeader{{$card}}').offsetWidth;
            var bodywidth = document.getElementById('body').offsetWidth;
            
            bodywidth = bodywidth/2
            widths = (widths+150)/2
            
            var placement = bodywidth - widths;
            document.getElementById("cardHeader{{$card}}").style.marginLeft = placement+"px";
        </script>        
        <?php $card++; ?>

    @endforeach
        
     </div>

        <!--script type="text/javascript">
            var sides = "side";
            if(sides == "back"){
                $( ".front" ).each(function() {
                  $(this).addClass("hide");
                });                
            }else{
                $( ".back" ).each(function() {
                  $(this).addClass("hide");
                });                  
            }           

        </script-->
    </body>
</html>
