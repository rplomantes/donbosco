<html>
    <head>
        <style type='text/css'>
           .top_header{width:70%}
           .logo{ width: 28%;}
           .descriptors{ width: 350px;}
           .scale{width:300px}
        </style>
        <style type='text/css' media='print'>
          table tr td{font-size:10pt}
         .top_header{width:100%}
         .logo{ width: 20%;}
         .descriptors{ width: 250px;}
         .scale{width:200px}         
         .page-break{page-break-after: always;}
         .print-size{
             font-size: 9pt;
         }
        </style>
        <link href="{{ asset('/css/print.css') }}" rel="stylesheet">
    </head>
    <body>
        <table cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <td rowspan="4" width="90px">
                        <img src="{{asset('images/logo.png')}}" style="display: inline-block; max-width:90px">
                    </td>
                    <td  colspan="2">
                        <span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span>
                    </td>
                </tr>
                <tr><td style="font-size:10pt;">Chino Roces Ave., Makati City </td><td style="text-align: right">STUDENT PROGRESS REPORT CARD</td></tr>
                <tr><td style="font-size:10pt;">PAASCU Accredited</td><td style="text-align: right">GRADE SCHOOL DEPARTMENT</td></tr>
                <tr><td  colspan="2" style="font-size:10pt;">School Year 2015 - 2016</td></tr>
            </thead>
            <tr><td colspan="3"><br></td></tr>
            <tr><td colspan="3">
                    <table width="100%" >
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <table width="100%" style="font-size:10pt" border = '0' cellpacing="0" cellpadding = "0">
                                                
                                                <tr>
                                                    <td width="100px" class="print-size">
                                                        <b>Name:</b>
                                                    </td>
                                                    <td width="50%" class="print-size">
                                                        {{$student[0]->lastname}}, {{$student[0]->firstname}} {{$student[0]->middlename}} {{$student[0]->extensionname}}
                                                    </td>
                                                    <td width="100px" class="print-size">
                                                        Student No:
                                                    </td>
                                                    <td class="print-size">
                                                        {{$idno}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">
                                                        <b>Gr. and Sec:</b>
                                                    </td>
                                                    <td class="print-size">
                                                        {{$student[0]->level}} - {{$student[0]->section}}
                                                    </td>
                                                    <td class="print-size">
                                                        Class No:
                                                    </td>
                                                    <td class="print-size">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">
                                                        <b>Adviser:</b>
                                                    </td>
                                                    <td class="print-size">
                                                        @if($teacher != null)
                                                        {{$teacher->adviser}}
                                                        @endif
                                                    </td>
                                                    <td class="print-size">
                                                        LRN No:
                                                    </td>
                                                    <td class="print-size">
                                                        {{$student[0]->lrn}}
                                                    </td>
                                                </tr>
                                            </table>                                                
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td width="48%">
                                            <br>
                                            @if(count($academic) != 0)
                                            <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports" style="font-size:12px;">
                                                <tr style="font-weight: bold;font-size: 10pt;text-align:center;">
                                                    <td class="print-size" width="40%" style="padding: 2px 2px 2px 2px;">SUBJECTS</td>
                                                    <td class="print-size" width="10%">1</td>
                                                    <td class="print-size" width="10%">2</td>
                                                    <td class="print-size" width="10%">3</td>
                                                    <td class="print-size" width="10%">4</td>
                                                    <td class="print-size" width="10%">FINAL RATING</td>
                                                    <td class="print-size" width="10%">REMARKS</td>
                                                </tr>
                                                {{--*/$first=0/*--}}
                                                {{--*/$second=0/*--}}
                                                {{--*/$third=0/*--}}
                                                {{--*/$fourth=0/*--}}
                                                {{--*/$final=0/*--}}
                                                {{--*/$count=0/*--}}
                                                @foreach($academic as $academics)
                                                <tr style="text-align: center">
                                                    <td style="text-align: left" class="print-size">
                                                        {{ucwords(strtolower($academics->subjectname))}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($academics->first_grading,2)}}
                                                        {{--*/$first = $first + round($academics->first_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($academics->second_grading,2)}}
                                                        {{--*/$second = $second + round($academics->second_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($academics->third_grading,2)}}
                                                        {{--*/$third = $third + round($academics->third_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($academics->fourth_grading,2)}}
                                                        {{--*/$fourth = $fourth + round($academics->fourth_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($academics->final_grade,2)}}
                                                        {{--*/$final = $final + round($academics->final_grade,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{$academics->remarks}}
                                                        {{--*/$count ++/*--}}
                                                    </td>                         
                                                </tr>
                                                @endforeach
                                                <tr style="text-align: center"><td class="print-size" style="text-align: right"><b>GENERAL AVERAGE</b></td><td class="print-size">{{round($first/$count,2)}}</td><td class="print-size">{{round($second/$count,2)}}</td><td class="print-size">{{round($third/$count,2)}}</td><td class="print-size">{{round($fourth/$count,2)}}</td><td class="print-size">{{round($final/$count,2)}}</td>
                                                    <td class="print-size">
                                                    {{round($final/$count,2) >= 75 ? "Passed":"Failed"}}    
                                                    </td></tr>
                                            </table>    
                                            @endif                                            
                                            <br>
                                            <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                                                <tr style="font-weight:bold;">
                                                    <td class="descriptors print-size">
                                                        DESCRIPTOR
                                                    </td>
                                                    <td class="scale print-size">
                                                        GRADING SCALE
                                                    </td>            
                                                    <td class="remarks print-size">
                                                        REMARKS
                                                    </td>                        
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Outstanding</td><td class="print-size">90 - 100</td><td class="print-size">Passed</td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Very Satisfactory</td><td class="print-size">85 - 89</td><td class="print-size">Passed</td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Satisfactory</td><td class="print-size">80 - 84</td><td class="print-size">Passed</td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Fairly Satisfactory</td><td>75 - 79</td><td>Passed</td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Did Not Meet Expectations</td><td>Below 75</td><td>Failed</td>
                                                </tr>
                                            </table>                                            
                                            <br>
                                            <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                                                <tr>
                                                    <td class="print-size" style="font-weight: bold;">
                                                        CHRISTIAN LIVING DESCRIPTORS
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size" width="60%" style="font-weight: bold;">
                                                        LEVEL OF "FRIENDSHIP WITH JESUS"
                                                    </td>
                                                    <td style="font-weight: bold" class="print-size">
                                                        GRADING SCALE
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Best Friend of Jesus</td>
                                                    <td class="print-size">95 - 100</td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Loyal Friend of Jesus</td>
                                                    <td class="print-size">89 - 94</td>
                                                </tr>     
                                                <tr>
                                                    <td class="print-size">Trustworthy Friend of Jesus</td>
                                                    <td class="print-size">83 - 88</td>
                                                </tr>
                                                <tr>
                                                    <td class="print-size">Good Friend of Jesus</td>
                                                    <td class="print-size">77 - 82</td>
                                                </tr>     
                                                <tr>
                                                    <td class="print-size">Common Friend of Jesus</td>
                                                    <td class="print-size">76 and Below</td>
                                                </tr>                    
                                            </table>                                            
                                        </td>
                                        <td width="50px"></td>
                                        <td style="vertical-align: top">
                                            <br>
                                            <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                                                <tr style="font-weight: bold">
                                                    <td class="print-size" width="40%">CONDUCT CRITERIA</td><td class="print-size" width="8%">Points</td><td class="print-size" width="8%">1</td><td class="print-size" width="8%">2</td><td class="print-size" width="8%">3</td><td class="print-size" width="8%">4</td><td rowspan="4"></td>
                                                </tr>
                                                    {{--*/$first=0/*--}}
                                                    {{--*/$second=0/*--}}
                                                    {{--*/$third=0/*--}}
                                                    {{--*/$fourth=0/*--}}
                                                    {{--*/$counter = 0/*--}}
                                                    {{--*/$length = count($conduct)/*--}}
                                                    @foreach($conduct as $conducts)
                                                    {{--*/$counter ++/*--}}                    
                                                <tr>
                                                    <td class="print-size" style="text-align: left;">{{$conducts->subjectname}}</td>
                                                    <td class="print-size">{{$conducts->points}}</td>
                                                    <td class="print-size">
                                                        {{round($conducts->first_grading,2)}}
                                                        {{--*/$first = $first + round($conducts->first_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($conducts->second_grading,2)}}
                                                        {{--*/$second = $second + round($conducts->second_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($conducts->third_grading,2)}}
                                                        {{--*/$third = $third + round($conducts->third_grading,2)/*--}}
                                                    </td>
                                                    <td class="print-size">
                                                        {{round($conducts->fourth_grading,2)}}
                                                        {{--*/$fourth = $fourth + round($conducts->fourth_grading,2)/*--}}
                                                    </td>
                                                    @if($length == $counter)
                                                    <td class="print-size" width="10%"><b>FINAL GRADE</b></td>
                                                    @endif
                                                    
                                                </tr>
                                                    @endforeach                    
                                                    <tr>
                                                        <td style="text-align: right"><b>CONDUCT GRADE</b></td>
                                                        <td>100</td>
                                                        <td>{{$first}}</td>
                                                        <td> {{$second}}</td>
                                                        <td>{{$third}}</td>
                                                        <td>{{$fourth}}</td>
                                                        <td>{{round(($first+$second+$third+$fourth)/4,2)}}</td>
                                                        
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
                                                        @foreach($attendance as $attend)
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
                                                @foreach($attendance as $attend)
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
                                                        {{round($attend->final_grade,0)}}
                                                    </td>                                                    
                                                </tr>
                                                @endforeach
                                            </table>
                                            <br>
                                            <table width="100%">
                                                <tr>
                                                    <td class="print-size" style="font-size: 7.5pt;">
                                                        <b>Certificate of eligibility for promotion</b>
                                                    </td>
                                                    <td class="print-size" style="font-size: 7.5pt;">
                                                        <b>Cancellation of Eligibility to Transfer</b>
                                                    </td>                                                    
                                                </tr>
                                                <tr>
                                                    <td class="print-size" style="font-size: 7.5pt;">
                                                        The student is eligible for transfer and
                                                    </td>
                                                    <td class="print-size" style="font-size: 7.5pt;">
                                                        Admitted in:____________________________
                                                    </td>                                                    
                                                </tr>
                                                <tr>
                                                    <td class="print-size" style="font-size: 7.5pt;">Admission to:___________________________</td>
                                                    <td class="print-size" style="font-size: 7.5pt;">Grade:_________   Date:__________________</td>                                                    
                                                </tr>
                                                <tr>
                                                    <td class="print-size" style="font-size: 7.5pt;">Date of Issue:__________________________</td>
                                                    <td></td>                                                    
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><br><br><br></td>                                                    
                                                </tr>
                                                                                                <tr style="text-align: center">
                                                    <td class="print-size">________________________________</td>
                                                    <td class="print-size">________________________________</td>
                                                </tr>
                                                <tr style="text-align: center;">
                                                    <td class="print-size" style="font-size: 7.5pt;">
                                                       @if($teacher != null)
                                                        {{$teacher->adviser}}
                                                       @endif
                                                    </td>
                                                    <td class="print-size" style="font-size: 7.5pt;">Principal Name</td>
                                                </tr>
                                                <tr style="text-align: center">
                                                    <td class="print-size" style="font-size: 7pt;"><b>Class Adviser</b></td>
                                                    <td class="print-size" style="font-size: 7pt;"><b>Grade School - Principal</b></td>
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
            <tfoot>
                <tr>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
