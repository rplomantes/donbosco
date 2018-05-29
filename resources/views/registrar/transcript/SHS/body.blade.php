<?php
use App\Http\Controllers\Registrar\Transcript\SeniorTranscript;
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
        {!!SeniorTranscript::levelRecord($idno,'Grade 11')!!}
	<br>
        if(strlen(SeniorTranscript::levelRecord($idno,'Grade 12'))>0)
        {!!SeniorTranscript::levelRecord($idno,'Grade 12')!!}
        @endif
	@include('registrar.transcript.SHS.footer')
    </body>
</html>
