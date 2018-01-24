<?php use App\Http\Controllers\Registrar\SheetA\Helper as SheetAHelper;?>
<html>
    <head>
        <style type='text/css'>
        .report tr td{
            padding-left:5px;
            padding-right:5px;
            font-size:13px;
        }
        body{
            margin-left:200px;
            margin-right:200px;
        }
        </style>
        
        <style type="text/css" media="print">
            body{
                margin-left:10px;
                margin-right:10px;
            }
        </style>
        <link href="{{ asset('/css/fonts.css') }}" rel="stylesheet">
    </head>
    <body >
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
                                <b>Date: </b>{{date("F d, Y")}}
                            </td>

                        </tr>
                        <tr>
                            <td colspan = "2" style="font-size:10pt;padding-left: 0px;">Chino Roces Ave., Makati City </td>
                            <td style="text-align: right">
                                <b>School Year: </b>{{$sy}} - {{intval($sy)+1}}
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
                                <b>QUARTER:</b> {{$quarter->qtrperiod}}
                            </td>
                            <td  style="text-align: center;width:33.3333%;font-size:12px;"><b>LEVEL:</b> {{$level}}</td>
                            <td style="text-align: right;width:33.3333%;font-size:12px;"><b>SECTION:</b> {{$section}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px">
                                <b>SUBJECT:</b> 
                                {{SheetAHelper::getSubject($level,$subject)}}
                            </td>
                            
                            <td colspan="2" style="text-align: right;font-size: 12px;">
                                <b>Teacher: </b>
                                {{SheetAHelper::getAdviser($sy,$level,$section,$subject)}}
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
                    <table style='font-size:13px;' width="100%" border="1" cellspacing="0">
                        <tr style="text-align: center">
                            <td>CLASS NO</td>
                            <td>LAST NAME</td>
                            <td>FIRST NAME</td>
                            <td>QTR 1</td>
                            <td>QTR 2</td>
                            @if(in_array($semester,array(0)))
                            <td>QTR 3</td>
                            <td>QTR 4</td>
                            @endif
                            <td>RUNNING AVE</td>
                        </tr>
                        <?php $cn = 1; ?>
                        @foreach($students as $student)
                        <?php 
                        $name = \App\User::where('idno',$student->idno)->first();
                        $first_grading = 0;
                        $second_grading = 0;
                        $third_grading = 0;
                        $fourth_grading = 0;
                        $running_ave = 0;
                        $count = 0;

                        if($subject == 3){
                            $grades = App\Grade::where('subjecttype',$subject)->where('schoolyear',$sy)->where('idno',$student->idno)->get();
                        }else{
                            $grades = App\Grade::where('subjectcode',$subject)->where('schoolyear',$sy)->where('idno',$student->idno)->get();
                        }

                        foreach($grades as $grade){
                            $grade_setting = App\GradesSetting::where('level',$grade->level)->first();
                            $first_grading = $first_grading+$grade->first_grading;
                            $second_grading = $second_grading+$grade->second_grading;
                            $third_grading = $third_grading+$grade->third_grading;
                            $fourth_grading = $fourth_grading+$grade->fourth_grading;
                        }
                        ?>
                        <tr>
                            <td style="text-align: center">{{$cn}}</td>
                            <td>{{$name->lastname}}</td>
                            <td>{{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
                            @if(in_array($semester,array(0,1)))
                            <td style="text-align: center">
                                @if($first_grading != 0)
                                <?php 
                                    $running_ave = $running_ave+$first_grading;
                                    $count++;
                                ?>
                                {{$first_grading}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if($second_grading != 0)
                                <?php 
                                    $running_ave = $running_ave+$second_grading;
                                    $count++;
                                ?>
                                {{$second_grading}}
                                @endif
                            </td>
                            @endif
                            @if(in_array($semester,array(0,2)))
                            <td style="text-align: center">
                                @if($third_grading != 0)
                                <?php 
                                    $running_ave = $running_ave+$third_grading;
                                    $count++;
                                ?>
                                {{$third_grading}}
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if($fourth_grading != 0)
                                <?php 
                                    $running_ave = $running_ave+$fourth_grading;
                                    $count++;
                                ?>
                                {{$fourth_grading}}
                                @endif
                            </td>
                            @endif

                            <td style="text-align: center">
                                @if($count != 0)
                                {{number_format(round($running_ave/$count,$grade_setting->decimal),$grade_setting->decimal)}}
                                @endif
                            </td>
                        </tr>
                        <?php $cn++; ?>
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
                            <td rowspan='3' style='text-align: right;vertical-align: top'>Date Printed: {{date("F d, Y h:i:s A")}}</td>
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
