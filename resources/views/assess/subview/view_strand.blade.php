@if($mode == "selectStrand")
<label>Strand / Track</label>
<select class="form-control" name="strand" id='strand'>
    @foreach($strands as $getstrand)
    <option value="{{$getstrand->course_strand}}">{{$getstrand->course_strand}}</option>
    @endforeach
</select>

@endif

@if($mode == "specifiedStrand")
<!--Strand already set-->
<label>Strand / Track</label>
<div class="form-control">{{$strand}}</div>
<!--Strand already set-->
@endif