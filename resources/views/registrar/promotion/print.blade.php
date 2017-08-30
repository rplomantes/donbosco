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
   
    <table width="100%" style="font-size: 9px;" border="1" cellspacing="0">
        <thead>
            <tr style='text-align: center'>
                <th></th>
                <th>STUD NO</th>
                <th>STUDENT'S NAME</th>
                <th>SEC</th>
                <th colspan="4">STATUS OF ADMISSION</th>
            </tr>
            <tr style='text-align: center'>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>STATUS</td>
                <td>CONDUCT</td>
                <td>ACADEMIC</td>
                <td>TECHNICAL</td>
            </tr>
            <?php $row = 1;?>
            @foreach($students as $student)
            <?php $isnew = RegistrarHelper::isNewStudent($student->studno,$sy);?>
            <tr>
                <td style='text-align: center'>{{$row}}</td>
                <td>{{$student->studno}}</td>
                <td>@if($isnew)
                    *
                    @endif
                    {{$student->lastname}}, {{$student->firstname}} {{substr($student->middlename,0,1)}}.</td>
                <td>{{$student->section}}</td>
                <td style='text-align: center'>{{$student->admission}}</td>
                <td style='text-align: center'>{{$student->conduct}}</td>
                <td style='text-align: center'>{{$student->academic}}</td>
                <td style='text-align: center'>{{$student->technical}}</td>
            </tr>
            <?php $row++;?>
            @endforeach
            @if(count($students) <= 0)
            <tr>
                <td colspan='7' style='text-align: center'>No Records Retrieved</td>
            </tr>
            @endif
    </table>
    </body>
    </html>
