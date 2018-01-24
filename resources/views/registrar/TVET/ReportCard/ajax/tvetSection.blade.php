@if($sections)
<option value="" hidden='hidden'>-- Select Section --</option>
@foreach($sections as $section)
<option value="{{$section->id}}">{{$section->section}}</option>
@endforeach
@else
<option value="" disabled="disabled">No section for the selection</option>
@endif