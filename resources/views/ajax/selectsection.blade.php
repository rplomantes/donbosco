<label>Section</label>
<select name="section" id="section" class="form-control" 
        @if($action != null)
        onchange="{{$action}}()"
        @endif
        >
    <option value="" hidden="hidden">-- Select Section --</option>
    <option value="All">All</option>
    @foreach($sections as $section)
    <option value="{{$section->section}}">{{$section->section}}</option>
    @endforeach
</select>