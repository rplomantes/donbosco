<table class='table table-borderless'>
    <tr style="text-align: center">
        <td width='30%'  style="text-align: left">Subject</td>
        @if($type == 'conduct')
        <td>Point</td>
        @endif
        <td>1st</td>
        <td>2nd</td>
        <td>3rd</td>
        <td>4th</td>
    </tr>
    @foreach($subjects as $subject)
    <tr>
        <td>{{$subject->subjectname}}</td>
        @if($type == 'conduct')
        <td>{{$subject->points}}</td>
        @endif
        <td><input class='form-control' name='subject[1][{{$subject->subjectcode}}]' type='number' value='{{round($subject->first_grading)}}'></td>
        <td><input class='form-control' name='subject[2][{{$subject->subjectcode}}]' type='number' value='{{round($subject->second_grading)}}'></td>
        <td><input class='form-control' name='subject[3][{{$subject->subjectcode}}]' type='number' value='{{round($subject->third_grading)}}'></td>
        <td><input class='form-control' name='subject[4][{{$subject->subjectcode}}]' type='number' value='{{round($subject->fourth_grading)}}'></td>
    </tr>
    @endforeach
</table>