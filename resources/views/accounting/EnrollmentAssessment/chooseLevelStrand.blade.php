@extends('appaccounting')
@section('content')
<div class='container-fluid'>
    <div class="col-md-3">
        @include('accounting.EnrollmentAssessment.leftmenu')
    </div>
    <div class="col-md-9">
        <h4>{{$module_info['title']}}</h4>
        <hr>
        <div class='form-group' id='level_cont'>
            <label for='level'>Level:</label>
            <select class='form-control' id='level' name='level'>
                @foreach($levels as $level)
                <option value='{{$level->level}}'>{{$level->level}}</option>
                @endforeach
            </select>
        </div>

        <div class='form-group' id='strand_cont'>
        </div>     
        <div id='submitBtn'><button class='btn btn-success' type='button' id="btn">Show</button></div>
    </div>
</div>

<script>
    var strand;
    $('#submitBtn').hide();
    $('#level').change(function(){
        var level = $(this).val();
        
            $.ajax({
            type:"GET",
            url:"/getstrandtrack/"+level,
            success:function(data){
                $('#strand_cont').html(data)
                strand = data;
            }    
            });
            
            $('#submitBtn').show();
    });
    
    $('#btn').click(function(){
        if($( "#strand_cont" ).has( "#strand" ).length){
            window.location.replace($("#level").val()+"/"+$("#strand").val())
        }else{
            window.location.replace($("#level").val());
        }
    });
</script>
@stop