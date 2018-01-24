            <table class="table table-striped">
                <thead>
                    <tr><th>Student Number</th><th>Student Name</th></tr>        
                </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr class='clickable-row' data-href='{{url('books',$student->idno)}}'>
                <td>{{$student->idno}}</td>
                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</td>
            </tr>
            @endforeach
            </tbody>
            </table>