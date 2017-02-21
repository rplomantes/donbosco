@extends('appcashier')
@section('content')
<style type="text/css">
.disabled {
   pointer-events: none;
   cursor: default;
}    
</style>
    
<div class="container">
<form method="POST" action="{{url('addtobatchaccount')}}">    
<div class="col-md-6">
        {!! csrf_field() !!} 
        <div class="form-group">
            <label>Account name</label>
            <select name="accttype" class="form form-control" onchange="findSubsidy(this.value)">
                @foreach($acctcode as $account)
                <option disable hidden>--Select--</option>
                <option value= "{{$account->accounttype}}">{{$account->accounttype}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Account name</label>
            <select name="subsidy" id="subsidy" class="form form-control" onchange="setlevel()" >
                <option disable hidden>--Select--</option>
            </select>
        </div>        
        <div class="form-group">
            <label>Amount</label>
            <input type="text" class="form form-control" id="amount" name="amount" onkeypress ="validate(event)" style="text-align: right">
        </div>    
         <div class="form-group">
            <button type="submit" class=" form form-control btn btn-primary" name="submit"  id="submit" >Add to account</button>
        </div> 

        <div class="form-group">
            <a href="{{url('cashier')}}" class="btn btn-primary">Back To ledger</a>
        </div>    
        </div>
    <div class="col-md-6">
        <div class="form-group" id="levelselect">
        </div>    
        <div class="form-group" id="sectionselect">
        </div>     
        <div class="form-group" id="studentlist">
        </div>         
    </div>
    </form> 
   
</div>

<script>
    function findSubsidy(account){
        $.ajax({
            type:"GET",
            url:"/getSubsidy/"+account,
            success:function(data){
                $('#subsidy').html(data)
            }
            
        });
    }
    function setlevel(){
        $.ajax({
            type:"GET",
            url:"/getaccountlevel",
            success:function(data){
                $('#levelselect').html(data)
            }
            
        });
    }    
    
    function setsection(level){
        $.ajax({
            type:"GET",
            url:"/getsection2/"+level,
            success:function(data){
                $('#sectionselect').html(data)
            }
            
        });
    }

    function setsection(level){
        $.ajax({
            type:"GET",
            url:"/getsection2/"+level,
            success:function(data){
                $('#sectionselect').html(data)
            }
            
        });
    } 
    function getstudents(section){
            var arrays ={} ;
            arrays['section'] = section;
            arrays['level'] = document.getElementById('level').value;
            
        $.ajax({
            type: "GET", 
            url: "/studentchecklist",
            data : arrays,
            success:function(data){
                $('#studentlist').html(data)
                }
            });
    }

</script>
@stop
 