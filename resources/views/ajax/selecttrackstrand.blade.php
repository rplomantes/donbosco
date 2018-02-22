<label>Tracks / Strand</label>
<select name="strand" id="strand" class="form-control" >
    <option value="" hidden="hidden">-- Select Tracks / Strand --</option>
    @foreach($strands as $strand)
    <option value="{{$strand->course_strand}}">{{$strand->course_strand}}</option>
    @endforeach
</select>