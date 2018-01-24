<label>Subjects</label>
<select name="section" id="section" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Subject --</option>
    <optgroup value="0" label="Conduct">
        <option value="3">Good Manners and Right Conduct</option>
    </optgroup>
    <optgroup value="0" label="Subjects">
    @foreach($subjects as $subject)
        <option value="{{$subject->subjectcode}}">{{$subject->subjectname}}</option>
    @endforeach
    </optgroup>
</select>