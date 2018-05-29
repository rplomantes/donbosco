
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method='POST' action="{{route('saveAdviser')}}">
            {!!csrf_field()!!}
        <div class="modal-header">
            <div class='container-fluid'>
                <div class='col-md-11'>
                    <h4 class="modal-title" id="exampleModalLabel">Add Adviser</h4>
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
                        <label class='label-control col-md-4'>Title</label>
                        <div class='col-md-8'>
                            <input class='form-control' type='text' id='title' name='title' placeholder="Mr.,Ms.,Mrs.">
                        </div>
                    </div>
                    <div class='form-group col-md-12'>
                        <label class='label-control col-md-4'>First Name</label>
                        <div class='col-md-8'>
                            <input class='form-control' type='text' id='firstname' name='firstname'>
                        </div>
                    </div>
                    <div class='form-group col-md-12'>
                        <label class='label-control col-md-4'>Middle Name</label>
                        <div class='col-md-8'>
                            <input class='form-control' type='text' id='middlename' name='middlename'>
                        </div>
                    </div>
                    <div class='form-group col-md-12'>
                        <label class='label-control col-md-4'>Last Name</label>
                        <div class='col-md-8'>
                            <input class='form-control' type='text' id='lastname' name='lastname'>
                        </div>
                    </div>
                    <div class='form-group col-md-12'>
                        <label class='label-control col-md-4'>Extension Name</label>
                        <div class='col-md-8'>
                            <input class='form-control' type='text' id='extensionname' name='extensionname'>
                        </div>
                  </div>
                
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>
    </div>
</div>

