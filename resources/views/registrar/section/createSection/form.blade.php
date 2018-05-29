<?php
use App\Http\Controllers\Registrar\Section\SectionHelper;
?>
<div class='panel panel-success'>
    <div class='panel-heading'>Create Section</div>
    <div class='panel-body'>
        <form method='POST' action="{{route('saveSection')}}">
            {!!csrf_field()!!}
            <div class='form-group'>
                <label class='label-control'>Level</label>
                <select class='form-control' name='level' id='level'>
                    <option value="" hidden>--Select Level--</option>
                    @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
                    @endforeach
                </select>
            </div>
            <div class='form-group' id='strand_cont'>
            </div>
            <hr>
            <div class='form-group'>
                <label class='label-control'>Section</label>
                <input class='form-control' id='seciton' name="section">
            </div>

            <div class=' form-group col-md-8' style="padding-left: 0px;padding-right: 0px;">
                <label class='label-control'>Adviser</label>
                <select class='form-control ' name='adviser' id='adviser'>
                    <option value="" hidden='hidden'>--Select Adviser--</option>
                    @foreach($advisers as $adviser)
                    <option value="{{$adviser->idno}}">{{SectionHelper::get_name($adviser->idno)}}</option>
                    @endforeach
                </select>
            </div>
            <div class=' form-group col-md-4' style="padding-left: 0px;padding-right: 0px;">
                <label class='label-control'>&nbsp;&nbsp;&nbsp;</label>
                <button type='button' id='createAdviser' class='btn btn-default form-control'>Add Teacher</button>
            </div>

            <button type='submit' class='btn btn-success col-md-12'>Create Section</button>
        </form>
    </div>
</div>
<script>
    
    $('#adviser').combify();
    
    var strand = "";
    $('#level').change(function(){
        var level = $(this).val();
        $('#strand_cont').html("");
        
        if( level == 'Grade 9' || level == 'Grade 10' || level == 'Grade 11' || level == 'Grade 12'){
            var array = {};
            array['sy'] = {{$sy}};
            array['level'] = level;
            $.ajax({
                type:"GET",
                data:array,
                url: "/getlevelstrands/null/0", 
                success:function(data){
                    $('#strand_cont').html(data);
                }
            });
        }else{
            
        }
    })
    
    $('#createAdviser').click(function(){
        $.ajax({
            type:"GET",
            url: "{{route('modal_createAdviser')}}", 
            success:function(data){
                $('#formModal').html(data);
                $('#formModal').modal('toggle');
            }
        });
    })
</script>