@extends('app')
@section('content')


<div class="container">
    <div class='row'>
        <div class="col-md-8">
            <div class='col-md-6'>
                <div class="form-group">
                    <label class="col-form-label">Level</label>
                    <select name="level" id="level" class='form-control'>
                        <option value='' hidden>--Select Level--</option>
                        @foreach($levels as $level)
                        <option value='{{$level->level}}'>{{$level->level}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id='strand_cont'>

                </div>
            </div>
            <div class='col-md-6'>
                <button class='btn btn-danger'>Auto Sectioning</button>
            </div>
        </div>
        <div class="col-md-4">
            
        </div>
    </div>
</div>
<script>
    var strand = "";
    $('#level').change(function(){
        var level = $(this).val();
        
        if(level == 'Grade 11' || level == 'Grade 12'){
            var array = {};
            array['sy'] = {{$schoolyear}};
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
</script>
@stop