
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{route('deleteSection')}}">
        <div class="modal-header">
            <div class='container-fluid'>
                <div class='col-md-11'>
                    <h4 class="modal-title" id="exampleModalLabel">Delete Section</h4>
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
                You are about to delete this section. All the students currently enlisted in thes section will be removed.
                <br><br>
                <b>Details:</b>
                <table class='table table-bordered'>
                    <tr>
                        <td>Level</td><td>{{$section->level}}</td>
                    </tr>
                    @if($section->strand != "")
                    <tr>
                        <td>Strand / Course</td><td>{{$section->strand}}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Section</td><td>{{$section->section}}</td>
                    </tr>
                    <tr>
                        <td>No. of Students</td><td>{{$section->students->count('id')}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {!!csrf_field()!!}
                <input type="hidden" name="section" id="section" value="{{$section->id}}">
                <button type="submit" class="btn btn-danger">Delete</button>
        </div>
        </form>
    </div>
</div>

