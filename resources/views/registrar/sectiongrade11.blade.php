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
            <?php $card = 1;?>    
        @foreach($collection as $info)
        <div class="front" style="padding-top: 50px;">
        <table class="parent" width="100%" style="padding:10px;margin-left: auto;margin-right: auto;margin-bottom: .8cm;">
            <thead>
            <tr>
                <td style="padding-left: 0px;">
                    <table class="head"  border="0" cellpadding="0" cellspacing="0" style="margin-left:43px;">

                    <tr>
                        <td rowspan="7" style="text-align: right;padding-left: 0px;width: 140px;vertical-align: top" class="logo" >
                            <img src="{{asset('images/DBTI.png')}}"  style="display: inline-block;width:120px">
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
                                <b>{{$info['info']->lastname}}, {{$info['info']->firstname}} {{$info['info']->middlename}} {{$info['info']->extensionname}}</b>
                            </td>
                            <td width="15%" style="font-size:10pt;padding-left: 0px;">
                                <b>Student No:</b>
                            </td>
                            <td width="25%" style="font-size:10pt;padding-left: 0px;">
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
                                <b>Age:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$info['info']->age}}
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
                                <b>Sex:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;vertical-align: top;">
                                {{$info['info']->gender}}
                            </td>
                            <td colspan="2" style="font-size:10pt;padding-left: 0px;"  >
                                <b style="display: inline-block;vertical-align: top;">Adviser:&nbsp;</b><div style="display: inline-block;width: 75%;">{{$teacher->adviser}}</div>
                            </td>

                        </tr>
                        <tr><td style="font-size:5pt;">&nbsp;</td></tr>
                        <tr>
                            <td colspan="4" style="font-size:10pt;padding-left: 0px;">
                                <b>ACADEMIC TRACK</b>:@if($info['info']->strand == "STEM")
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
                    
                        <tr>@if($sem ==1)
                                <td width="15%" style="text-align:center"><b>FIRST</b></td>
                                <td width="15%" style="text-align:center"><b>SECOND</b></td>
                            @else
                                <td width="15%" style="text-align:center"><b>THIRD</b></td>
                                <td width="15%" style="text-align:center"><b>FOURTH</b></td>                            
                            @endif
                        </tr>
                    <tr>
                        <td style="text-align:center"><b>Core Subjects</b></td>
                        <td colspan="3"></td>
                    </tr>
                    {{--*/$first=0/*--}}
                    {{--*/$second=0/*--}}                    
                    {{--*/$count=0/*--}}
                    {{--*/$totalacad=0/*--}}
                    @foreach($info['core'] as $key=>$core)
                    {{--*/$final=0/*--}}
                    <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left;padding-left: 10px">
                            {{$core->subjectname}}
                        </td>
                        <td>@if($sem ==1)
                                @if(round($core->first_grading,2) != 0)
                                {{round($core->first_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($core->first_grading,2)/*--}}
                            @else
                                @if(round($core->third_grading,2) != 0)
                                {{round($core->third_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($core->third_grading,2)/*--}}
                            @endif
                        </td>
                        <td>
                            @if($sem ==1)
                                @if(round($core->second_grading,2) != 0)
                                {{round($core->second_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($core->second_grading,2)/*--}}
                            @else
                                @if(round($core->fourth_grading,2) != 0)
                                {{round($core->fourth_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($core->fourth_grading,2)/*--}}
                            @endif
                        </td >
                        <td>
                            @if(round($core->second_grading,2) != 0)
                            {{round((round($core->second_grading,2)+round($core->first_grading,2))/2,0)}}
                            @endif
                            @if($sem ==1)
                                <?php $final = $final + round((round($core->first_grading,2)+round($core->second_grading,2))/2,0);
                                ?>
                            @else
                                <?php $final = $final + round((round($core->third_grading,2)+round($core->fourth_grading,2))/2,0);
                                ?>
                            @endif
                                <?php $totalacad = $totalacad + $final;?>
                            {{$final}}
                        </td>

                            {{--*/$count ++/*--}}                        
                    </tr>
                    @endforeach
                    
                    <tr>
                        <td style="text-align:center"><b>Applied and Specialized Subjects</b></td>
                        <td colspan="3"></td>
                    </tr>                    
                    
                    @foreach($info['spec'] as $key=>$spec)
                    {{--*/$final=0/*--}}
                    <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left;padding-left: 10px">
                            {{$spec->subjectname}}
                        </td>
                        <td>
                            @if($sem ==1)
                                @if(round($spec->first_grading,2) != 0)
                                {{round($spec->first_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($spec->first_grading,2)/*--}}
                            @else
                                @if(round($spec->third_grading,2) != 0)
                                {{round($spec->third_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($spec->third_grading,2)/*--}}
                            @endif
                        </td>
                        
                        <td>@if($sem ==1)
                                @if(round($spec->second_grading,2) != 0)
                                {{round($spec->second_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($spec->second_grading,2)/*--}}
                            @else
                                @if(round($spec->fourth_grading,2) != 0)
                                {{round($spec->fourth_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($spec->fourth_grading,2)/*--}}
                            @endif
                        </td >
                        <td>
                            @if(round($spec->second_grading,2) != 0)
                            {{round((round($spec->second_grading,2)+round($spec->first_grading,2))/2,0)}}
                            @endif
                            @if($sem ==1)
                                <?php $final = $final + round((round($spec->first_grading,2)+round($spec->second_grading,2))/2,0);
                                ?>
                            @else
                                <?php $final = $final + round((round($spec->third_grading,2)+round($spec->fourth_grading,2))/2,0);
                                ?>
                            @endif
                                <?php $totalacad = $totalacad + $final;?>
                            {{$final}}
                        </td>

                            {{--*/$count ++/*--}}                        
                    </tr>                    
                    @endforeach
                    <tr style="text-align: center">
                        <td></td>
                        <td colspan="2" style="text-align: right;padding-right: 10px">
                            <b>GENERAL AVERAGE for the Semester</b>
                        </td>
                        <td>@if(round($totalacad/$count,0) != 0)
                            <b>{{round($totalacad/$count,0)}}</b>
                            @endif
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
                <table border = '0' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                    <tr>
                        <td width="30%" style="padding-top: 15px;padding-bottom: 15px;border:1px solid"><b>CONDUCT CRITERIA</b></td>
                        <td width="10%" style="border:1px solid"><b>Points</b></td>
                        @if($sem ==1)
                        <td width="10%" style="border:1px solid"><b>1</b></td>
                        <td width="10%" style="border:1px solid"><b>2</b></td>
                        @else
                        <td width="10%" style="border:1px solid"><b>3</b></td>
                        <td width="10%" style="border:1px solid"><b>4</b></td>
                        @endif
                        <td width="20%" rowspan="{{count($info['con'])}}"></td>
                    </tr>
                        {{--*/$first=0/*--}}
                        {{--*/$second=0/*--}}
                        {{--*/$counter = 0/*--}}
                        {{--*/$length = count($info['con'])/*--}}
                        @foreach($info['con'] as $key=>$conducts)
                        {{--*/$counter ++/*--}}                    
                    <tr>
                        <td style="text-align: left;padding-left: 10px;border: 1px solid">{{$conducts->subjectname}}</td>
                        <td style="border:1px solid">{{$conducts->points}}</td>
                        @if($sem ==1)
                            <td style="border:1px solid">
                                @if(!round($conducts->first_grading,2)==0)
                                {{round($conducts->first_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($conducts->first_grading,2)/*--}}
                            </td>
                            <td style="border:1px solid">
                                @if(!round($conducts->second_grading,2)==0)
                                {{round($conducts->second_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($conducts->second_grading,2)/*--}}
                            </td>
                        @else
                            <td style="border:1px solid">
                                @if(!round($conducts->third_grading,2)==0)
                                {{round($conducts->third_grading,2)}}
                                @endif
                                {{--*/$first = $first + round($conducts->third_grading,2)/*--}}
                            </td>
                            <td style="border:1px solid">
                                @if(!round($conducts->fourth_grading,2)==0)
                                {{round($conducts->fourth_grading,2)}}
                                @endif
                                {{--*/$second = $second + round($conducts->fourth_grading,2)/*--}}
                            </td>
                        @endif

                        @if($length == $counter)
                        <td style="border:1px solid"><b>FINAL GRADE</b></td>
                        @endif
                        

                    </tr>
                        @endforeach                    
                        <tr>
                            <td style="text-align:center;border:1px solid"><b>CONDUCT GRADE</b></td>
                            <td style="border:1px solid"><b>100</b></td>
                            <td style="border:1px solid"><b>@if(!$first == 0){{$first}}@endif</b></td>
                            <td style="border:1px solid"><b>@if(!$second == 0){{$second}}@endif</b></td>
                            <td style="border:1px solid"><b>@if($second != 0){{round(($first+$second)/2,0)}}@endif</b></td>
                            
                        </tr>
                </table>
                <br>
                <!--table border="1" cellspacing="0" cellpading="0" style="font-size:12px;text-align: center" width="100%">
                    <tr>
                        <td width="40%"><b>ATTENDANCE</b></td>
                        <td width="10%"><b>1</b></td>
                        <td width="10%"><b>2</b></td>
                        <td width="10%"><b>3</b></td>
                        <td width="10%"><b>4</b></td>
                        <td width="20%"><b>TOTAL</b></td>
                    </tr>
                    <tr>
                        <td>
                            Days of School
                        </td>
                        {{--*/$first=0/*--}}
                        {{--*/$second=0/*--}}
                        {{--*/$third=0/*--}}
                        {{--*/$fourth=0/*--}}
                            @foreach($info['att'] as $key=>$attend)
                                @if($attend->subjectcode != "DAYT")
                                    {{--*/$first = $first + $attend->first_grading/*--}}
                                    {{--*/$second = $second + $attend->second_grading/*--}}
                                    {{--*/$third = $third + $attend->third_grading/*--}}
                                    {{--*/$fourth = $fourth + $attend->fourth_grading/*--}}
                                @endif
                            @endforeach
                        <td>
                            @if($first != 0)
                            {{$first}}
                            @endif
                        </td>
                        <td>
                            @if($second != 0)                            
                            {{$second}}
                            @endif
                        </td>
                        <td>
                            @if($third != 0)                            
                            {{$third}}
                            @endif
                        </td>                                                    
                        <td>
                            @if($fourth != 0)                            
                            {{$fourth}}
                            @endif
                        </td>
                        <td>
                            {{$first+$second+$third+$fourth}}
                        </td>                                                   
                    </tr>
                    @foreach($info['att'] as $key=>$attend)
                    <tr>
                        <td>
                            {{$attend->subjectname}}
                        </td>
                        <td>
                            @if($first != 0)
                            {{round($attend->first_grading,1)}}
                            @endif
                        </td>
                        <td>
                            @if($second != 0)
                            {{round($attend->second_grading,1)}}
                            @endif
                        </td>
                        <td>
                            @if($third != 0)
                            {{round($attend->third_grading,1)}}
                            @endif
                        </td>
                        <td>
                            @if($fourth != 0)
                            {{round($attend->fourth_grading,1)}}
                            @endif
                        </td>
                        <td>
                            {{round($attend->first_grading,1)+round($attend->second_grading,1)+round($attend->third_grading,1)+round($attend->fourth_grading,1)}}
                        </td>                                                    
                    </tr>
                    @endforeach
                </table-->
                
                <table border='1' cellpadding='0' cellspacing='0' width="100%" style="text-align: center;font-size:11px;">
                    <tr style="font-size:12px;">
                        <td style="padding-bottom:5px;padding-top:5px">
                            <b>ATTENDANCE</b>
                        </td>
                        @if($sem == 2)
                        <td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td>
                        @else
                        <td>Jun</td><td>Jul</td><td>Aug</td><td>Sept</td><td>Oct</td>
                        @endif
                        <td>TOTAL</td>
                    </tr>
                    <tr style="font-size:11px;">
                        <td style="text-align: left">Days of School</td>
                        <?php $tsd  = \App\CtrAttendance::Select(DB::raw('*,Jun+Jul+Aug+Sept+Oct as sem1,Nov+Dece+Jan+Feb+Mar as sem2'))->where('schoolyear',$schoolyear->schoolyear)->where('level',$level)->first();?>
                            @if($sem == 2)
                                <td>@if($tsd->Nov != 0){{round($tsd->Nov,1)}}@endif</td>
                                <td>@if($tsd->Dece != 0){{round($tsd->Dece,1)}}@endif</td>
                                <td>@if($tsd->Jan != 0){{round($tsd->Jan,1)}}@endif</td>
                                <td>@if($tsd->Feb != 0){{round($tsd->Feb,1)}}@endif</td>
                                <td>@if($tsd->Mar != 0){{round($tsd->Mar,1)}}@endif</td>
                                <td>@if($tsd->Mar != 0){{round($tsd->sem2,1)}}@endif</td>
                            @else
                                <td>@if($tsd->Jun != 0){{round($tsd->Jun,1)}}@endif</td>
                                <td>@if($tsd->Jul != 0){{round($tsd->Jul,1)}}@endif</td>
                                <td>@if($tsd->Aug != 0){{round($tsd->Aug,1)}}@endif</td>
                                <td>@if($tsd->Sept != 0){{round($tsd->Sept,1)}}@endif</td>
                                <td>@if($tsd->Oct != 0){{round($tsd->Oct,1)}}@endif</td>
                                <td>@if($tsd->Oct != 0){{round($tsd->sem1,1)}}@endif</td>
                            @endif
                            
                            
                    </tr>     
                    <?php $curr_month = \App\Attendance::Select(DB::raw('max(Jun) as jun,max(Jul) as jul,max(Aug) as aug,max(Sept) as sept,max(Oct) as oct,max(Nov) as nov,max(Dece) as dece,max(Jan) as jan,max(Feb) as feb,max(Mar) as mar'))->first(); ?>
                    @foreach($info['att'] as $key=>$attend)
                    <tr>
                        <td style="text-align: left">
                            {{$attend->attendanceName}}
                        </td>
                        @if($sem == 2)
                        <td>@if($curr_month->nov != 0){{round($attend->Nov,1)}}@endif</td>
                        <td>@if($curr_month->dece != 0){{round($attend->Dece,1)}}@endif</td>
                        <td>@if($curr_month->jan != 0){{round($attend->Jan,1)}}@endif</td>
                        <td>@if($curr_month->feb != 0){{round($attend->Feb,1)}}@endif</td>
                        <td>@if($curr_month->mar != 0){{round($attend->Mar,1)}}@endif</td>                        
                        <td>@if($curr_month->mar != 0){{round($attend->Nov+$attend->Dece+$attend->Jan+$attend->Feb+$attend->Mar,1)}}@endif</td>
                        @else
                        <td>@if($curr_month->jun != 0){{round($attend->Jun,1)}}@endif</td>
                        <td>@if($curr_month->jul != 0){{round($attend->Jul,1)}}@endif</td>
                        <td>@if($curr_month->aug != 0){{round($attend->Aug,1)}}@endif</td>
                        <td>@if($curr_month->sept != 0){{round($attend->Sept,1)}}@endif</td>
                        <td>@if($curr_month->oct != 0){{round($attend->Oct,1)}}@endif</td>
                        <td>@if($curr_month->oct != 0){{round($attend->Jun+$attend->Jul+$attend->Aug+$attend->Sept+$attend->Oct,1)}}@endif</td>
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
                                                    
                           @if($teacher != null)
                           <span>{{$teacher->adviser}}</span>
                           @endif
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
                        @if(round($totalacad/$count,0) >= 75)
                        <?php  $levelup = intval(str_replace("Grade","",$level));
                                $newlevel = $levelup + 1;
                        ?>
                        <td class="print-size" >admission to:<div style="display: inline-block;border-bottom: 1px solid;height: 16px;width: 145px;text-align: center;"><i>Grade {{$newlevel}}</i></div></td>
                        @else
                        <td class="print-size" >admission to:___________________</td>
                        @endif
                        <td class="print-size" >Grade:__________________________</td>
                    </tr>
                    <tr>
                        <td class="print-size" >Retained in ____________________</td>
                        <td class="print-size" >Date ___________________________</td>
                    </tr>
                    <tr>
			@if(round($totalacad/$count,0) >= 75)
                         <td class="print-size" >Date of Issue:<div style="display: inline-block;border-bottom: 1px solid;height: 16px;width: 145px;text-align: center;"><i>April 11, 2017</i></div></td>
                        @else
                        <td class="print-size" >Date of Issue:__________________</td>
                        @endif
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
        <div style="text-align: right;padding-left: 0px"><b>{{$info['info']->idno}}</b></div>
    <div class="page-break"></div>
    </div>
        <script type="text/javascript">
            var widths = document.getElementById('cardHeader{{$card}}').offsetWidth;
            var bodywidth = document.getElementById('body').offsetWidth;
            
            bodywidth = bodywidth/2
            widths = (widths+120)/2
            
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