<?php 
use App\Http\Controllers\Registrar\PromotionController as Promotion;
use App\Http\Controllers\Registrar\Helper as RegistrarHelper;
use App\Http\Controllers\Accounting\Student\StudentInformation as Info;
?>
@extends('app')
@section('content')
<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<style>
    table tr td,table thead{
        font-size:9pt
    }
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
        {!! csrf_field() !!} 
        <table class='table table-bordered' id='editTable'>
            <thead>
                <tr style='text-align: center' id='fixed'>
                    <th rowspan="2"></th>
                    <th rowspan="2">IDNO</th>
                    <th rowspan="2">STUDENT'S NAME</th>
                    <th rowspan="2">SEC</th>
                    <th rowspan="2">
                        <select id="override_admission" >
                            @foreach($admissions as $admission)
                            <option value="{{$admission->code}}">{{$admission->code}}</option>
                            @endforeach
                        </select>
                        <br>
                        Status
                    </th>
                    @foreach($probations->groupBy('type') as $type=>$probation)
                    <th colspan="3" style='text-align: center'>{{strtoupper($type)}}</th>
                    @endforeach
                </tr>
                <tr style='text-align: center' id='fixed'>
                    @foreach($probations as $probation)
                    <th>{{$probation->code}}</th>
                    @endforeach
                </tr>
            </thead>

            <?php $row = 1;?>
            @foreach($students as $student)
            <?php 
            $isnew = RegistrarHelper::isNewStudent($student->idno,$sy-1);
            $promotionStat = App\StudentPromotion::where('schoolyear',$sy)->where('idno',$student->idno)->first();
            
            ?>
            <tr>
                <td>{{$row}}</td>
                <td>{{$student->idno}}</td>
                <td>@if($isnew)
                    *
                    @else
                    &nbsp;
                    @endif
                    {{Info::get_name($student->idno)}}
                </td>
                <td>{{strrchr($student->section," ")}}</td>
                <td>
                    <select class="admission" data-id='{{$student->idno}}'>
                        @foreach($admissions as $admission)
                        <option value="{{$admission->code}}"
                                @if($admission->code == $promotionStat->admission)
                                selected
                                @endif
                                >{{$admission->code}}</option>
                        @endforeach
                    </select>
                </td>
                
                @foreach($probations as $probation)
                <td style="text-align: center">
                    <input type="checkbox" data-id='{{$student->idno}}' class="{{$probation->type}}" name="{{$probation->type}}[{{$student->studno}}]" value="{{$probation->code}}"
                           @if($promotionStat && Promotion::selected($promotionStat->conduct,$promotionStat->academic,$promotionStat->technical,$probation->type,$probation->code))
                           checked = 'checked'
                           @endif
                           >
                </td>
                @endforeach
            </tr>
            <?php $row++;?>
            @endforeach
        </table>
</div>
<script>
    $('#override_admission').change(function(){
        var val = $(this).val();
        $('.admission').each(function(){
            var id = $(this).data('id');
            changeStatus(id,'admission',val);
            $(this).val(val)
        })
    })
    $('.admission').change(function(){
        var id = $(this).data('id');
        var val = $(this).val();
        changeStatus(id,'admission',val);
    });
    
    $(".acad").change(function() {
        var id = $(this).data('id');
        var val = $(this).val();
        if(this.checked) {
            $('.acad[data-id="'+id+'"]').not(this).prop('checked', false);  
            changeStatus(id,'academic',val);
        }else{
            changeStatus(id,'academic','');
        }
        

    });
    
    $(".conduct").change(function() {
        var id = $(this).data('id');
        var val = $(this).val();
        if(this.checked) {
            $('.conduct[data-id="'+id+'"]').not(this).prop('checked', false);  
            changeStatus(id,'conduct',val);
        }else{
            changeStatus(id,'conduct','');
        }
    });
    
    $(".tech").change(function() {
        var id = $(this).data('id');
        var val = $(this).val();
        if(this.checked) {
            $('.tech[data-id="'+id+'"]').not(this).prop('checked', false);
            
            changeStatus(id,'technical',val);
        }else{
            changeStatus(id,'technical','');
        }
    });
    
    function changeStatus(idno,type,value){
        var arrays = {};
        arrays['idno'] = idno;
        arrays['type'] = type;
        arrays['value'] = value;
        arrays['sy'] = '{{$sy}}';
        
        $.ajax({
            type:"GET",
            url: "/updatestudentpromotion", 
            data:arrays,
            error:function(){
                alert('An error occured. Data was not updated')
            }
        });
    }
</script>
@stop