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
            <button class="btn btn-danger" onclick="setdate()">Set Date</button>
        </div>    
        <div class="form-group">
            <h5>Account Title :</h5>
            <select name="accountname" id="accountname" class="form-control">
                <option value="" hidden disabled selected>-- Select --</option>
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
<div class="container" id="viewprint">
    <button id="print" class="col-md-12 btn btn-primary" onclick="viewprint()">Print</button>
</div>

<script>
    $('#viewprint').hide()
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
                $('#viewprint').show()
            }
        });
        
    }
    
    function viewprint(){
        document.location="/printindividualsummary/" + $('#from').val() + "/" + $('#to').val() + "/" + $('#accountname').val();
    }
    function setdate(){
        document.location="/individualsummary/" + $('#from').val() + "/" + $('#to').val()
    }
</script>
@stop

