<?php use App\Http\Controllers\Registrar\ReportCards\StudentCard;?>

        @foreach($students as $student)
        {!!(string)StudentCard::studentReport($student->idno,$sy,$quarter,$sem)!!}
        @endforeach
