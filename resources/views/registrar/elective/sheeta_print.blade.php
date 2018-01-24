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
        <table width="100%">
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
                                <b>Date: </b>{{date('F d, Y')}}
                            </td>

                        </tr>
                        <tr>
                            <td colspan = "2" style="font-size:10pt;padding-left: 0px;">Chino Roces Ave., Makati City </td>
                            <td style="text-align: right">
                                <b>School Year: </b>{{$info->schoolyear}} - {{intval($info->schoolyear)+1}}
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
                    <table width="100%" >
                        <tr>
                            <td width="33.3333px;" style="font-size: 12px">
                                <b>QUARTER:</b> 
                                @if($info->sem == 1)
                                    @if($quarter > 2)
                                    Final
                                    @else
                                    {{$quarter}}
                                    @endif
                                @elseif($info->sem == 2)
                                    @if($quarter > 5)
                                    Final
                                    @elseif($quarter > 3)
                                    1
                                    @elseif($quarter > 4)
                                    2
                                    @endif
                                @endif
                            </td>
                            <td  style="text-align: center;width:33.3333%;font-size:12px;"><b>LEVEL:</b> {{$info->level}}</td>
                            <td style="text-align: right;width:33.3333%;font-size:12px;">
                                <table style="font-size:12px;">
                                    <tr>
                                        <td style="vertical-align: top"><b>ATECH:</b></td>
                                        <td>{{$sectionname}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="font-size: 12px">
                                    <tr>
                                        <td style="vertical-align: top"><b>SUBJECT:</b></td>
                                        <td>{{$info->elective}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td></td>
                            <td style="font-size: 12px;">
                                <b>Teacher:</b>{{$adviser}}
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
                            <td style='text-align: center;width:100px;'>QTR1</td>
                            <td style='text-align: center;width:100px;'>QTR2</td>
                            <td style='text-align: center;width:80px;'>RUNNING AVE</td>
                        </tr>
                            <?php $cn = 1; ?>
                            @foreach($students as $student)
                            <?php 
                            $name = \App\User::where('idno',$student->idno)->first();
                            $grade = App\Grade::where('section',$section)->where('idno',$student->idno)->first();
                            ?>
                            <tr>
                                <td style="text-align: center">{{$cn}}</td>
                                <td>{{$name->lastname}}</td>
                                <td>{{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
                                @if($info->sem==1)
                                <td style="text-align: center">
                                    @if($grade->first_grading != 0)
                                    {{round($grade->first_grading,0)}}
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if($grade->second_grading != 0)
                                    {{round($grade->second_grading,0)}}
                                    @endif
                                </td>
                                @elseif($info->sem==2)
                                <td style="text-align: center">
                                    @if($grade->third_grading != 0)
                                    {{round($grade->third_grading,0)}}
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if($grade->fourth_grading != 0)
                                    {{round($grade->fourth_grading,0)}}
                                    @endif
                                </td>
                                @endif
                                <td style="text-align: center">
                                    <?php 
                                    $running_ave = 0;
                                    if($info->sem ==1){
                                        $running_ave = round(($grade->first_grading+$grade->second_grading)/2,0);
                                    }elseif($info->sem == 2){
                                        $running_ave = round(($grade->third_grading+$grade->fourth_grading)/2,0);
                                    }
                                    ?>
                                    @if($running_ave != 0)
                                    {{$running_ave}}
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
                            <td rowspan='3' style='text-align: right;vertical-align: top'>Date Printed: {{date('F d, Y g:i:s A')}}</td>
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
