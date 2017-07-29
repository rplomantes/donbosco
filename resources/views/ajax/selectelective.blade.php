<label>Elective</label>
<select name="elective" id="strand" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Elective --</option>
    @foreach($electives as $elective)
    <option value="{{$elective->elecode}}">{{$elective->elective}}</option>
    @endforeach
</select>