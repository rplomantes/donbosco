@extends('appaccounting')
@section('content')
<div class="container">
    <div class="col-md-6">
        <div class='form-group'>
            <h5>Date Range :</h5>
            <label> From : </label>
            <input type = 'text' value="{{$fromdate}}" id="from" name="from">
            <label> To : </label>
            <input type = "text" value="{{$todate}}" id="to" name="to">
        </div>    
        <div class="form-group">
            <h5>Account Title :</h5>
            <select name="accountname" id="accountname" class="form-control">
                <option value="">-- Select --</option>
                @foreach($acctcodes as $acctcode)
                <option value="{{$acctcode->acctcode}}">{{$acctcode->accountname}}</option>
                @endforeach
            </select>    
        </div>    
        <div class="form-group">
            <input type="submit" name="submit" id="submit" class="btn btn-primary" onclick="viewreport()">
        </div>
    </div>
</div>
<div class="container" id="viewreport">
    
</div>
<script>
    function viewreport(){
        var arrays = {};
        arrays['from'] = $("#from").val();
        arrays['to'] = $("#to").val();
        arrays['account'] = $("#accountname").val();
        
        $.ajax({
            type:"GET",
            url:"/getindividualaccount",
            data:arrays,
            success:function(data){
                $("#viewreport").html(data);
            }
        });
        
    }
</script>
@stop

