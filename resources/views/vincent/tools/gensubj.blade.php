<div class="col-md-1">
<h2>Prep</h2>
@foreach($subjs as $subj)
@if($subj->P == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 1</h2>
@foreach($subjs as $subj)
@if($subj->I == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 2</h2>
@foreach($subjs as $subj)
@if($subj->II == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 3</h2>
@foreach($subjs as $subj)
@if($subj->III == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 4</h2>
@foreach($subjs as $subj)
@if($subj->IV == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 5</h2>
@foreach($subjs as $subj)
@if($subj->V == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 6</h2>
@foreach($subjs as $subj)
@if($subj->VI == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>
<div class="col-md-1">
<h2>Grade 7</h2>
@foreach($subjs as $subj)
@if($subj->ONE == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>

<div class="col-md-1">
<h2>Grade 8</h2>
@foreach($subjs as $subj)
@if($subj->TWO == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>

<div class="col-md-1">
<h2>Grade 9</h2>
@foreach($subjs as $subj)
@if($subj->THREE == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>

<div class="col-md-1">
<h2>Grade 10</h2>
@foreach($subjs as $subj)
@if($subj->FOUR == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>

<div class="col-md-1">
<h2>Grade 11</h2>
@foreach($subjs as $subj)
@if($subj->FIVE == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>

<div class="col-md-1">
<h2>Grade 12</h2>
@foreach($subjs as $subj)
@if($subj->SIX == "Yes" && $subj->CLASS !="N")
{{$subj->SUBJ_CODE}}<br>
@endif
@endforeach
</div>