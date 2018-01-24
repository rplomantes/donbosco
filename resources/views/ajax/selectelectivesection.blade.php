<label>Section</label>
<select name="section" id="section" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Section --</option>
    @if($allavailable == 1)
    <option value="All">All</option>
    @endif
    @foreach($sections as $section)
    <option value="{{$section->id}}">{{$section->section}}</option>
    @endforeach
</select>