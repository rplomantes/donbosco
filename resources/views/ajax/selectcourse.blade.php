<label>Course</label>
<select name="course" id="course" class="form-control" 
        @if($action != null)
        onchange="{{$action}}()"
        @endif
        >
    <option value="" hidden="hidden">-- Select Course --</option>
    @foreach($courses as $course)
    <option value="{{$course->course}}">{{$course->course}}</option>
    @endforeach
</select>