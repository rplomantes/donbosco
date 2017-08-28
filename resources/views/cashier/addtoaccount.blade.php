@extends('appcashier')
@section('content')
<style>
    .slidable{
        display:none;
        width:60%;
        float:right;
        margin-left:10px;
    }
    .remark{
    cursor: pointer;
    max-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    }
</style>
<div class="container-fluid">
    
    
    <div class="col-md-4">
        <table class="table table-striped">
            <tr><td>Student No</td><td>{{$studentid}}</td></tr>
             <tr><td>Name</td><td>{{$studentdetails->lastname}}, {{$studentdetails->firstname}}</td></tr>
        </table> 
        
        <form method="POST" action="{{url('addtoaccount')}}">
            {!! csrf_field() !!} 
            <div class="form-group">
                <label>Account Type</label>
                <select name="basicaccount1"   class="form-control" onchange = "getAccount(this.value,'groupaccount1')">
                    <option value="" hidden="hidden">--Select Account--</option>
                    <option value = "1">Assets</option>
                    <option value = "2">Liabilities</option>
                    <option value = "3">Equity</option>
                    <option value = "4">Income</option>
                    <option value = "5">Expense</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Account Group</label>
                <input type="hidden" name='idno' value="{{$studentid}}">
                <select id="accttype" name="accttype" class="form form-control" onchange="getParticular(this.value)">
                </select>    
            </div>
            
            <div class="form-group">
                <label>Particular</label>
                <select class="form-control" name="particular" id="particular">
                    <option value="" hidden="hidden"></option>
                </select>  
            </div>
            
            <div class="form-group">
                <label>Department</label>
            <select class="form-control" name="department">
                <option value="None">None</option>
                @foreach($acct_departments as $acct_dept)
                <option value = "{{$acct_dept->sub_department}}">{{$acct_dept->sub_department}}</option>
                @endforeach
            </select>
            </div>

            
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form form-control" id="amount" name="amount" onkeypress ="validate(event)" style="text-align: right">
            </div>
            <div class="form-group">
                <label>Particular:</label>
                <input type="text" class="form form-control" id="remark" name="remark" style="text-align: right" onkeypress ="validate2(event)">
            </div>
             <div class="form-group">
                 <input type="submit" class="form form-control btn btn-primary"  name="submit"  id="submitme" value="Add to account">
            </div> 
        </form>    
        <div class="form-group">
            <a href="{{url('cashier',$studentid)}}" class="btn btn-primary">Back To ledger</a>
        </div>    
        </div>
        <div class="col-md-8">
            <div>
            
            @if(count($ledgers)>0)
            <h5>Balance for Other Collections</h5>
            <table class="table table-striped">
                <tr><td>Description</td><td>Amount</td><td>Particular</td><td></td></tr>
                @foreach($ledgers as $ledger)
                <tr><td>{{$ledger->receipt_details}}</td><td align="right">{{number_format($ledger->amount,2)}}</td><td class="remark"  title="{{$ledger->remark}}">{{$ledger->remark}}</td><td style="text-align: right"><a href="#" onclick="deleteAccount({{$ledger->id}})" >Delete</a><input type="text" class="form-control slidable" id="remark_{{$ledger->id}}" onkeypress="submitDelete({{$ledger->id}},event)"></td></tr>
                @endforeach
            </table>    
            @endif
            </div>
            <div>
            
            @if(count($deletes)>0)
            <h5>Deleted Accounts</h5>
            <table class="table table-striped">
                <tr><td>Description</td><td>Amount</td><td>Date Deleted</td><td>Particular</td></tr>
                @foreach($deletes as $delete)
                <tr><td width="35%">{{$delete->receipt_details}}</td>
                    <td width="13%" align="right">{{number_format($delete->amount,2)}}</td>
                    <td width="20%">{{date_format(date_create($delete->created_at),"Y-m-d")}}</td>
                    <td width="42%">{{$delete->deleted}}</td>
                </tr>
                @endforeach
            </table>    
            @endif
            </div>
         </div>      
</div>
@stop

<script>
    function getAccount(group){


        $.ajax({
        type: "GET", 
        url: "/getaccount/" + group, 
        success:function(data){
            $('#accttype').html(data);
          } 
        }); 

    }
    
    function getParticular(group){
        $.ajax({
        type: "GET", 
        url: "/getparticulars/" + group, 
        success:function(data){
            $('#particular').html(data);
          } 
        }); 

    }
    
    function validate(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
            theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
        }
        
        if(key == 13){
            document.getElementById("remark").focus()            
            theEvent.preventDefault();
            return false;
            
        }
    }
    
    function validate2(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;

        if(key == 13){
            document.getElementById("submitme").focus()       
            theEvent.preventDefault();
            return false;

        }
    }

    function deleteAccount(id){
        $("#remark_"+id).slideToggle();
    }
    
    function submitDelete(id,evt){
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if(key == 13){
            arrays ={} ;
            arrays['remark'] = $("#remark_"+id).val();
            $.ajax({
                   type: "GET", 
                   url: "/addtoaccountdelete/"+id,
                   data : arrays,
                   success:function(data){
                       location.reload();
                       }
                   });            
        }

    }

</script>  
