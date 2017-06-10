<html>
    <head>


        <style type='text/css'>
           table tr td{font-size:10pt;}
           body{
                font-family: calibri;
                margin-left: auto;
                
            }
            .row_2{
                    -webkit-column-count: 3; /* Chrome, Safari, Opera */
                    -moz-column-count: 3; /* Firefox */
                    column-count: 3;
                    column-fill: auto;
            }
            td{vertical-align:top}
        </style>    
        <style type="text/css" media="print">
                       body{
                font-family: calibri;
                margin-left: auto;
                margin-right: none;
            }
        </style>
                <link href="{{ asset('/css/print.css') }}" rel="stylesheet">
               
    </head>
    <body>
        @foreach($collection as $info)
        
        <table border="1" width="100%">
            <tr>
                <td>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="right">

                    <tr>
                        <td rowspan="4" style="text-align: right;" class="logo" width="55px">
                            <img src="{{asset('images/logo.png')}}"  style="display: inline-block;width:50px">
                        </td>
                        <td>
                            <span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span>
                        </td>
                    </tr>
                    <tr><td style="font-size:10pt;">Chino Roces Ave., Makati City </td></tr>
                    <tr><td style="font-size:10pt;">PAASCU Accredited</td></tr>
                    <tr><td style="font-size:10pt;">School Year 2015 - 2016</td></tr>
                    <tr><td style="font-size:4pt;">&nbsp; </td></tr>
                    <tr><td><span style="font-size"></td></tr>
                    <tr>
                        <td colspan="2">
                    <div style="text-align: center;font-size:10pt;"><b>STUDENT PROGRESS REPORT CARD</b></div>
                    <div style="text-align: center;font-size:10pt;"><b>GRADE SCHOOL DEPARTMENT</b></div>

                        </td>
                    </tr>
                    </table>                    
                </td>
            </tr>    
            <tr>
                <td>
                    <table width="100%" border = '0' cellpacing="0" cellpadding = "0">
                        <tr>
                            <td width="15%" style="font-size:8pt;">
                                <b>Name:</b>
                            </td>
                            <td width="45%" style="font-size:8pt;">
                                {{$info['info']->lastname}}, {{$info['info']->firstname}} {{$info['info']->middlename}} {{$info['info']->extensionname}}
                            </td>
                            <td width="15%" style="font-size:8pt;">
                                Student No:
                            </td>
                            <td width="25%" style="font-size:8pt;">
                                {{$info['info']->idno}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:8pt;">
                                <b>Gr. and Sec:</b>
                            </td>
                            <td style="font-size:8pt;">
                                {{$level}} - {{$section}}
                            </td>
                            <td style="font-size:8pt;">
                                Class No:
                            </td>
                            <td style="font-size:8pt;">

                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:8pt;">
                                <b>Age:</b>
                            </td>
                            <td style="font-size:8pt;">
                                {{$info['info']->age}}
                            </td>
                            <td style="font-size:8pt;"  >
                                LRN:
                            </td>
                            <td style="font-size:8pt;">
                                {{$info['info']->lrn}}
                            </td>
                        </tr>
                    </table>                     
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="33.333333333%">
                @if(sizeOf($info['aca'])!= 0)
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports">
                    <tr><td colspan="6" align="center">QUARTERLY GRADES</td></tr>
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
                        <td style="text-align: left">
                            {{ucwords(strtolower($academics->subjectname))}}
                        </td>
                        <td>
                            {{round($academics->first_grading,2)}}
                            {{--*/$first = $first + round($academics->first_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($academics->second_grading,2)}}
                            {{--*/$second = $second + round($academics->second_grading,2)/*--}}
                        </td >
                        <td>
                            {{round($academics->third_grading,2)}}
                            {{--*/$third = $third + round($academics->third_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($academics->fourth_grading,2)}}
                            {{--*/$fourth = $fourth + round($academics->fourth_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($academics->final_grade,2)}}
                            {{--*/$final = $final + round($academics->final_grade,2)/*--}}
                        </td>
                            {{--*/$count ++/*--}}
                                                 
                    </tr>
                    @endforeach
                    <tr style="text-align: center">
                        <td style="text-align: right;">
                            <b>ACADEMIC AVERAGE</b>
                        </td>
                        <td>
                            {{round($first/$count,2)}}
                        </td>
                        <td>{{round($second/$count,2)}}
                        </td>
                        <td>
                            {{round($third/$count,2)}}
                        </td>
                        <td>
                            {{round($fourth/$count,2)}}
                        </td>
                        <td>
                            {{round($final/$count,2)}}
                        </td>
                    </tr>
                </table>
                @endif
                <br>
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                    <tr><td colspan="3"><b>ACADEMIC DESCRIPTORS</b></td></tr>
                    <tr style="font-weight:bold;">
                        <td width="36%" class="descriptors">
                            DESCRIPTOR
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
                <table border="1" cellspacing="0" cellpading="0" style="font-size:12px;text-align: center" width="100%">
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
                                {{--*/$first = $first + $attend->first_grading/*--}}
                                {{--*/$second = $second + $attend->second_grading/*--}}
                                {{--*/$third = $third + $attend->third_grading/*--}}
                                {{--*/$fourth = $fourth + $attend->fourth_grading/*--}}
                            @endforeach
                        <td>

                            {{$first}}
                        </td>
                        <td>
                            {{$second}}
                        </td>
                        <td>
                            {{$third}}
                        </td>                                                    
                        <td>
                            {{$fourth}}
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
                            {{round($attend->first_grading,0)}}
                        </td>
                        <td>
                            {{round($attend->second_grading,0)}}
                        </td>
                        <td>
                            {{round($attend->third_grading,0)}}
                        </td>
                        <td>
                            {{round($attend->fourth_grading,0)}}
                        </td>
                        <td>
                            {{round($attend->first_grading,0)+round($attend->second_grading,0)+round($attend->third_grading,0)+round($attend->fourth_grading,0)}}
                        </td>                                                    
                    </tr>
                    @endforeach
                </table>
                </td>
                
                <td width="33.333333333%">
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                    <tr>
                        <td width="30%">CONDUCT CRITERIA</td>
                        <td width="10%">Points</td>
                        <td width="10%">1</td>
                        <td width="10%">2</td>
                        <td width="10%">3</td>
                        <td width="10%">4</td>
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
                        <td style="text-align: left">{{$conducts->subjectname}}</td>
                        <td>{{$conducts->points}}</td>
                        <td>
                            {{round($conducts->first_grading,2)}}
                            {{--*/$first = $first + round($conducts->first_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($conducts->second_grading,2)}}
                            {{--*/$second = $second + round($conducts->second_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($conducts->third_grading,2)}}
                            {{--*/$third = $third + round($conducts->third_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($conducts->fourth_grading,2)}}
                            {{--*/$fourth = $fourth + round($conducts->fourth_grading,2)/*--}}
                        </td>
                    </tr>
                        @endforeach                    
                        <tr>
                            <td>CONDUCT GRADE</td>
                            <td>100</td>
                            <td>{{$first}}</td>
                            <td> {{$second}}</td>
                            <td>{{$third}}</td>
                            <td>{{$fourth}}</td>
                        </tr>
                        <tr>
                            <td>FINAL GRADE</td>
                            <td colspan="5">{{round(($first+$second+$third+$fourth)/4,2)}}</td>
                        </tr>
                </table>
                <br>
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                        <tr style="font-weight:bold;">
                            <td width="36%" class="descriptors">
                                DESCRIPTOR
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
                            <td>Failed</td><td>Failed</td><td>75 and Below</td>
                        </tr>
                    </table>  
                    <br>
                    <table border="1" width="100%">
                        <tr><td colspan="2">PHYSICAL EDUCATION</td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Physical Education")
                        <tr>
                            <td width="70%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>                                                            
                </td>
                <td width="33.333333333%">
                    <table border="1" width="100%">
                        <tr><td colspan="2">CHRISTIAN LIVING</td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Christian Living")
                        <tr>
                            <td width="70%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>                    
                    <br>
                    <table border="1" width="100%">
                        <tr><td colspan="2">ART EDUCATION</td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Art Education")
                        <tr>
                            <td width="70%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>                                        
                </td>
            </tr>
            
        </table>
        <table>
            <tr>
                <td width="33.333333333%">
                    <table border="1" width="100%">
                        <tr><td colspan="2">ENGLISH</td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "English")
                        <tr>
                            <td width="70%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>   
                    </td>
                    <td width="33.333333333%">
                        <div class="fil_">
                    <table border="1" width="100%">
                        <tr><td colspan="2">FILIPINO</td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Filipino")
                        <tr>
                            <td width="70%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                            <div>
                    </td>
                    <td width="33.333333333%">
                    <table border="1" width="100%">
                        <tr><td colspan="2">MATHEMATICS</td></tr>
                        @foreach($info['comp'] as $key=>$comp)
                        @if($comp->subject == "Mathematics")
                        <tr>
                            <td width="70%">{{$comp->description}}</td>
                            <td>{{$comp->value}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </table>                                                             
                </td>
            </tr>
        </table>
                        
        <div class="page-break"></div>
        @endforeach

        <>
    </body>
</html>