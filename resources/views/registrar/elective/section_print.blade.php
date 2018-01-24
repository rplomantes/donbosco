<html>
    <head>
        
        <style type="text/css">

            table tr td{font-size: 9pt;}
            table.list tr td{border: 1.5px solid;}
            table.footer tr td{border-width: 1px; font-size: 7pt;}
            body {
            font-family: dejavu sans;}
	
	    #footer { position: fixed; bottom:0px;border-top:1px solid gray;font-size: 10pt;}
        </style>    
        
    </head>
    <body>
	<div id="footer">Enrolled as of {{date("Y-m-d h:i:sa")}}</div>
            <table border="0" cellspacing="0" cellpadding ="0" width="100%" style="margin-bottom: 0px;">
                <tr><td rowspan="3" width="50" align="center" style="vertical-align: top"><img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png" width="100%" height="auto"></td>
                <td><span style="font-size: 10pt; font-weight: bold;" >Don Bosco Technical Institute of Makati</span><br>
                <span style="line-height: 70%;">Chino Roces Avenue Brgy. Pio Del Pilar, Makati City</span><br>
                <span style="line-height: 70%;">Tel Nos. 892-0101 to 08</span></td>
                <td rowspan="3" valign="top" width="45%">
                <table border ="0" celspacing="0" cellpadding="0">
                <tr style="font-size: 10pt"><td width="45">Subject:</td><td style="border-bottom:1px solid;"></td></tr>
                <tr style="font-size: 10pt"><td>Teacher:</td><td style="border-bottom:1px solid;">{{rtrim($advisername,',')}}</td></tr>
                <tr style="font-size: 10pt"><td>Grade:</td> <td>{{$sectioninfo->level}}</td></tr>
                <tr style="font-size: 10pt"><td>Etech:</td><td> {{$sectioninfo->elective}} ({{$sectioninfo->section}})</td></tr>
                </table>
                </td></tr>
            
                <tr><td rowspan="2">
                    School Year : {{$sectioninfo->schoolyear}} - {{$sectioninfo->schoolyear + 1}}<br>
                    Grading period: ________
                </td></tr>
        </table>
        <br>
        <br>
        <div>
            <table width="100%" border="1" cellspacing = "0" class="list" style="margin-top: 0px;">
            <tr align="center" style="font-weight: bold">
                <td width="10%" align="center">ID No.</td>
                <td width="5%"  align="center">CN</td>
                <td width="37%">Name of Student</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php $cn = 1; ?>
            @foreach($students as $student)
            <?php $name = \App\User::where('idno',$student->idno)->first(); ?>
            <tr>
                <td align="center">{{$student->idno}}</td>
                <td align="center">{{$cn}}</td>
                <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php $cn++; ?>
            @endforeach
        </table>
        </div>
    </body>
</html>

