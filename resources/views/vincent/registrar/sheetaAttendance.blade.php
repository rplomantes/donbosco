<!--FINAL AND FINISHED NO CHANGES SHOULD BE MADE-->
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
                                <b>SUBJECT:</b> ATTENDANCE
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
                            <td style='text-align: center;width:100px;'>LAST NAME</td>
                            <td style='text-align: center'>FIRST NAME</td>
                            <td style='text-align: center'>M.I.</td>
                            @foreach($subjects as $subject)
                                <td>{{$subject->subjectname}}</td>
                            @endforeach
                        </tr>
                        @foreach($students as $student)
                        <?php
                        if($quarter == 1){
                            //$grades = DB::Select("SELECT sum(dayp) as dayp,sum(dayt) as dayt,sum(daya) as daya FROM `attendance_repos` WHERE `idno` LIKE '$student->idno' and qtrperiod = 1 and month IN('JUN','JUL','AUG') and schoolyear ='$schoolyear->schoolyear'  order by id DESC");
                            $month1 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','JUN')->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','JUL')->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',1)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month','AUG')->orderBy('id','DESC')->first();
                            
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                        }elseif($quarter == 2){
                            //$grades = DB::Select("SELECT sum(dayp) as dayp,sum(dayt) as dayt,sum(daya) as daya FROM `attendance_repos` WHERE `idno` LIKE '$student->idno' and qtrperiod = 2 and month IN('AUG','SEPT','OCT') and schoolyear ='$schoolyear->schoolyear'  order by id DESC");
                            $month1 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"Sept")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"OCT")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',2)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"AUG")->orderBy('id','DESC')->first();
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                            }
                        }elseif($quarter ==3){
                            $grades = DB::Select("SELECT sum(dayp) as dayp,sum(dayt) as dayt,sum(daya) as daya FROM `attendance_repos` WHERE `idno` LIKE '$student->idno' and qtrperiod = 3 and month IN('OCT','NOV','DECE') and schoolyear ='$schoolyear->schoolyear'  order by id DESC");
                            $month1 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"OCT")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"NOV")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',3)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"DECE")->orderBy('id','DESC')->first();
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                            }                           
                        }elseif($quarter == 4){
                            $grades = DB::Select("SELECT sum(dayp) as dayp,sum(dayt) as dayt,sum(daya) as daya FROM `attendance_repos` WHERE `idno` LIKE '$student->idno' and qtrperiod = 4 and month IN('JAN','FEB','MAR') and schoolyear ='$schoolyear->schoolyear'  order by id DESC");
                            $month1 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"JAN")->orderBy('id','DESC')->first();
                            $month2 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"FEB")->orderBy('id','DESC')->first();
                            $month3 = \App\AttendanceRepo::where('qtrperiod',4)->where('idno',$student->idno)->where('schoolyear',$schoolyear->schoolyear)->where('month',"MAR")->orderBy('id','DESC')->first();
                            if(!empty($month1) && !empty($month2) && !empty($month3)){
                            $dayt = $month1->DAYT + $month2->DAYT + $month3->DAYT;
                            $dayp = $month1->DAYP + $month2->DAYP + $month3->DAYP;
                            $daya = $month1->DAYA + $month2->DAYA + $month3->DAYA;
                            }else{
                            $dayt = 0;
                            $dayp = 0;
                            $daya = 0;
                            }
                        }
                        ?>
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
                                {{substr($student->middlename, 0,1)."."}}
                                @endif
                            </td>
                            
                            
                            <td style="text-align: center">
                                {{number_format($dayp,1)}}
                            </td>
                            <td style="text-align: center">
                                {{number_format($daya,1)}}
                            </td>
                            <td style="text-align: center">
                                {{number_format($dayt,1)}}
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
