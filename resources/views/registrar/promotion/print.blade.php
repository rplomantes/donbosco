<?php 
use App\Http\Controllers\Registrar\PromotionController as Promotion;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta author="Roy Plomantes">
    <meta poweredby = "Nephila Web Technology, Inc">
    
    <style>
        html{
            margin:20px;
        }
        body{
            margin-top: 150px;
        }
        #header { position: fixed; left: 0px; top: -10px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
        #footer { position: fixed; bottom:0px;border-bottom:1px solid gray;} .pagenum:before {content: counter(page); }
    </style>
</head>
<body>
    <div id="footer">Page <span class="pagenum"></span></div>    
    <div  id="header">
        <table  width ="100%">
            <tr align="center"><td>DON BOSCO TECHNICAL INSTITUTE OF MAKATI</td></tr>
            <tr align="center"><td>Chino Roces Avenue, Makati, Metro Manila</td></tr>
            <tr align="center"><td>MASTERLIST OF {{strtoupper($level)}} STUDENTS</td></tr>
            <tr align="center"><td>School Year: {{$sy}} - {{$sy+1}}</td></tr>
            <tr align="center"><td>{{date('l, M d, Y')}} </td></tr>
        </table> 
        <hr>
    </div>
   
    <table width="100%" style="font-size: 8px;" border="1" cellspacing="0">
        <thead>
            <tr style='text-align: center'>
                <th width="20px"></th>
                <th width="22px;">STUD NO</th>
                <th width="50px;">STUDENT'S NAME</th>
                <th width="20px;">SEC</th>
                <th colspan="{{count($probations)+1}}">STATUS OF ADMISSION</th>
            </tr>
            <tr style='text-align: center'>
                <th colspan="4"></th>
                <th width="30px">A</th>
                @foreach($probations as $probation)
                <th style="font-size: 9px;padding-left: 0px;padding-right: 0px;">{{$probation->code}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <?php $row = 1;?>
            @foreach($students as $student)
            <?php $isnew = RegistrarHelper::isNewStudent($student->idno,$sy);?>
            <tr>
                <td>{{$row}}</td>
                <td>{{$student->studno}}</td>
                <td>@if($isnew)
                    *
                    @else
                    &nbsp;
                    @endif
                    {{$student->lastname}}, {{$student->firstname}} {{substr($student->middlename,0,1)}}.</td>
                <td align="center">{{RegistrarHelper::getNumericSection($sy,$level,$student->section)}}</td>
                <td>{{$student->admission}}</td>
                @foreach($probations as $probation)
                <td style="text-align: center;font-size: 9px;padding-left: 0px;padding-right: 0px;">
                    <input type="checkbox" class="{{$probation->type}}" name="{{$probation->type}}[{{$student->studno}}]" value="{{$probation->code}}"
                           @if(Promotion::selected($student->conduct,$student->academic,$student->technical,$probation->type,$probation->code))
                           checked = 'checked'
                           @endif
                           >
                </td>
                @endforeach
            </tr>
            <?php $row++;?>
            @endforeach            
        </tbody>
    </table>
    </body>
    </html>
