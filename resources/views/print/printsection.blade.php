<html>
    <head>
        
        <style type="text/css">

            table tr td{font-size: 9pt;}
            table.list tr td{border: 1.5px solid;}
            table.footer tr td{border-width: 1px; font-size: 7pt;}
            body {
            font-family: dejavu sans;}
        </style>    
        
    </head>
    <body>
            <table border="0" cellspacing="0" cellpadding ="0" width="100%" style="margin-bottom: 0px;">
            <tr><td width="50" align="center"><img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png" width="100%" height="auto"></td>
                <td><span style="font-size: 10pt; font-weight: bold;" >Don Bosco Technical Institute of Makati</span><br>
                <span style="line-height: 70%;">Chino Roces Avenue Brgy. Pio Del Pilar, Makati City</span><br>
                <span style="line-height: 70%;">Tel Nos. 892-0101 to 08</span></td>
                <td rowspan="3" valign="top" width="40%">
                <table border ="0" celspacing="0" cellpadding="0">
                <tr><td width="45">Subject:</td><td style="border-bottom:1px solid;"></td></tr>
                <tr><td>Teacher:</td><td style="border-bottom:1px solid;"></td></tr>
                <tr><td>Adviser:</td><td>{{$adviser}}</td></tr>
                <tr><td>Grade:</td> <td>{{$level}} 
                    @if(isset($strand))    
                        ({{$strand}})
                    @endif
                    </td></tr>
                <tr><td>Section:</td><td> {{$section}}</td></tr>
                </table>
                </td></tr>
            
            <tr><td colspan="2">School Year : {{$schoolyear}} - {{$schoolyear + 1}}</td></tr>
            <tr><td colspan="2">Grading period: ________</td></tr>
            
        </table>  
        
        <table width="100%" border="1" cellspacing = "0" class="list" style="margin-top: 0px;"><tr>
                <td width="10%" align="center">Student No</td><td width="5%" align="center">CN</td><td width="37%">Name</td><td width="6.85%"></td><td width="6.85%"></td><td width="6.85%"></td><td width="6.85%"></td><td width="6.85%"></td><td width="6.85%"></td><td width="6.85%"></td></tr>
        <?php
        $cnt = 0;
        ?>
        @if(preg_match( '/^Batch.*/', $level))
        <tr><td colspan="3" style="padding-left: 15px">Girls</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            @foreach($studentnames as $studentname)
                @if($studentname->gender == "Female")
                    <tr><td align="center">{{$studentname->idno}}</td><td align="center">
                    <?php if($cnt++ < 9){echo "0".$cnt;} else {echo $cnt;}?>    
                    </td><td>{{$studentname->lastname}}, {{$studentname->firstname}} {{$studentname->middlename}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @endif
            @endforeach         
            <tr><td colspan="3" style="padding-left: 15px">Boys</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            @foreach($studentnames as $studentname)
                @if($studentname->gender == "Male")
                    <tr><td align="center">{{$studentname->idno}}</td><td align="center">
                    <?php if($cnt++ < 9){echo "0".$cnt;} else {echo $cnt;}?>    
                    </td><td>{{$studentname->lastname}}, {{$studentname->firstname}} {{$studentname->middlename}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @endif
            @endforeach                 
        @else
            @foreach($studentnames as $studentname)
            <tr><td align="center">{{$studentname->idno}}</td><td align="center">
            <?php if($cnt++ < 9){echo "0".$cnt;} else {echo $cnt;}?>    
            </td><td>{{$studentname->lastname}}, {{$studentname->firstname}} {{$studentname->middlename}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            @endforeach        
        @endif            
        </table>   
        <p>&nbsp;</p>
        <table border = "0" width="100%" cellspacing = '0' class="footer">
            <tr align="center"><td>Prepared By:<br><br></td><td>Checked By:<br><br></td><td></td><td>Noted By:<br><br></td><td></tr>
                @if(!preg_match( '/^Batch.*/', $level))
            <tr align="center"><td>Subject Teacher</td><td>Subject Area Head</td><td>Date Submitted</td><td>Asst. Principal</td><td>Due Date</t$
                @else
            <tr align="center"><td>TVET Records Assistant</td><td>Adviser</td><td>Date Submitted</td><td>Asst. Technical Director</td><td>Due D$
                @endif
            </table>
    </body>
</html>

