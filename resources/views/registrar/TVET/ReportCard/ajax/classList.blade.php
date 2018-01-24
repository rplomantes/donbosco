@if(count($students)>0)
<table class='table table-bordered'>
    <thead>
        <tr>
            <th>Idno</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{$student->idno}}</td>
            <td>{{$student->user->lastname}}, {{$student->user->firstname}}</td>
            <td><a href="{{route('individual_TvetCard',[$student->period,$student->idno,$semester])}}">View</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="col-md-12 h3" align="center">No Record</div>
@endif