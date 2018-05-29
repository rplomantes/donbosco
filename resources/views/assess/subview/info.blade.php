<?php
use App\Http\Controllers\StudentInfo as Info;
?>
<table class="table table-striped">
    <tr>
        <td><b>Student Id:</b></td>
        <td>{{$idno}}</td>
    </tr>
    <tr>
        <td><b>Name:</b></td>
        <td>{{Info::get_nameFormal($idno)}}</td>
    </tr>
    <tr>
        <td><b>Gender:</b></td>
        <td>{{strtoupper(\App\User::where('idno',$idno)->first()->gender)}}</td>
    </tr>
    @if($laststatus)
    <tr>
        <td><b>Level:</b></td>
        <td>{{$laststatus->level}}</td>
    </tr>
    @if($laststatus->strand != "")

    <tr>
        <td><b>Strand:</b></td>
        <td>{{$laststatus->strand}}</td>
    </tr>
    @endif
    <tr>
        <td><b>Last Section:</b></td>
        <td>{{$laststatus->section}}</td>
    </tr>
    @endif
</table>