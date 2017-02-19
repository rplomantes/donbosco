@extends('appaccounting')
@section('content')
<div class="container">
<?php
$plans = DB::Select( "select distinct plan from ctr_ref_schedules");
$levels = \App\CtrLevel::orderBy('level')->get();
$strands = DB::Select("select distinct strand from ctr_sections");
?>    
<form method="POST" action = "makepaymentschedule">
    {!! csrf_field() !!} 
    <div class="form form-group">
        <label>Plan</label>
        <select class="form-control" name="plan" id="plan">
            @foreach($plans as $plan)
                <option value="{{$plan->plan}}">{{$plan->plan}}</option>
            @endforeach
        </select>    
    </div>    
    <div class="form form-group">
        <label>Level</label>
        <select class="form-control" name="level" id="level">
            @foreach($levels as $level)
                <option value="{{$level->level}}">{{$level->level}}</option>
            @endforeach
        </select>    
    </div>   
    <div class="form form-group">
        <label>stand</label>
        <select class="form-control" name="strand" id="strand">
            @foreach($strands as $strand)
                <option value="{{$strand->strand}}">{{$strand->strand}}</option>
            @endforeach
        </select>    
    </div>   
    <div class="form form-group">
        <input type="submit" name="submit" value="Process Schedule">   
    </div>   
</form>    

</div>
@stop
