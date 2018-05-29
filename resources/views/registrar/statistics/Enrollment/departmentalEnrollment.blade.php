<style>
    .count{
        text-align: right;
    }
</style>
<h4>Departmental Enrollment Report</h4>
<table class='table table-bordered' id='departmentEnrollment'>
    <tr>
        <td width='5px'></td>
        <td>Department</td>
        <td>Enrolled</td>
    </tr>
    <tr>
        <td><button  type="button" data-toggle="collapse" data-target="#departmentEnrollment .collapse"><span class='fa fa-plus'></span></button></td>
        <td><b>Kto12 Department</b></td>
        <td class='count'><b>
            {{$enrollees->filter(function($query){
            return $query->department != 'TVET';
            })->count()}}
        </b></td>
    </tr>
    @foreach($departments as $department)
    <tr class='collapse'>
        <td></td>
        <td style='padding-left: 30px'>{{$department->department}}</td>
        <td class='count'>{{$enrollees->where('department',$department->department,false)->count()}}</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td><b>TVET Department (Batch {{$batch->period}})</b></td>
        <td class='count'><b>{{$enrollees->where('department','TVET',false)->count()}}</b></td>
    </tr>
</table>