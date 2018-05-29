<?php 
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Assessement\Helper as AssHelper;
use App\Http\Controllers\Helper as MainHelper;
use App\Http\Controllers\Assessement\ProcessAwards as Award;


?>
@extends('app')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped">
                <tr>
                    <td><b>Name:</b></td>
                    <td>{{Info::get_nameFormal($idno)}}</td>
                </tr>
                <tr>
                    <td><b>Level:</b></td>
                    <td>{{Info::get_level($idno, $currEnrollment,TRUE)}}</td>
                </tr>
                @if(Info::get_strand($idno, $currEnrollment,true) != "")
                
                <tr>
                    <td><b>Strand:</b></td>
                    <td>{{Info::get_strand($idno, $currEnrollment,true)}}</td>
                </tr>
                @endif
                <tr>
                    <td><b>Section:</b></td>
                    <td>{{Info::get_section($idno, $currEnrollment)}}</td>
                </tr>
            </table>

        </div>
        
        <div class='col-md-6' id='showAssessment'>
            {!!AssHelper::getLedgerBreakdown($idno,$currEnrollment)!!}
        </div>
    </div>    

</section>
@stop


