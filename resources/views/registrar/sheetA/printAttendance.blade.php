<?php 
use App\Http\Controllers\Registrar\SheetA\Helper as SheetAHelper;
use App\Http\Controllers\Registrar\AttendanceController as Attendance; ?>
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
                                <b>QUARTER:</b> {{$qtr}}
                            </td>
                            <td  style="text-align: center;width:33.3333%;font-size:12px;"><b>LEVEL:</b> {{$level}}</td>
                            <td style="text-align: right;width:33.3333%;font-size:12px;"><b>SECTION:</b> {{$section}}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px">
                                <b>SUBJECT:</b> 
                                {{SheetAHelper::getSubject($level,2)}}
                            </td>
                            
                            <td colspan="2" style="text-align: right;font-size: 12px;">
                                <b>Teacher: </b>
                                {{SheetAHelper::getAdviser($sy,$level,$section,2)}}
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
                            <td>DAYS PRESENT</td>
                            <td>DAYS ABSENT</td>
                            <td>DAYS TARDY</td>
                        </tr>
                        <?php 
                        $cn = 1; 
                        ?>
                        @foreach($students as $student)
                        <?php
                        $name = \App\User::where('idno',$student->idno)->first();
                        $attendance = Attendance::studentQuarterAttendance($student->idno,$sy,$quarter,$level);
                        ?>
                        <tr>
                            <td style="text-align: center">{{$student->class_no}}</td>
                            <td>{{$name->lastname}}</td>
                            <td>{{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
                            <td style="text-align: center">{{$attendance[0]}}</td>
                            <td style="text-align: center">{{$attendance[2]}}</td>
                            <td style="text-align: center">{{$attendance[1]}}</td>
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
