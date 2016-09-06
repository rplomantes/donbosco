<html>
    <head>


        <style type='text/css'>

           table tr td{
               font-size:10.5pt;
               padding-left: 10px;
           }
           body{
                font-family: calibri;
                margin-left: auto;
                
            }
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
    <body style="width:15.5cm;">
        @foreach($collection as $info)
        <table width="95%" style="padding:10px;margin-left: auto;margin-right: auto;margin-top: 20px;margin-bottom: 20px;">
            <thead>
            <tr>
                <td style="padding-left: 0px;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="right">

                    <tr>
                        <td rowspan="4" style="text-align: right;padding-left: 0px;" class="logo" width="55px">
                            <img src="{{asset('images/logo.png')}}"  style="display: inline-block;width:50px">
                        </td>
                        <td style="padding-left: 0px;">
                            <span style="font-size:11pt; font-weight: bold">Don Bosco Technical Institute of Makati, Inc. </span>
                        </td>
                    </tr>
                    <tr><td style="font-size:9pt;padding-left: 0px;">Chino Roces Ave., Makati City </td></tr>
                    <tr><td style="font-size:9pt;padding-left: 0px;">PAASCU Accredited</td></tr>
                    <tr><td style="font-size:9pt;padding-left: 0px;">School Year 2015 - 2016</td></tr>
                    <tr><td style="font-size:4pt;padding-left: 0px;">&nbsp; </td></tr>
                    <tr><td><span style="font-size"></td></tr>
                    <tr>
                        <td colspan="2">
                    <div style="text-align: center;font-size:9pt;"><b>STUDENT PROGRESS REPORT CARD</b></div>
                    <div style="text-align: center;font-size:9pt;"><b>JUNIOR HIGH SCHOOL DEPARTMENT</b></div>

                        </td>
                    </tr>
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
                                {{$info['info']->lastname}}, {{$info['info']->firstname}} {{$info['info']->middlename}} {{$info['info']->extensionname}}
                            </td>
                            <td width="15%" style="font-size:10pt;padding-left: 0px;">
                                <b>Student No:</b>
                            </td>
                            <td width="25%" style="font-size:10pt;padding-left: 0px;">
                                {{$info['info']->idno}}
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding-left: 0px;">
                                <b>Gr. and Sec:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$level}} - {{$section}}
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
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$info['info']->gender}}
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;"  >
                                <b>Adviser:</b>
                            </td>
                            <td style="font-size:10pt;padding-left: 0px;">
                                {{$teacher->adviser}}
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
                        <tr><td colspan="4">1st Sem</td></tr>
                        
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 0px;">
                
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" class="reports">
                    <tr style="font-weight: bold;text-align:center;">
                        <td width="35%" rowspan="2" style="padding: 15px 0 15px 0;">SUBJECTS</td>
                        <td width="12%" colspan="2">QUARTER</td>
                        <td width="12%" rowspan="2">FINAL GRADE</td>
                    </tr>
                    <tr>
                        <td style="text-align:center"><b>FIRST</b></td>
                        <td style="text-align:center"><b>SECOND</b></td>
                    </tr>
                    <tr>
                        <td style="text-align:center"><b>Core Subjects</b></td>
                        <td colspan="3"></td>
                    </tr>
                    {{--*/$first=0/*--}}
                    {{--*/$second=0/*--}}                    
                    {{--*/$final=0/*--}}
                    {{--*/$count=0/*--}}
                    @foreach($info['core'] as $key=>$core)
                    <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left">
                            {{ucwords(strtolower($core->subjectname))}}
                        </td>
                        <td>
                            {{round($core->first_grading,2)}}
                            {{--*/$first = $first + round($core->first_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($core->second_grading,2)}}
                            {{--*/$second = $second + round($core->second_grading,2)/*--}}
                        </td >
                        <td>
                            {{round($core->final_grade,2)}}
                            {{--*/$final = $final + round($core->final_grade,2)/*--}}
                        </td>

                            {{--*/$count ++/*--}}                        
                    </tr>
                    @endforeach
                    
                    <tr>
                        <td style="text-align:center"><b>Applied and Specialized Subjects</b></td>
                        <td colspan="3"></td>
                    </tr>                    
                    
                    @foreach($info['spec'] as $key=>$spec)
                    <tr style="text-align: center;font-size: 8pt;">
                        <td style="text-align: left">
                            {{ucwords(strtolower($spec->subjectname))}}
                        </td>
                        <td>
                            {{round($spec->first_grading,2)}}
                            {{--*/$first = $first + round($spec->first_grading,2)/*--}}
                        </td>
                        <td>
                            {{round($spec->second_grading,2)}}
                            {{--*/$second = $second + round($spec->second_grading,2)/*--}}
                        </td >
                        <td>
                            {{round($spec->final_grade,2)}}
                            {{--*/$final = $final + round($spec->final_grade,2)/*--}}
                        </td>

                            {{--*/$count ++/*--}}                        
                    </tr>                    
                    @endforeach
                    <tr style="text-align: center">
                        <td></td>
                        <td colspan="2" style="text-align: right;">
                            <b>GENERAL AVERAGE for the Semester</b>
                        </td>
                        <td>
                            <b>{{round($final/$count,2)}}</b>
                        </td>
                    </tr>
                </table>
                                  
                </td>
            </tr>
            <tr><td><span style="height:10pt"></td></tr>
            <tr>
                <td style="padding-left: 0px;">
                    @if(count($info['tech']) != 0)
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

                        @foreach($info['tech'] as $key=>$tech)
                        <?php $weight=$tech->weighted / 100;?>
                        <tr style="text-align: center">
                            <td style="text-align: left" class="print-size">
                                <div style="width:70%;display:inline-block;" width="70%">{{ucwords(strtolower($tech->subjectname))}}</div><span>({{$tech->weighted}}%)</span>
                            </td>
                            <td class="print-size">
                                {{round($tech->first_grading,2)}}
                                {{--*/$first = $first + round($tech->first_grading,2)*$weight/*--}}
                            </td>
                            <td class="print-size">
                                {{round($tech->second_grading,2)}}
                                {{--*/$second = $second + round($tech->second_grading,2)*$weight/*--}}
                            </td>
                            <td class="print-size">
                                {{round($tech->third_grading,2)}}
                                {{--*/$third = $third + round($tech->third_grading,2)*$weight/*--}}
                            </td>
                            <td class="print-size">
                                {{round($tech->fourth_grading,2)}}
                                {{--*/$fourth = $fourth + round($tech->fourth_grading,2)*$weight/*--}}
                            </td>
                            <td class="print-size">
                                {{round($tech->final_grade,2)}}
                                {{--*/$final = $final + round($tech->final_grade,2)*$weight/*--}}
                            </td>
                            <td class="print-size">
                                {{$tech->remarks}}
                            </td>                         
                        </tr>
                        @endforeach
                        <tr style="text-align: center"><td class="print-size" style="text-align: right"><b>TECHNICAL AVERAGE</b></td><td class="print-size">{{round($first,0)}}</td><td class="print-size">{{$second}}</td><td class="print-size">{{$third}}</td><td class="print-size">{{$fourth}}</td><td class="print-size">{{$final}}</td>
                            <td class="print-size">
                            {{round($final/$count,2) >= 75 ? "Passed":"Failed"}}    
                            </td></tr>
                    </table>        
                    @endif                    
                </td>
            </tr>
            <tr><td><span style="height:10pt"></td></tr>
            <tr>
                <td style="padding-left: 0px;">
                    <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 12px;">
                        <tr style="font-weight:bold;">
                            <td width="36%" class="descriptors">
                                DESCRIPTOR
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
        @endforeach
        
        







        @foreach($collection as $info)
        
        <table width="95%" style="padding:10px;margin-left: auto;margin-right: auto;margin-top: 20px;margin-bottom: 20px;">
        <tr>
            <td style="padding-left: 0px;">
                <table border = '1' cellspacing="0" cellpadding = "0" width="100%" style="text-align: center;font-size: 11pt;">
                    <tr>
                        <td width="30%">CONDUCT CRITERIA</td>
                        <td width="10%">Points</td>
                        <td width="10%">1</td>
                        <td width="10%">2</td>
                        <td width="20%" rowspan="{{count($info['con'])}}"></td>
                    </tr>
                        {{--*/$first=0/*--}}
                        {{--*/$second=0/*--}}
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

                        @if($length == $counter)
                        <td><b>FINAL GRADE</b></td>
                        @endif
                        

                    </tr>
                        @endforeach                    
                        <tr>
                            <td style="text-align:center"><b>CONDUCT GRADE</b></td>
                            <td>100</td>
                            <td>{{$first}}</td>
                            <td> {{$second}}</td>
                            <td>{{round(($first+$second)/2,2)}}</td>
                            
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
                <br>
               
            </td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">
                Dear Parent:
                            <p style="text-indent: 20px">This report card shows the ability and progress your child has made in different learning areas as well as his/her core values.</p>
                            <p style="text-indent: 20px">The school welcomes you should desire to know more about your child progress.</p>
                            
                            <p style="text-align: right;">__________________________<br>
                                                    
                           @if($teacher != null)
                           <span style="padding-right: 30px">{{$teacher->adviser}}</span>
                           @endif
                                                    <br><span style="padding-right: 50px">Class Adviser</span></p>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 0px;">
                 <table width="100%">
                    <tr>
                        <td class="print-size"  width="49%">
                            <b>Certificate of eligibility for promotion</b>
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
                        <td class="print-size" >Admission to:___________________</td>
                        <td class="print-size" >Grade:__________________________</td>
                    </tr>
                    <tr>
                        <td class="print-size" >Retained in ____________________</td>
                        <td class="print-size" >Date ___________________________</td>
                    </tr>
                    <tr>
                        <td class="print-size" >Date ___________________________</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><br><br></td>                                                    
                    </tr>
                                                                    <tr style="text-align: center">
                        <td class="print-size"></td>
                        <td class="print-size">________________________________</td>
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
    <br>

</td>
</tr>
</table>
                
            </td>
        </tr>
        <tr>
            <td>
                <br>
            </td>
</tr>            
        </table>
    <div class="page-break"></div>
    @endforeach
        
        
    </body>
</html>