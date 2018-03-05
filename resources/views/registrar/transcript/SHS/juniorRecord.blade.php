<table width="100%" style="font-size: 7pt" cellspacing="0">
    <tr>
        <td width="200px">Junior High School Completed:</td>
        <td class="underscore">{{$school}}<hr class='underlined'></td>

    </tr>
</table>
<table width="100%" style="font-size: 7pt">
    <tr>
        <td width="11%">School Year:</td>
        <td width="30%" class="underscore">
            @if($schoolyear != "")
            {{$schoolyear}} - {{$schoolyear+1}}
            @endif
            <hr class='underlined'>
        </td>
        <td width="5%" style="padding-top: 9pt;"></td>
        <td width="15%">General Average:</td>
        <td width="39%" class="underscore">
            @if($grade != 0)
            {{round($grade,2)}}
            @endif
            <hr class='underlined'>
        </td>
    </tr>
</table>
