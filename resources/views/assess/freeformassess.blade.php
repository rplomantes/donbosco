<?php 
use App\Http\Controllers\StudentInfo as Info;
use App\Http\Controllers\Assessement\Helper as AssHelper;
use App\Http\Controllers\Helper as MainHelper;


?>
@extends('app')

@section('content')
<section class="container">
    
    <form method='POST' action='{{route("processAssessment",array($idno))}}'>
        {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            {!!AssHelper::get_viewInfo($idno, $laststatus)!!}
                        
            <!--This is for assessment .....-->
            <?php 
            $plans = App\CtrRefSchedule::groupBy('plan')->orderBy('id','DESC')->get();
            ?>
            <hr>
            <h4>Assessment for {{MainHelper::get_enrollmentSY()}} - {{MainHelper::get_enrollmentSY() +1}}:</h4>
            <div class="col-md-12">
                <label>Level</label>
                <select class="form-control" id="level" name="level">
                    <option value="" hidden="">-- Select Level --</option>
                    @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
                    @endforeach
                    <option value="TVET">TVET</option>
                </select>
            </div>
            
            <div id="strand_cont" class="col-md-12"></div>
            <div class="col-md-12">
                <hr>
                <label>Plan</label>
                <select class="form-control" name="plan" id='plan'>
                    <option value='' hidden='hidden'>--Select A Plan--</option>
                    @foreach($plans as $plan)
                    <option value='{{$plan->plan}}'>{{$plan->plan}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-12">
                <label>Discount</label>
                <select class="form-control" name="discount" id='discount'>
                    <option value='' hidden='hidden'>None</option>
                    @foreach($discounts as $discount)
                    <option value='{{$discount->discountcode}}'>{{$discount->description}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class='col-md-6' id='showAssessment'></div>
    </div>    
    </form>
</section>
<script type='text/javascript'>
var arrays={};
arrays['idno'] = {{$idno}};
$('#level').change(function(){
    $('#strand_cont').html('');
    var level = $('#level').val();
    arrays['level'] = level;
    
    
    if($.inArray(level,['Grade 9','Grade 10','Grade 11','Grade 12']) >= 0){
        $.ajax({
            'url':"{{route('newassessmentStrand')}}",
            'data':arrays,
            'success':function(data){
                $('#strand_cont').html(data);
            }

        })
    }else{
        get_plan();
    }
})

function get_plan(){
    $.ajax({
        
        'url':"/planassessement/{{$idno}}/"+$('#plan').val()+"/"+$('#strand').val(),
        'data':arrays,
        'success':function(data){
            $('#showAssessment').html(data);
        }

    })  
}
</script>
@stop



