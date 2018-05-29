<?php
use App\Http\Controllers\Registrar\Section\SectionHelper;
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{route('updateSection')}}">
        {!!csrf_field()!!}
        <input type="hidden" name="section" id="section" value="{{$section->id}}">
        <div class="modal-header">
            <div class='container-fluid'>
                <div class='col-md-11'>
                    <h4 class="modal-title" id="exampleModalLabel">Update Section</h4>
                </div>
                <div class='col-md-1'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class='container-fluid'>
                <div class='form-group col-md-12'>
                    <label class='label-control col-md-4'>Level</label>
                    <div class='col-md-8'>
                        {{$section->level}}
                    </div>
                </div>
                @if($section->strand != "")
                    <div class='form-group col-md-12'>
                        <label class='label-control col-md-4'>Strand</label>
                        <div class='col-md-8'>
                            {{$section->strand}}
                        </div>
                    </div>
                @endif
                <div class='form-group col-md-12'>
                    <label class='label-control col-md-4'>Section Name</label>
                    <div class='col-md-8'>
                        <input class='form-control' type='text' id='sectionname' name='sectionname' value='{{$section->section}}'>
                    </div>
                </div>
                <div class='form-group col-md-12'>
                    <label class='label-control col-md-4'>Adviser</label>
                    <div class='col-md-8'>
                        <select class='form-control ' name='adviser' id='updateAdviser'>
                            <option value="" hidden='hidden'>--Select Adviser--</option>
                            @foreach($advisers as $adviser)
                            <option value="{{$adviser->idno}}">{{SectionHelper::get_name($adviser->idno)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Update</button>
        </div>
        </form>
    </div>
</div>

<script>    
    $('#updateAdviser').val("{{$section->adviserid}}")
    $('#updateAdviser').combify();
</script>