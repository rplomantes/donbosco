<?php
use App\Http\Controllers\Registrar\Transcript\SeniorTranscript;
use App\Http\Controllers\Registrar\PermanentRecord as Record;
?>
<html>
    <head>
        <style>
            body{
                margin-top:1px;
            }
            html{
                margin-top:10px;
                margin-bottom:0px;
                margin-left:30px;
                margin-right:30px;
                font-family: dejavu sans;
            }
        </style>
    </head>
    <body>
        @include('registrar.transcript.SHS.header')
        <br>
        @include('registrar.transcript.SHS.info')
        <br>
        {!!SeniorTranscript::previousRecord($idno)!!}
        <br>
        {!!Record::levelRecord($idno,'Grade 11')!!}
	<br>
        {!!Record::levelRecord($idno,'Grade 12')!!}
        @if(Record::levelRecord($idno,'Grade 12') != null)
        <div style="page-break-after: always"></div>
        @endif
	@include('registrar.permanentRecord.SHS.footer')
    </body>
</html>