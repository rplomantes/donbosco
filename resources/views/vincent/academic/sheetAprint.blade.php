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
        @foreach($subjects as $subject)
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
                                <b>Schoolyear: </b>{{$schoolyear->schoolyear}} - {{intval($schoolyear->schoolyear)+1}}
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
                                <b>SUBJECT:</b> {{$subject->subjectname}}
                            </td>
                            
                            <td colspan="2" style="text-align: right;font-size: 12px;">
                                <?php $adviser = DB::table('ctr_subject_teachers')->where('level',$level)->where('section',$section)->where('subjcode',$subject->subjectcode)->first(); ?>
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
                            <td style='text-align: center;width:100px;'>LAST NAME</td>
                            <td style='text-align: center'>FIRST NAME</td>
                            <td style='text-align: center'>M.I.</td>
                            <td style='text-align: center'>QTR1</td>
                            <td style='text-align: center'>QTR2</td>
                            <td style='text-align: center'>QTR3</td>
                            <td style='text-align: center'>QTR4</td>
                            <td style='text-align: center;width:80px;'>RUNNING AVE</td>
                        </tr>

                        @foreach($students as $student)
                        <?php $grades = \App\Grade::where('idno',$student->idno)->where('subjectcode',$subject->subjectcode)->where('schoolyear','2016')->get(); ?>
                        <tr>
                            <td style="text-align: center">{{$student->class_no}}</td>
                            <td>{{$student->lastname}}</td>
                            <td>{{$student->firstname}}
                            @if($student->stat == 3)
                            <span style="float: right;color: red;font-weight: bold">
                            DROPPED
                            </span>
                            @endif
                            </td>
                            
                            <td style="text-align: center">@if(!$student->middlename == '')
                                {{substr($student->middlename, 1,1)."."}}
                                @endif
                            </td>
                                    
                        @foreach($grades as $grade)
                            <td style="text-align: center">@if(!round($grade->first_grading,2) == null)
                                {{round($grade->first_grading,2)}}
                            @endif</td>
                            <td style="text-align: center">@if(!round($grade->second_grading,2) == NULL)
                                {{round($grade->second_grading,2)}}
                            @endif</td>
                            <td style="text-align: center">@if(!round($grade->third_grading,2) == NULL)
                                {{round($grade->third_grading,2)}}
                            @endif</td>
                            <td style="text-align: center">@if(!round($grade->fourth_grading,2) == NULL)
                                {{round($grade->fourth_grading,2)}}
                            @endif</td>
                            <?php 
                            $count = 0;
                            $grades = 0;
                            
                                if(!round($grade->first_grading,2) == null){
                                    $grades = $grades+round($grade->first_grading,2);
                                    $count++;
                                }
                                if(!round($grade->second_grading,2) == null){
                                    $grades = $grades+round($grade->second_grading,2);
                                    $count++;
                                }
                                if(!round($grade->third_grading,2) == null){
                                    $grades = $grades+round($grade->third_grading,2);
                                    $count++;
                                }
                                if(!round($grade->fourth_grading,2) == null){
                                    $grades = $grades+round($grade->fourth_grading,2);
                                    $count++;
                                }
                                if(!$count == 0){
                                $grades = $grades/$count;
                                }
                            ?>
                            
                            <td>@if(!$grades == 0)
                                {{$grades}}
                            @endif</td>
                            
                        @endforeach
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
        
        @endforeach

    </body>
</html>