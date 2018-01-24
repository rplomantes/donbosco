<?php use App\Http\Controllers\EntranceExam\Helper as EntranceHelper;?>
<table class="table table-borderless">
    <tr>
        <td></td>
        <td>Batch</td>
        <td>Date</td>
        <td>Day</td>
        <td>Time</td>
        <td>Remaining Slot</td>
    </tr>
    @foreach($scheds as $sched)
    
    <tr>
        <td>
            <?php
            $applicants = EntranceHelper::schedApplicant($sched->id)->count($sched->id);
            $remainigSlot = $sched->max_examinee - $applicants;
            ?>
            
            @if($remainigSlot > 0)
            <input type="radio" name='sched' value="{{$sched->id}}">
            @endif
        </td>
        <td>{{$sched->batch}}</td>
        <td>{{$sched->date}}</td>
        <td>{{date('l',strtotime($sched->batch))}}</td>
        <td>{{$sched->time_start}} - {{$sched->time_end}}</td>
        <td>{{$remainigSlot}}</td>
    </tr>
    @endforeach
</table>