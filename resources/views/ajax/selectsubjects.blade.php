<label>Subjects</label>
<select name="section" id="section" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Subject --</option>
    @if($allavailable == 1)
    <option value="All">All</option>
    @endif
    @foreach($subjects as $subject)
    <option value="{{$subject->subjectcode}}">{{$subject->subjectname}}</option>
    @endforeach
</select>