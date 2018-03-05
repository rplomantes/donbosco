@extends('appaccounting')
@section('content')
<div class='container-fluid'>
   <div class="col-md-3">
        @include('accounting.EnrollmentAssessment.leftmenu')
    </div>
    <div class='col-md-9'>
        <h4>{{$module_info['title']}}</h4>
        <hr>
        <div class='col-md-12'>
            <label>Discounts</label>
            <select class='form-control'>
                @foreach($discounts->groupBy('description') as $discount)
                <option value=''>{{$discount->pluck('description')->last()}}</option>
                @endforeach
            </select>
        </div>
        <div class='col-md-12' id='discountDetails'>
        </div>
    </div>
</div>
@stop