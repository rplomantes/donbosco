<style>
    .count{
        text-align: right;
    }
</style>
<h3>Batch {{$batch->period}} (TVET)</h3>
<table class='table'>
    <thead>
        <tr>
            <th>Course</th>
            <th>Enrolled</th>
        </tr>        
    </thead>

    @foreach($enrollees->groupBy('course') as $course=>$courseStudents)
    <tr>
        <td>{{$course}}</td>
        <td class='count'>{{$courseStudents->count()}}</td>
    </tr>
    @endforeach
    <tfoot style='font-weight: bold'>
        <tr>
            <td>Total</td>
            <td class='count'>{{$enrollees->count()}}</td>
        </tr>    
    </tfoot>

</table>