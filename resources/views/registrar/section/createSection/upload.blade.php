<div class='panel panel-danger'>
    <div class='panel-heading'>Upload Sections</div>
    <div class='panel-body'>
        For quick input of new sections. You can upload the information from here.Just follow the format <a href="{{ asset('/images/import_section.png') }}">here</a>.Please upload at your own discresion.
        <br>
        <form method='POST' action="{{route('uploadSection')}}" enctype="multipart/form-data">
            {!!csrf_field()!!}
            <input type="file" name="sections" id="sections" class="form-control">
            <br>
            <button type='submit' class='btn btn-danger col-md-12'>Upload</button>
        </form>
    </div>
</div>