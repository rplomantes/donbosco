<?php 
use App\Http\Controllers\Registrar\PromotionController as Promotion;
?>
@extends('app')
@section('content')
<style>
input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  padding: 10px;
}
</style>
<div class='container'>
    <form id="submitconduct" action="{{ URL::to('savepromotion/'.$sy.'/'.$level) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!} 
        <table class='table table-bordered'>
            <tr style='text-align: center' id='fixed'>
                <td></td>
                <td>IDNO</td>
                <td>STUDENT'S NAME</td>
                <td>SEC</td>
                <td>A</td>
                @foreach($probations as $probation)
                <td>{{$probation->code}}</td>
                @endforeach
            </tr>
            <?php $row = 1;?>
            @foreach($students as $student)
            <tr>
                <td>{{$row}}</td>
                <td>{{$student->studno}}</td>
                <td>{{$student->lastname}}, {{$student->firstname}} {{substr($student->middlename,0,1)}}.</td>
                <td>{{$student->section}}</td>
                <td>
                    <select name="admission[{{$student->studno}}]">
                        @foreach($admissions as $admission)
                        <option value="{{$admission->code}}"
                                @if($admission->code == $student->admission)
                                selected
                                @endif
                                >{{$admission->code}}</option>
                        @endforeach
                    </select>
                </td>
                @foreach($probations as $probation)
                <td style="text-align: center">
                    <input type="checkbox" class="{{$probation->type}}" name="{{$probation->type}}[{{$student->studno}}]" value="{{$probation->code}}"
                           @if(Promotion::selected($student->conduct,$student->academic,$student->technical,$probation->type,$probation->code))
                           checked = 'checked'
                           @endif
                           >
                </td>
                @endforeach
            </tr>
            <?php $row++;?>
            @endforeach
        </table>
        <button type="submit">Save</button>
    </form>
</div>
@stop