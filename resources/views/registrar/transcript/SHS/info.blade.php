<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;

$info = App\StudentInfo::where('idno',$idno)->first();

?>
<style>
    #info tr td{
        text-align: left;
        font-size: 6.5pt;
    }
    .underlined{
        margin:0px;
        border:1px solid;
    }
    .underscore{
        padding-right: 10px;
        vertical-align: bottom
    }
</style>
<table width="100%" cellspacing="0" id='info'>
    <tr>
        <td width="11.5%">Name:</td>
        <td width="36.5%" class="underscore">{{Info::get_name($idno)}}<hr class='underlined'></td>
        <td width="12%">Gender:</td>
        <td width="11%" class="underscore">{{Info::get_gender($idno)}}<hr class='underlined'></td>
        <td width="13%">Date of Birth:</td>
        <td width="17%" class="underscore">{{$info->birthDate}}<hr class='underlined'></td>
    </tr>
    <tr>
        <td >Place of Birth:</td>
        <td class="underscore">{{$info->birthPlace}}<hr class='underlined'></td>
        <td >Student No.:</td>
        <td class="underscore">{{$idno}}<hr class='underlined'></td>
        <td>LRN:</td>
        <td class="underscore">{{$info->lrn}}<hr class='underlined'></td>
    </tr>

    <tr style="vertical-align: top">
        <td>Father:</td>
        <td class="underscore">{{$info->fname}}<hr class='underlined'></td>
        <td>Address:</td>
        <td colspan="3" rowspan="2"  class="underscore" valign='top'><div>
            {{$info->address1}}
            @if($info->address2 != "")
            ,{{$info->address2}}
            @endif
            @if($info->address3 != "")
            , {{$info->address3}}
            @endif
            </div>
            <hr class='underlined'>
        </td>
    </tr>
    <tr>
        <td>Mother:</td>
        <td class="underscore">{{$info->mname}}<hr class='underlined'></td>
        <td></td>
    </tr>
</table>
