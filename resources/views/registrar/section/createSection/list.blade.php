<?php
use App\Http\Controllers\Registrar\Section\SectionInformation;
use App\Http\Controllers\Helper as MainHelper;

?>
<style>
    .action a:hover{
        cursor: pointer
    }
</style>
<table id='section-list'>
    <thead>
        <tr>
            <th>Level</th>
            <th>Strand</th>
            <th>Section</th>
            <th>Adviser</th>
            <th>No. of Students</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sections as $section)
        <tr>
            <td>{{$section->level}}</td>
            <td>{{$section->strand}}</td>
            <td>{{$section->section}}</td>
            <td>{{MainHelper::get_propername($section->adviserid)}}</td>
            <td align='center'>{{$section->students->count('id')}}</td>
            <td class='action' align='center' style='font-size: 20px'>
                <a class="updateSection" data-section="{{$section->id}}"><i style='color:#4949de' class="fa fa-pencil"></i></a>
                &nbsp;
                <a class="deleteSection" data-section="{{$section->id}}"><i style='color:red' class="fa fa-times fa-col"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $('#section-list').DataTable();
    
    function changeStat(id){

        $.ajax({
            type:"GET",
            url: "/section/status/"+id, 
            success:function(data){
                $('#status'+id).html(data);
            }
        });
        alert($('#status'+id).html());
        $('#status'+id).html('')
    }
    
    $('.deleteSection').click(function(){
        var section = $(this).data('section');
        $.ajax({
            type:"GET",
            url: "/section/kto12/modal/delete/"+section, 
            success:function(data){
                $('#formModal').html(data);
                $('#formModal').modal('toggle');
            }
        });
    });
    
    $('.updateSection').click(function(){
        var section = $(this).data('section');
        $.ajax({
            type:"GET",
            url: "/section/kto12/modal/update/"+section, 
            success:function(data){
                $('#formModal').html(data);
                $('#formModal').modal('toggle');
            }
        });
    });
</script>