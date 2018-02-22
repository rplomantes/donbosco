<?php
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
use App\Http\Controllers\Accounting\Helper;

?>
@extends('appaccounting')
@section('content')
<div class='container'>
    <table class='table table bordered'>
        <tr>
            <td>Idno</td>
            <td>Lastname</td>
            <td>Firstname</td>
            <td>Middle Initial</td>
            <td>Level</td>
            <td>Section</td>
            <td>Notes</td>
        </tr>
        @foreach($reservations as $reservation)
        <?php
        $hasDebit = \App\Dedit::where('refno',$reservation)->where('accountingcode',210400)->exists();
        //$name = Info::get_propername($reservation->idno);
        $note = "";
        $lastname="";
        $firstname="";
        $middleInit="";
        $name = Info::get_namedividedWInitial($reservation->idno);
        if(count($name)> 0 ){
            $lastname= $name['lastname'];
            $firstname=$name['firstname'];
            $middleInit=$name['middleinit'];
            
            
        }else{
            $lastname = Helper::get_nonStudent($reservation->idno);
            $firstname = "";
            $middleInit = "";
            $note = "Processed using a non student module. Please Check";
        }
        
        
        ?>
        
        @if(!$hasDebit)
        <tr>
            <td>{{$reservation->idno}}</td>
            <td>{{$lastname}}</td>
            <td>{{$firstname}}</td>
            <td>{{$middleInit}}</td>
            <td>{{Info::get_level($reservation->idno,$sy)}}</td>
            <td>{{Info::get_section($reservation->idno,$sy)}}</td>
            <td>{{$note}}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>
@stop