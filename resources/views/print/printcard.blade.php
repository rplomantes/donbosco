<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="John Vincent Villanueva">
        <meta poweredby = "Nephila Web Technology, Inc">
    </head>
    <body style="margin-left:20px;margin-right:20px;">
        <div id="header">
            <table width="100%">
            <tr>
            <td style="padding-left: 0px;text-align: center;">
            <span style="font-size:12pt;font-weight: bold">DON BOSCO TECHNICAL INSTITUTE</span>
            </td>
            </tr>
            <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">Chino Roces Ave., Makati City </td></tr>
            <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">PAASCU Accredited</td></tr>
            <tr><td style="font-size:9pt;text-align: center;padding-left: 0px;">School Year {{$sy}} - {{intval($sy)+1}}</td></tr>
            <tr><td style="font-size:9pt;padding-left: 0px;">&nbsp; </td></tr>
            <tr><td><span style="font-size:5px"></td></tr>
            <tr>
            <td colspan="2" style="padding-left: 0px;">
            <div style="text-align: center;font-size:11pt;"><b>STUDENT PROGRESS REPORT CARD</b></div>
            <div style="text-align: center;font-size:11pt;"><b>ELEMENTARY DEPARTMENT</b></div>
            <br>
            </td>
            </tr>
            <tr><td style="font-size:3px"><br></td></tr>
            </table>
            <img src="{{asset('images/DBTI.png')}}"  style="position:absolute;width:108px;top:0px;">
            
            <table width="100%">
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td width="15%">Name:</td>
                    <td width="50%">{{$name}}</td>
                    <td width="15%">Student No:</td>
                    <td width="20%">{{$idno}}</td>
                </tr>
                <tr style="font-size:10pt;padding-left: 0px;font-weight: bold">
                    <td>Gr. and Sec:</td>
                    <td>{{$name}}</td>
                    <td>Class No:</td>
                    <td>{{$idno}}</td>
                </tr>
            </table>
        </div>
    </body>
</html>