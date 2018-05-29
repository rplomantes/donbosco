<form class="form-horizontal" method="POST" action="">
    {!!csrf_field()!!}
    <div class="form-group col-md-12">
        <label class="label-control col-md-3">Receipt No</label>
        <div class="col-md-9">
            <input class="form-control" name="receiptno" value="{{$details->receiptno}}">
        </div>
    </div>
    <div class="form-group col-md-12">
        <label class="label-control col-md-3">Remarks</label>
        <div class="col-md-9">
            <textarea class="form-control" name="remarks">{{$details->remarks}}</textarea>
        </div>
    </div>
    @if($cash)
    <div class="form-group col-md-12">
        <label class="label-control col-md-3">Deposit To</label>
        <div class="col-md-9">
            <div class="col-md-4"><label><input type="radio" name="depositto" value="China Bank"> China Bank</label></div>
            <div class="col-md-4"><label><input type="radio" name="depositto" value="BPI 1"> BPI 1</label></div>
            <div class="col-md-4"><label><input type="radio" name="depositto" value="LANDBANK 1" > LANDBANK 1</label></div>

            <div class="col-md-4"><label><input type="radio" name="depositto" value="China Bank 2"> China Bank 2</label></div>
            <div class="col-md-4"><label><input type="radio" name="depositto" value="BPI 2"> BPI 2</label></div>
            <div class="col-md-4"><label><input type="radio" name="depositto" value="LANDBANK 2"> LANDBANK 2</label></div>
        </div>
    </div>    
    @endif
    <div class="form-group col-md-12">
        <div class="col-md-offset-3 col-md-9">
        <button class="btn btn-danger col-md-2 navbar-right">Update</button>
        </div>
    </div>
    
</form>

<script>
    @if($cash)
    $("input[name='depositto'][value='{{$cash->depositto}}']").prop('checked', true);
    @endif
</script>