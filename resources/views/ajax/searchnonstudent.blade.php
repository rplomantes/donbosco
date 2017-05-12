<table class="table table-stripped">
    <tr>
        <td>Name</td>
        <td></td>
    </tr>
    @foreach($students as $student)
    <tr>
        <td width="80%">
            {{$student->fullname}}
        </td>
        <td>
            <a href="{{url('viewnonstudent',$student->idno)}}">view</a>
        </td>
    </tr>
    @endforeach
</table>