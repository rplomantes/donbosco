@extends('appaccounting')
@section('content')
<div class="container">
    <div class="form-group">
    <div class="col-md-10 col-md-offset-1">
        <input type="text"  class="form-control" placeholder="Enter Journal Voucher No" id="search"></div>
    </div>
    </div>
<div class="form-group">
    <div id ="displaysearch">
    </div>
</div>       
</div>   

<script type="text/javascript">
    $(document).ready(function(){
       $("#search").keypress(function(e){
           if(e.keyCode==13){
               if($("#search").val()==""){
                   alert("Please enter voucher no.")
               } else {
                    var arrays={};
                    arrays['search']=$("#search").val();
                    $.ajax({
                    type:"GET",
                    url:"/getjournallist",
                    data:arrays,
                        success:function(data){
                        $("#displaysearch").html(data);
                        }
                    });    
                }
           }    
       }); 
    });
    
</script>
@stop
