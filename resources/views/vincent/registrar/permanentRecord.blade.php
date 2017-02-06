<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta author="John Vincent Villanueva">
    <meta poweredby = "Nephila Web Technology, Inc">
    
    <style>
        body{
            font-family: dejavu sans;
            margin-top: 0px;
            padding-top: 0px;
            font-size: 8pt;
        }
        html{
            margin-left:30px;
            margin-right:30px;
            margin-top: 10px;
            padding-top: 0px;
        }
        .block{
            display:inline-block;
            width:48%;
        }
        .block:nth-child(even){
            margin-left: 26px;
        }
    </style>
</head>
<body>
    <table width="100%" cellspacing="0">
        <tr>
            <td rowspan="2" width="15%">
                <img src="{{url('images','logo.png')}}" height="90px">
            </td>
            <td width="70%" style="vertical-align: bottom"><h3 style="margin-bottom: 0px;margin-top: 0px;text-align: center;font-family: times;font-size: 15pt">DON BOSCO TECHNICAL INSTITUTE</h3></td>
            <td width="15%" rowspan="2"></td>
        </tr>

        <tr><td><p style="margin-bottom: 0px;margin-top: 0px;text-align: center;font-size: 10pt">
                Chino Roces Ave., Makati City, Philipppines 1200<br>
                Tel. Nos. 892-01-01 to 10
                </p>
            </td></tr>
        <tr><td style="font-family: times;text-align: center;font-size: 11pt" colspan="3"><b>ELEMENTARY SCHOOL PERMANENT RECORD</b></td></tr>
        <tr><td style="font-family: times;text-align: center;font-size: 11pt" colspan="3"><b>(FORM 137-E TRANSCRIPT OF RECORDS)</b></td></tr>
    </table>
    <table width="100%" cellspacing="0">
        <tr style="text-align: center;">
            <td style="border-bottom: 1px solid;text-align: left;width:90px;">{{$info->idno}}</td>
            <td style="border-bottom: 1px solid;">{{strtoupper($student->lastname)}}</td>
            <td style="border-bottom: 1px solid;">{{strtoupper($student->firstname)}}</td>
            <td style="border-bottom: 1px solid;">{{strtoupper($student->middlename)}}</td>
        </tr>
        <tr style="text-align: center;font-size: 8pt;font-weight: bold">
            <td style="text-align: left;width:90px;">STUD. NO.</td><td>Family Name</td><td>First Name</td><td>Middle Name</td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="1" style="margin-bottom: 5px;">
        <tr>
            <td width="50%"><b>Date of Birth:</b><span style="float:left;width:100%;margin-left: 20px;">{{date("F d, Y",strtotime($info->birthDate))}}</span></td>
            <td width="50%"><b>Birth Place:</b><span style="float:left;width:100%;margin-left: 20px;">{{strtoupper($info->birthPlace)}}</span></td>
        </tr>
        <?php 
        if((str_replace(' ', '', $info->fname) != "" || $info->fname != null) & $info->fname != 'DECEASED'){
            $guardian = $info->fname;
            $occupation = $info->fFulljob;
        }elseif((str_replace(' ', '', $info->mname) != "" || $info->mname != null) & $info->mname != 'DECEASED'){
            $guardian = $info->mname;
            $occupation = $info->mFulljob;
        }else{
            $guardian = $info->guardianname;
            $occupation = "";
        }
        ?>
        <tr>
            <td width="50%"><b>Parent or Guardian:</b><span style="float:left;width:100%;margin-left: 20px;">{{$guardian}}
                </span></td>
            <td width="50%"><b>Occupation:</b><span style="float:left;width:100%;margin-left: 20px;">{{$occupation}}</span></td>
        </tr>
        <tr><td colspan="2"><b>Address of Parent or Guardian:</b><span style="float:left;width:100%;margin-left: 20px;">{{$info->address1}} {{$info->address2}}, {{$info->address3}}</span></td></tr>
    </table>
    <hr>
    <?php $prevrecs = App\PrevSchoolRec::where('idno',$info->idno)->orderBy('schoolyear','ASC')->get();
    $department = "";
    ?>
    @foreach($prevrecs as $prev)
        @if($prev->department != $department)
        <table width="100%" cellspacing="0" cellpadding="1">
            <tr><td colspan="8" style="text-align: center;">{{$prev->department}}</td></tr>
            <?php $department = $prev->department;?>
        @endif
        <tr style="text-align: center;vertical-align: top;">
            <td style="border:1px solid;" width="70px">School Year<br>{{$prev->schoolyear}} - {{$prev->schoolyear+1}}</td>
            <td style="border:1px solid;" width="80px">Date Entered<br>{{$prev->dateEntered}}</td>
            <td style="border:1px solid;" width="70px">Date Left<br>{{$prev->dateLeft}}</td>
            <td style="border:1px solid;" width="225px">School Attended<br>{{$prev->school}}</td>
            <td style="border:1px solid;" width="60px">Grade<br>{{$prev->level}}</td>
            <td style="border:1px solid;" width="80px">Days Present<br>{{number_format($prev->dayp,0)}}</td>
            <td style="border:1px solid;" width="70px">Final Rating<br>{{number_format($prev->finalrate,2)}}</td>
            <td style="border:1px solid;">Prom./Ret.<br>{{$prev->status}}</td>
        </tr>
        @if($prev->department != $department)
        </table>
        @endif
    @endforeach
    <?php $sys = App\Grade::distinct()->select('schoolyear','level','school')->where('idno',$info->idno)->orderBy('schoolyear','ASC')->get(); ?>
    
    @foreach($sys as $sy)
    <?php $grades = App\Grade::where('idno',$info->idno)->where('schoolyear',$sy->schoolyear)->where('isdisplaycard',1)->whereIn('subjecttype',array(0,1,5,6))->orderBy('sortto','ASC')->get(); ?>
    <div class="block">
        {{$sy->level}} - Section : {{$sy->section}}<br>
        School: @if($sy->school == "")DON BOSCO TECHNICAL INSTITUTE @else {{$sy->school}} @endif
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SY: {{$sy->schoolyear}} - {{$sy->schoolyear+1}}
        <table width="100%" border="1" cellspacing="0" style="text-align: center">
            <tr>
                <td rowspan="2" width="40%">LEARNING AREA</td>
                <td colspan="4" width="40%">Periodic Rating</td>
                <td rowspan="2">Final Rating</td>
                <td rowspan="2">Action Taken</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
            </tr>
            @foreach($grades as $grade)
            <tr>
                <td  style="text-align: left;">{{$grade->subjectname}}</td>
                <td>{{round($grade->first_grading,0)}}</td>
                <td>{{round($grade->second_grading,0)}}</td>
                <td>{{round($grade->third_grading,0)}}</td>
                <td>{{round($grade->fourth_grading,0)}}</td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </table>
    </div>
    @endforeach
    
</body>
</html>