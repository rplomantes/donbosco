<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
?>



<table width="100%" cellspacing='0' style='font-size:7pt;position:absolute;bottom:260px;'>
        <tr>
            <td colspan="4" style="text-align: center;font-size: 9pt"><b>CERTIFICATE OF TRANSFER</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;font-size: 8pt"><b>This is to certify that this is a true record of <span style='border-bottom:1px solid;'>{{Info::get_name($idno)}}</span>.</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;font-size: 8pt"><b>He is eligible for admission to College and has no financial or property responsibility in this school.<></span>.</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;padding-top: 10pt"></td>
        </tr>
        <tr style="text-align: left;font-size: 8pt;vertical-align: top">
            <td width='100px'>Prepared by:</td>
            <td><div style="width:50%;text-align: center">Mrs. Asuncion G. Torrefiel<br>HS Records Assistant</div></td>
            <td  width='200px'>
                This record is valid for:
                <div style='border-bottom: 1px solid;padding-top: 8pt;height:8pt'></div>
                <div style='border-bottom: 1px solid;padding-top: 8pt;height:8pt'></div>
            </td>
            <td width='100px' style="padding-left: 100px"></td>
        </tr>
        <tr style="text-align: left;font-size: 8pt;vertical-align: top">
            <td width='100px'>Verified by:</td>
            <td><div style="width:50%;text-align: center">Miss Danica Shane W.Vila<br>Office Assistant</div></td>
        <td></td>
            <td width='100px' style="padding-top: 8pt"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;padding-top: 10pt"></td>
        </tr>
        <tr style="text-align: left;font-size: 8pt;vertical-align: top">
            <td colspan="2" style="font-size: 9pt;font-weight: bold"><div style="width:80%;text-align: center;font-size: 9pt;">Miss Violeta F. Roxas<br>School Registrar</div></td>
            <td>
                NOT VALID WITHOUT SCHOOL SEAL<br>
                OR WITH ERASURE OR ALTERATION<br>
                <span style='font-size: 7pt'>Original Copy</span>
            </td>
            <td width='100px' style="padding-top: 7pt"></td>
        </tr>
    </table>
