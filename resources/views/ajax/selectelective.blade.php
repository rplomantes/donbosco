<label>Elective</label>
<select name="elective" id="strand" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Elective --</option>
    
    @foreach($electives as $key=>$elective)
    <optgroup label="Semester {{$key}}">
        @foreach($elective as $ele)
        <option value="{{$ele->elecode}}">{{$ele->elective}}</option>
        @endforeach
    </optgroup>
    @endforeach
</select>