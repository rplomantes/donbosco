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
        </style>
        <link href="{{ asset('/css/print.css') }}" rel="stylesheet">
    </head>
    <body>
        <table border = '0' cellpacing="0" cellpadding = "0" class="top_header" align="center">
        <thead>    
        <tr><td rowspan="5" style="text-align: right;" class="logo"><img src="{{asset('images/logo.png')}}" style="display: inline-block; min-width:60px"></td><td><span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span></td></tr>
        <tr><td style="font-size:10pt;padding-left: 100px;">Chino Roces Ave., Makati City </td></tr>
        <tr><td style="font-size:10pt;padding-left: 130px;">PAASCU Accredited</td></tr>
        <tr><td style="font-size:10pt;padding-left: 115px;">School Year 2015 - 2016</td></tr>
        <tr><td><br> </td></tr>
        <tr><td colspan="2" style="text-align: center;font-size:11pt;"><b>STUDENT PROGRESS REPORT CARD</b></td></tr>
        <tr><td colspan="2" style="text-align: center;font-size:11pt;"><b>GRADE SCHOOL DEPARTMENT</b></td></tr>
        <tr><td><br> </td></tr>
        </thead>
        <tbody>
        <tr>
            <td colspan = "2">
                <table width="100%" border = '0' cellpacing="0" cellpadding = "0">
                    <tr>
                        <td width="140px">
                            <b>Name:</b>
                        </td>
                        <td width="47%">
                            {{$student[0]->lastname}}, {{$student[0]->firstname}} {{$student[0]->middlename}} {{$student[0]->extensionname}}
                        </td>
                        <td width="120px">
                            Student No:
                        </td>
                        <td >
                            {{$student[0]->idno}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Gr. and Sec:</b>
                        </td>
                        <td>
                            {{$student[0]->level}} - {{$student[0]->section}}
                        </td>
                        <td>
                            Class No:
                        </td>
                        <td >

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Adviser:</b>
                        </td>
                        <td>
                            @if($teacher != null)
                            {{$teacher->adviser}}
                            @endif
                        </td>
                        <td>
                            LRN No:
                        </td>
                        <td >
                            {{$student[0]->lrn}}
                        </td>
                    </tr>
                </table>        
            </td>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td colspan="2">
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports">
                    <tr style="font-weight: bold;font-size: 11pt;text-align:center;">
                        <td width="300px" style="padding: 15px 0 15px 0;">SUBJECTS</td>
                        <td width="60px">1</td>
                        <td width="60px">2</td>
                        <td width="60px">3</td>
                        <td width="60px">4</td>
                        <td>FINAL RATING</td>
                        <td>REMARKS</td>
                    </tr>
                    {{--*/$first=0/*--}}
                    {{--*/$second=0/*--}}
                    {{--*/$third=0/*--}}
                    {{--*/$fourth=0/*--}}
                    {{--*/$final=0/*--}}
                    {{--*/$count=0/*--}}
                    @foreach($academic as $academics)
                    <tr style="text-align: center">
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
                        </td>
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
                        <td>
                            {{$academics->remarks}}
                            {{--*/$count ++/*--}}
                        </td>                         
                    </tr>
                    @endforeach
                    <tr style="text-align: center"><td style="text-align: right"><b>GENERAL AVERAGE</b></td><td>{{round($first/$count,2)}}</td><td>{{round($second/$count,2)}}</td><td>{{round($third/$count,2)}}</td><td>{{round($fourth/$count,2)}}</td><td>{{round($final/$count,2)}}</td>
                        <td>
                        {{round($final/$count,2) >= 75 ? "Passed":"Failed"}}    
                        </td></tr>
                </table>
            </td>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td colspan="2">
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                    <tr style="font-weight:bold;">
                        <td class="descriptors">
                            DESCRIPTOR
                        </td>
                        <td class="scale">
                            GRADING SCALE
                        </td>            
                        <td class="remarks">
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
        <tr><td><br></td></tr>
        <tr>
            <td colspan="2">
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
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
        <tr><td><br><span class="page-break"></td></tr>
        <tr>
            <td colspan="2">
                <table border = '0' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                    <tr style="border:1px solid;">
                        <td style="border:1px solid;">CONDUCT CRITERIA</td><td style="border:1px solid;">Points</td><td style="border:1px solid;">1</td><td style="border:1px solid;">2</td><td style="border:1px solid;">3</td><td style="border:1px solid;">4</td>
                    </tr>
                        {{--*/$first=0/*--}}
                        {{--*/$second=0/*--}}
                        {{--*/$third=0/*--}}
                        {{--*/$fourth=0/*--}}
                        {{--*/$counter = 0/*--}}
                        {{--*/$length = count($conduct)/*--}}
                        @foreach($conduct as $conducts)
                        {{--*/$counter ++/*--}}                    
                    <tr style="border:1px solid;">
                        <td style="border:1px solid;text-align: left">{{$conducts->subjectname}}</td>
                        <td style="border:1px solid;">{{$conducts->points}}</td>
                        <td style="border:1px solid;">
                            {{round($conducts->first_grading,2)}}
                            {{--*/$first = $first + round($conducts->first_grading,2)/*--}}
                        </td>
                        <td style="border:1px solid;">
                            {{round($conducts->second_grading,2)}}
                            {{--*/$second = $second + round($conducts->second_grading,2)/*--}}
                        </td>
                        <td style="border:1px solid;">
                            {{round($conducts->third_grading,2)}}
                            {{--*/$third = $third + round($conducts->third_grading,2)/*--}}
                        </td>
                        <td style="border:1px solid;">
                            {{round($conducts->fourth_grading,2)}}
                            {{--*/$fourth = $fourth + round($conducts->fourth_grading,2)/*--}}
                        </td>
                        @if($length == $counter)
                        <td style="border:1px solid;">FINAL GRADE</td>
                        @endif
                        

                    </tr>
                        @endforeach                    
                        <tr>
                            <td style="border:1px solid;">CONDUCT GRADE</td>
                            <td style="border:1px solid;">100</td>
                            <td style="border:1px solid;">{{$first}}</td>
                            <td style="border:1px solid;"> {{$second}}</td>
                            <td style="border:1px solid;">{{$third}}</td>
                            <td style="border:1px solid;">{{$fourth}}</td>
                            <td style="border:1px solid;">{{round(($first+$second+$third+$fourth)/4,2)}}</td>
                            <td></td>
                        </tr>
                </table>
            </td>
        </tr>
        <tr>
            
        </tr>
        </tbody>
    </table>
        @endforeach
    </body>
</html>
