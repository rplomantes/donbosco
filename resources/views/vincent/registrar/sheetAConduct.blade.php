<html>
    <head>
        <style type='text/css'>
        .report tr td{
            padding-left:5px;
            padding-right:5px;
            font-size:13px;
        }
        
        </style>
        <link href="{{ asset('/css/fonts.css') }}" rel="stylesheet">
    </head>
    <body style="margin-left:10px;margin-right:10px">
        
        <table width="100%" style="page-break-after: always">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td rowspan="3" style="text-align: right;padding-left: 0px;vertical-align: top" class="logo" width="55px">
                                <img src="{{asset('images/logo.png')}}"  style="display: inline-block;width:50px">
                            </td>
                            <td style="padding-left: 0px;">
                                <span style="font-size:12pt; font-weight: bold">Don Bosco Technical Institute</span>
                            </td>
                            <td style="text-align: left;font-size:12pt; font-weight: bold">
                                GENERATED SHEET A
                            </td>
                            <td style="text-align: right;font-size:12pt;">
                                <b>Date: </b>{{$today}}
                            </td>

                        </tr>
                        <tr>
                            <td colspan = "2" style="font-size:10pt;padding-left: 0px;">Chino Roces Ave., Makati City </td>
                            <td style="text-align: right">
                                <b>School Year: </b>{{$schoolyear->schoolyear}} - {{intval($schoolyear->schoolyear)+1}}
                            </td>                            
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="33.3333px;" style="font-size: 12px">
                                <b>QUARTER:</b> {{$quarter}}
                            </td>
                            <td  style="text-align: center;width:33.3333%;font-size:12px;"><b>LEVEL:</b> {{$level}}</td>
                            <td style="text-align: right;width:33.3333%;font-size:12px;"><b>SECTION:</b> {{$section}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px">
                                <b>SUBJECT:</b> GOOD MANNERS & RIGHT CONDUCT
                            </td>
                            
                            <td colspan="2" style="text-align: right;font-size: 12px;">
                                
                                <b>Teacher:</b>
                                @if(isset($adviser->adviser))
                                {{$adviser->adviser}}
                                @endif
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
            <tr>
                <td>
                    <table class='report' width="100%" cellpadding="0" cellspacing="0" border="1">
                        <tr>
                            <td style='text-align: center;width:60px;'>CLASS NO</td>
                            <td style='text-align: center;width:120px;'>LAST NAME</td>
                            <td style='text-align: center;width:300px;'>FIRST NAME</td>
                            @if(($level == "Grade 11" || $level == "Grade 12") && ($quarter ==1 || $quarter ==2))
                            <td style='text-align: center;width:100px;'>QTR1</td>
                            <td style='text-align: center;width:100px;'>QTR2</td>
                            @endif
                            @if(($level == "Grade 11" || $level == "Grade 12") && ($quarter ==3 || $quarter ==4))
                            <td style='text-align: center;width:100px;'>QTR3</td>
                            <td style='text-align: center;width:100px;'>QTR4</td>
                            @endif
                            @if($level != "Grade 11")
                            <td style='text-align: center;width:100px;'>QTR1</td>
                            <td style='text-align: center;width:100px;'>QTR2</td>
                            <td style='text-align: center;width:100px;'>QTR3</td>
                            <td style='text-align: center;width:100px;'>QTR4</td>
                            @endif
                            <td style='text-align: center;width:80px;'>RUNNING AVE</td>
                        </tr>
                        @foreach($students as $student)
                     
                        <tr>
                            <td style="text-align: center">{{$student->class_no}}</td>
                            <td>{{$student->lastname}}</td>
                            <td>{{$student->firstname}} {{substr($student->middlename, 0,1)."."}}
                            @if($student->stat == 3)
                            <span style="float: right;color: red;font-weight: bold">
                            DROPPED
                            </span>
                            @endif                            
                            </td>
                            @if((($level == "Grade 11" || $level == "Grade 12") && ($quarter ==1 || $quarter ==2)))
                            <td style="text-align: center">
                                @if(!round($student->first,2) == 0)
                                    {{round($student->first,2)}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if(!round($student->second,2) == 0)
                                    {{round($student->second,2)}}
                                @endif
                            </td>d
                            @endif
                            
                            @if((($level == "Grade 11" || $level == "Grade 12") && ($quarter ==3 || $quarter ==4)))
                            <td style="text-align: center">
                                @if(!round($student->third,2) == 0)
                                    {{round($student->third,2)}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if(!round($student->fourth,2) == 0)
                                    {{round($student->fourth,2)}}
                                @endif
                            </td>  
                            @endif
                            @if($level != "Grade 11" || $level != "Grade 12")
                            <td style="text-align: center">
                                @if(!round($student->first,2) == 0)
                                    {{round($student->first,2)}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if(!round($student->second,2) == 0)
                                    {{round($student->second,2)}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if(!round($student->third,2) == 0)
                                    {{round($student->third,2)}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if(!round($student->fourth,2) == 0)
                                    {{round($student->fourth,2)}}
                                @endif
                            </td>
                            @endif
                            <td style="text-align: center">
                            <?php 
                            $count = 0;
                            $grades = 0;
                            if((($level == "Grade 11" || $level == "Grade 12") && ($quarter ==1 || $quarter ==2))){
                                if(!round($student->first,2) == 0){
                                    $grades = $grades+round($student->first,2);
                                    $count++;
                                }
                                if(!round($student->second,2) == null){
                                    $grades = $grades+round($student->second,2);
                                    $count++;
                                }
                            }
                            if((($level == "Grade 11" || $level == "Grade 12") && ($quarter ==3 || $quarter ==4))){
                                if(!round($student->third,2) == null){
                                    $grades = $grades+round($student->third,2);
                                    $count++;
                                }
                                if(!round($student->fourth,2) == null){
                                    $grades = $grades+round($student->fourth,2);
                                    $count++;
                                }
                                if(!$count == 0){
                                $grades = $grades/$count;
                                }
                            }
                            if($level != "Grade 11" || $level != "Grade 12"){
                                if(!round($student->first,2) == 0){
                                    $grades = $grades+round($student->first,2);
                                    $count++;
                                }
                                if(!round($student->second,2) == null){
                                    $grades = $grades+round($student->second,2);
                                    $count++;
                                }
                                if(!round($student->third,2) == null){
                                    $grades = $grades+round($student->third,2);
                                    $count++;
                                }
                                if(!round($student->fourth,2) == null){
                                    $grades = $grades+round($student->fourth,2);
                                    $count++;
                                }
                            }
                                if(!$count == 0){
                                $grades = $grades/$count;
                                }
                            
                            ?>                                
                            @if(!$grades == 0)
                                @if($level == 'Grade 7' || $level == 'Grade 8' || $level == 'Grade 9' || $level == 'Grade 10' || $level == 'Grade 11' || $level == 'Grade 12')
                                    {{round($grades,0)}}
                                    
                                @else
                                    {{round($grades,2)}}
                                    
                                @endif
                            @endif                                
                            </td>                            
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <table width='100%'>
                        <tr>
                            <td>Certified True and Correct by:</td>
                            <td rowspan='3' style='text-align: right;vertical-align: top'>Date Printed: {{$print}}</td>
                        </tr>
                        <tr>
                            <td>_________________________</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 45px">Subject Teacher</td>
                        </tr>                        
                    </table>
                </td>
            </tr>
        </table>
        
        

    </body>
</html>
