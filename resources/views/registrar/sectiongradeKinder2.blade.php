<html>
    <head>
        <script src="{{asset('/js/jquery.js')}}"></script>        
        <style type='text/css'>
            
            .hide{
                display:none;
            }            
            @if($quarter != 4)
           .padded tr td{
               padding-top: 7px;
               padding-bottom: 7px;
           }
           @endif
            @if($quarter == 4)
           .padded tr td{
               padding-top: 2px;
               padding-bottom: 2px;
           }
           @endif           

           table tr td{font-size:10pt;}
           body{
                font-family: calibri;
                margin-left: auto;
                width:11in;
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
        
        @foreach($collection as $info)       
        <div class="back">
        <table style="margin-top: 55px;margin-bottom:30px;margin-left: .5cm;margin-right:.5cm">
            <tr>
                <td style="width:8.33cm" id="init_{{$info['info']->idno}}">
                    @if(sizeOf($info['aca'])!= 0)
                    <table class="padded" border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports" style="margin-top: auto;margin-bottom: auto;">
                        <tr><td colspan="6" align="center"><b>QUARTERLY GRADES</b></td></tr>
                        <tr style="font-weight: bold;text-align:center;">
                            <td width="35%" style="padding: 15px 0 15px 0;">LEARNING AREAS</td>
                            <td width="10%">1st</td>
                            <td width="10%">2nd</td>
                            <td width="10%">3rd</td>
                            <td width="10%">4th</td>
                            <td width="12%">FINAL RATING</td>
                        </tr>
                        {{--*/$first=0/*--}}
                        {{--*/$second=0/*--}}
                        {{--*/$third=0/*--}}
                        {{--*/$fourth=0/*--}}
                        {{--*/$final=0/*--}}
                        {{--*/$count=0/*--}}
                        @foreach($info['aca'] as $key=>$academics)
                        <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left;padding-left:5px;">
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
                                {{--*/$count ++/*--}}

                        </tr>
                        @endforeach
                        <tr style="text-align: center">
                            <td style="text-align: right;padding-right:10px">
                                <b>ACADEMIC AVERAGE</b>
                            </td>
                        <td>
                            @if(round($first/$count,2) != 0)
                            <b>{{round($first/$count,2)}}</b>
                            @endif
                        </td>
                        <td>
                            @if(round($second/$count,2) != 0)
                            <b>{{round($second/$count,2)}}</b>
                            @endif
                        </td>
                        <td>
                            @if(round($third/$count,2) != 0)
                            <b>{{round($third/$count,2)}}</b>
                            @endif
                        </td>
                        <td>
                            @if(round($fourth/$count,2) != 0)
                            <b>{{round($fourth/$count,2)}}</b>
                            @endif
                        </td>
                        <td>
                            @if(round($fourth/$count,2) != 0)
                            {{round($final/$count,2)}}
                            @endif
                        </td>
                        </tr>
                    </table>
                    @endif
                    <br>
                    <table class="padded" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;margin-top: auto;margin-bottom: auto;">
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
                <td style="width:1cm"></td>
                <td style="width:8.33cm" id="com1_{{$info['info']->idno}}">
                    <div id="con_{{$info['info']->idno}}">        
                    <table class="padded" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;margin-top: auto;margin-bottom: auto;">
                        <tr>
                            <td width="30%"><b>CONDUCT CRITERIA</b></td>
                            <td width="9%"><b>Points</b></td>
                            <td width="9%"><b>1</b></td>
                            <td width="9%"><b>2</b></td>
                            <td width="9%"><b>3</b></td>
                            <td width="9%"><b>4</b></td>
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
                            <td style="text-align: left;padding-left:10px">{{$conducts->subjectname}}</td>
                            <td>{{$conducts->points}}</td>
                        <td>
                            @if(!round($conducts->first_grading,2)==0)
                            {{round($conducts->first_grading,2)}}
                            @endif
                            {{--*/$first = $first + round($conducts->first_grading,2)/*--}}
                        </td>
                        <td>
                            @if(!round($conducts->second_grading,2)==0)
                            {{round($conducts->second_grading,2)}}
                            @endif
                            {{--*/$second = $second + round($conducts->second_grading,2)/*--}}
                        </td>
                        <td>
                            @if(!round($conducts->third_grading,2)==0)
                            {{round($conducts->third_grading,2)}}
                            @endif
                            {{--*/$third = $third + round($conducts->third_grading,2)/*--}}
                        </td>
                        <td>
                            @if(!round($conducts->fourth_grading,2)==0)
                            {{round($conducts->fourth_grading,2)}}
                            @endif
                            {{--*/$fourth = $fourth + round($conducts->fourth_grading,2)/*--}}
                        </td>
                        </tr>
                            @endforeach                    
                            <tr>
                            <td><b>CONDUCT GRADE</b></td>
                            <td><b>100</b></td>
                            <td><b>@if(!$first == 0){{$first}}@endif</b></td>
                            <td><b>@if(!$second == 0){{$second}}@endif</b></td>
                            <td><b>@if(!$third == 0){{$third}}@endif</b></td>
                            <td><b>@if(!$fourth == 0){{$fourth}}@endif</b></td>
                            
                            
                            
                        </tr>
                            <tr>
                                <td><b>FINAL GRADE</b></td>
                                <td colspan="5">@if($fourth != 0){{round(($first+$second+$third+$fourth)/4,2)}}@endif</td>
                            </tr>
                    </table>
                        <br>
                    </div>
                    
                    <table class="padded" border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                        <tr><td colspan="3"><b>CONDUCT DESCRIPTORS</b></td></tr>
                            <tr style="font-weight:bold;@if ($quarter == 4)height: 48px;@endif">
                                <td width="36%" class="descriptors">
                                    DESCRIPTORS
                                </td>
                                <td width="32%" class="scale">
                                    GRADING SCALE
                                </td>            
                                <td width="32%" class="remarks">
                                    NUMERIC EQUIVALENCE
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
                                <td 
                                    @if ($quarter != 4)
                                    style="padding-top: 15px;padding-bottom: 15px"
                                    @endif
                                    >Failed</td><td>Failed</td><td>75 and Below</td>
                            </tr>
                        </table>
                    <br>
                </td>
                <td style="width:1cm"></td>
                <td style="width:8.33cm" id="com2_{{$info['info']->idno}}">
                    <div id="eng_{{$info['info']->idno}}">
                    <table border="1" width="100%" cellpadding="0" cellspacing="0">
                        <tr style="text-align: center"><td colspan="2" style="padding: 2px;"><b>ENGLISH</b></td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "English")
                        <tr>
                            <td width="80%" style="padding-left: 15px;">{{$comp->description}}</td>
                            <td style="text-align: center;vertical-align: middle;">{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                    <br>
                    </div>
                    <div id="art_{{$info['info']->idno}}" style="display:none">
                        <table border="1" cellspacing="0" cellpadding="0" width="100%" >
                            <tr style="text-align: center"><td colspan="2"><b>ART EDUCATION</b></td></tr>
                            @foreach($info['comp'] as $key=>$comp)
                            @if($comp->subject == "Art Education")
                            <tr>
                                <td width="80%" style="padding-left: 15px;">{{$comp->description}}</td>
                                <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                            </tr>
                            @endif
                            @endforeach
                        </table>
                        <br>
                    </div>
                    <div  id="chr_{{$info['info']->idno}}">
                        <table border="1" width="100%" cellpadding="0" cellspacing="0">
                            <tr style="text-align: center"><td colspan="2"><b>CHRISTIAN LIVING</b></td></tr>
                            @foreach($info['comp'] as $key=>$comp)
                            @if($comp->subject == "Christian Living")
                            <tr>
                                <td width="80%" style="padding-left: 15px;">{{$comp->description}}</td>
                                <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                            </tr>
                            @endif
                            @endforeach
                        </table>  
                        <br>
                    </div>                    
                    <div id="phy_{{$info['info']->idno}}" style="display:none">
                        <table border="1" cellspacing="0" cellpadding="0" width="100%" >
                            <tr style="text-align: center"><td colspan="2"><b>PHYSICAL EDUCATION</b></td></tr>
                                @foreach($info['comp'] as $key=>$comp)
                                @if($comp->subject == "Physical Education")
                                <tr>
                                    <td width="80%">{{$comp->description}}</td>
                                    <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                                </tr>
                                @endif
                                @endforeach
                        </table>
                        <br>
                    </div>
                    <div id="fil_{{$info['info']->idno}}">
                    <table border="1" width="100%" cellpadding="0" cellspacing="0">
                        <tr style="text-align: center;"><td colspan="2" style="padding: 2px;"><b>FILIPINO</b></td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Filipino")
                        <tr>
                            <td style="padding-left: 15px;" width="80%">{!!nl2br($comp->description)!!}</td>
                            <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                        <br>
                    </div>
                    <div  id="math_{{$info['info']->idno}}">
                        @if($quarter == 4)
                    <table border="1" width="100%" cellpadding="0" cellspacing="0">
                        <tr style="text-align:center"><td colspan="2"><b>MATHEMATICS</b></td></tr>
                        <tr>
                            <td>
                                I.Measurement<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A.Time
                            </td>
                            <td></td>
                        </tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->section == "1A")                        
                        <tr>
                            <td width="80%" style="padding-left: 20px;">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                        
                        <tr>
                            <td>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B. Days and Months
                            </td>
                            <td></td>
                        </tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->section == "1B")                        
                        <tr>
                            <td width="80%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                        
                        <tr>
                            <td>
                                II.Measurement<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A.Division
                            </td>
                            <td></td>
                        </tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->section == "2A")                        
                        <tr>
                            <td width="80%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                        
                        <tr>
                            <td>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B.Fraction
                            </td>
                            <td></td>
                        </tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->section == "2B")                        
                        <tr>
                            <td width="80%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach  
                        
                        <tr>
                            <td>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C. Statistics
                            </td>
                            <td></td>
                        </tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->section == "2C")                        
                        <tr>
                            <td width="80%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach                
                        
                        
                    </table>
                        @elseif($quarter == 2)
                    <table border="1" width="100%" cellpadding="0" cellspacing="0">
                        <tr style="text-align:center"><td colspan="2"><b>MATHEMATICS</b></td></tr>
                        <tr>
                            <td>
                                I. Numbers and Number Sense 
                            </td>
                            <td></td>
                        </tr>                        
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Mathematics")
                           @if($comp->section == "1A")
                        <tr>
                            <td width="80%" style="padding-left: 20px;">{{$comp->description}}</td>
                            <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                        </tr>
                        @endif
                            
                        @endif
                        @endforeach
                        <tr>
                            <td>
                                II. Patterns and Algebra
                            </td>
                            <td></td>
                        </tr>                        
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Mathematics")
                           @if($comp->section == "2A")
                        <tr>
                            <td width="80%" style="padding-left: 20px;">{{$comp->description}}</td>
                            <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                        </tr>
                        @endif
                            
                        @endif
                        @endforeach                        
                        
                    </table>                        
                        @else
                    <table border="1" width="100%" cellpadding="0" cellspacing="0">
                        <tr style="text-align:center"><td colspan="2"><b>MATHEMATICS</b></td></tr>
                                              
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Mathematics")
                           
                        <tr>
                            <td width="80%" style="padding-left: 20px;">{{$comp->description}}</td>
                            <td style="text-align: center;vertical-align: middle">{{$comp->value}}</td>
                        </tr>
                            
                        @endif
                        @endforeach
                        
                    </table>
                        @endif
                    <br>
                    </div>
                </td>
            </tr>
        </table>

        <div class="page-break"></div>
        </div>

        <div class="front">
        <table style="margin-top: 55px;margin-bottom:30px;margin-left: .5cm;margin-right:.5cm" align="center">
                    <tr>
                        <td style="width:8.33cm" id="com3_{{$info['info']->idno}}">
                            <div id="cert_{{$info['info']->idno}}">
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
                                   @if($teacher != null)
                                    {{$teacher->adviser}}
                                   @endif
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
                                        <td class="print-size" style="text-align: center">Grade  School Principal</td>
                                    </tr>                                    
                                </table>
                            </div>
                        </td>
                        <td style="width:1cm"></td>
                        <td style="width:8.33cm" id="com4_{{$info['info']->idno}}"></td>
                        <td style="width:1cm"></td>
                        <td style="width:8.33cm" id="front_{{$info['info']->idno}}" style="padding-left: 20px;padding-right: 20px">
                            <div style="text-align: center;">
                                <span style="font-size: 12pt;"><b>DON BOSCO TECHNICAL INSTITUTE</b></span><br>
                                <span style="font-size: 10pt;">Chino Roces Ave., Brgy. Pio del Pilar</span><br>
                                <span style="font-size: 10pt;">Makati City</span>
                                <div>
                                <img src="{{asset('images/DBTI.png')}}"  style="display: inline-block;width:200px;padding-top: 60px;padding-bottom: 60px">
                                <br>
                                
                                <br>
                                <span style="font-size: 10pt;font-weight: bold;text-align: center">GRADE SCHOOL DEPARTMENT</span><br>
                                <span style="font-size: 10pt;font-weight: bold;text-align: center">{{$schoolyear->schoolyear}} - {{intval($schoolyear->schoolyear)+1}}</span>
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
                            <div class="parent" style="border: 1px solid; padding: 20px 10px 50px;border-radius: 40px;">
                                <div style="text-align:center;font-size: 12pt;"><b>KINDERGARTEN</b></div>
                                <br>
                            <div><div style="display:inline-block;width:55px;vertical-align: top"><b>Name: </b></div><div style="display:inline-block;width:235px">{{strtoupper($info['info']->lastname)}}, {{ucwords($info['info']->firstname)}} {{ucwords($info['info']->middlename)}} {{ucwords($info['info']->extensionname)}}</div></div>
                            <div><div style="display:inline-block;width:55px;vertical-align: top"><b>ID No: </b></div><div style="display:inline-block;width:235px">{{$info['info']->idno}}</div></div>
                            <div><div style="display:inline-block;width:55px;"><b>LRN: </b></div>{{strtoupper($info['info']->lrn)}}</div>
                            <div><div style="display:inline-block;width:55px;"><b>Age: </b></div>{{strtoupper($info['info']->age)}}</div>
                            <div><div style="display:inline-block;width:55px;"><b>Section: </b></div>{{strtoupper($section)}}</div>
                            <div><div style="display:inline-block;width:55px;"><b>Adviser: </b></div>{{strtoupper($teacher->adviser)}}</div>
                            </div>
                        </td>
                    </tr>

        </table>
        </div>
            <script>
            @if($quarter == 1)
            $("#fil_{{$info['info']->idno}}").appendTo("#com2_{{$info['info']->idno}}");
            $("#eng_{{$info['info']->idno}}").appendTo("#com3_{{$info['info']->idno}}");
            $("#cert_{{$info['info']->idno}}").appendTo("#com4_{{$info['info']->idno}}");
            $("#chr_{{$info['info']->idno}}").appendTo("#com1_{{$info['info']->idno}}");
            @endif
            @if($quarter == 2)
            $("#fil_{{$info['info']->idno}}").prependTo("#com2_{{$info['info']->idno}}");
            $("#math_{{$info['info']->idno}}").prependTo("#com3_{{$info['info']->idno}}");
            $("#cert_{{$info['info']->idno}}").appendTo("#com4_{{$info['info']->idno}}");
            $("#eng_{{$info['info']->idno}}").prependTo("#com3_{{$info['info']->idno}}");
            $("#chr_{{$info['info']->idno}}").prependTo("#com2_{{$info['info']->idno}}");
            $("#phy_{{$info['info']->idno}}").appendTo("#com1_{{$info['info']->idno}}");
            @endif  
            @if($quarter == 3)
            $("#phy_{{$info['info']->idno}}").appendTo("#com1_{{$info['info']->idno}}");
            $("#chr_{{$info['info']->idno}}").prependTo("#com3_{{$info['info']->idno}}");
            $("#eng_{{$info['info']->idno}}").prependTo("#com3_{{$info['info']->idno}}");
            $("#cert_{{$info['info']->idno}}").appendTo("#com4_{{$info['info']->idno}}");
            @endif
            @if($quarter == 4)
               
               $("#math_{{$info['info']->idno}}").prependTo("#com3_{{$info['info']->idno}}"); 
               $("#phy_{{$info['info']->idno}}").prependTo("#com3_{{$info['info']->idno}}"); 
               //$("#fil_{{$info['info']->idno}}").appendTo("#com3_{{$info['info']->idno}}");
               $("#eng_{{$info['info']->idno}}").appendTo("#com1_{{$info['info']->idno}}"); 
               //$("#chr_{{$info['info']->idno}}").appendTo("#com3_{{$info['info']->idno}}"); 
               $("#con_{{$info['info']->idno}}").appendTo("#init_{{$info['info']->idno}}"); 
               $("#art_{{$info['info']->idno}}").appendTo("#com1_{{$info['info']->idno}}"); 
               $("#cert_{{$info['info']->idno}}").appendTo("#com4_{{$info['info']->idno}}"); 
               
            @endif
        </script>
        <div class="page-break"></div>        
        @endforeach
        
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
