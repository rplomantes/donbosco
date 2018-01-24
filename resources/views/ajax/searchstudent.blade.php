            <table class="table table-striped"><thead>
            <tr><th>Student Number</th><th>Student Name</th><th>Gender</th><th>View</th></tr>        
            </thead>
            <tbody>
               
            @foreach($students as $student)
            <tr><td>{{$student->idno}}</td><td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}
                    {{$student->extensionname}}</td><td>{{$student->gender}}</td><td><a href = "{{url('/studentinfo',$student->idno)}}">view</a></td></tr>
            @endforeach
            </tbody>
            </table>