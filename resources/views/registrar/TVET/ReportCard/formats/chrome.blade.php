<style>
    .paper{
        border:1px solid;
        width:16.6cm;
        max-width:16.6cm;
        min-height:200px;
        margin: auto;
        padding: 35px;
        padding-top: 35px;
        font-family: sans-serif;
        
    }
    
    .subheader{
        font-size:10.5pt;
        text-align: center;
        padding-left: 0px;
    }
    .schoolname{
        font-size:11pt;
        font-weight: bold;
        text-align:center;
    }
    
    #student_info tr td{
        font-size:10.5pt;
        font-weight: bold;
    }
</style>
<div id="front" class="paper">
    <!-- Card Header. Can be looped?-->
    <div style="z-index: 3;position: relative;max-height: 0px;left:-10px;top:-10px">
        <img src="{{asset('images/DBTI.png')}}"  style=";width:130px;">
    </div>
    <table width="100%" cellspacing="0">
        <tr><td class="schoolname">DON BOSCO TECHNICAL INSTITUTE</td></tr>
        <tr><td class="subheader">Chino Roces Avenue, Pio Del Pilar </td></tr>
        <tr><td class="subheader">Makati City</td></tr>
        <tr><td><span style="font-size:10px">&nbsp;</td></tr>
        <tr><td class="subheader"><medium><b>Technical Vocational Education and Training Center</b></medium></td></tr>
        <tr><td><span style="font-size:10px">&nbsp;</td></tr>
        <tr><td class="schoolname">REPORT OF SCHOLASTIC PERFORMANCE</td></tr>
        <tr><td class="subheader"><b>School Year</b></td></tr>
    </table>
    <!--End  of  Card Header-->
    <br>
    <br>
    <!--Student Information-->
    <table width="100%" cellspacing="0" id="student_info">
        <tr>
            <td width="8%">Name:</td><td width="50%">{{$student_info->user->lastname}}, {{$student_info->user->firstname}} {{substr($student_info->user->middlename,0,1)}}.</td>
            <td>Student No:</td><td style="font-weight: normal">{{$student_info->idno}}</td>
        </tr>
        <tr>
            <td>Age:</td><td></td>
            <td>Section:</td><td>{{$student_info->tvetCourse()->course_id}}-{{$student_info->section}} (BATCH {{$student_info->period}})</td>
        </tr>
        <tr>
            <td>Sex:</td><td>{{$student_info->user->gender}}</td>
            <td>Semester:</td>
            <td>
                @if($sem == 1)
                FIRST
                @else
                SECOND
                @endif
            </td>
        </tr>
    </table>
    <!--End Student Information-->
    
</div>