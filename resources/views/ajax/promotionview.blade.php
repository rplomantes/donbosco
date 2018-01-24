<table class='table table-bordered'>
    <tr style='text-align: center' id='fixed'>
        <td></td>
        <td>IDNO</td>
        <td>STUDENT'S NAME</td>
        <td>SEC</td>
        <td>ADMISSION</td>
        <td>CONDUCT</td>
        <td>ACADEMIC</td>
        <td>TECHNICAL</td>
    </tr>
    <?php $row = 1;?>
    @foreach($students as $student)
    <tr>
        <td style='text-align: center'>{{$row}}</td>
        <td>{{$student->studno}}</td>
        <td>{{$student->lastname}}, {{$student->firstname}} {{substr($student->middlename,0,1)}}.</td>
        <td>{{$student->section}}</td>
        <td style='text-align: center'>{{$student->admission}}</td>
        <td style='text-align: center'>{{$student->conduct}}</td>
        <td style='text-align: center'>{{$student->academic}}</td>
        <td style='text-align: center'>{{$student->technical}}</td>
    </tr>
    <?php $row++;?>
    @endforeach
    @if(count($students) <= 0)
    <tr>
        <td colspan='7' style='text-align: center'>No Records Retrieved</td>
    </tr>
    @endif
</table>