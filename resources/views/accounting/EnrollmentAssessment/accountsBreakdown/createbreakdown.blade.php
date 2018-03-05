<?php
$offices = \App\CtrAcctDep::pluck('sub_department')->toArray();
?>
@extends('appaccounting')
@section('content')
  <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
  <script src="{{asset('/js/jquery-ui.js')}}"></script>
  <style>
      .account{
          width:80%;
      }
</style>  
  
  
  <script>
   $( function() {
    var coa = [<?php echo '"'.implode('","', $coa).'"' ?>];
    $( ".coa" ).autocomplete({
      source: coa
    });
    
    var office = [<?php echo '"'.implode('","', $offices).'"' ?>];
    $( ".offices" ).autocomplete({
      source: office
    });
    });
    
  </script>
  
  
<div class='container-fluid'>
    
    <div class="col-md-3">
        @include('accounting.EnrollmentAssessment.leftmenu')
    </div>
    <div class="col-md-9"  id='content'>
        <h4>{{$module_info['title']}} - {{$level}}</h4>
        <hr>
        <div class='panel-body'>
            <button class="btn btn-danger " id='addacct'>Add account</button>
        </div>
        
        <form action="{{url('submitassessment')}}" method="POST" >
        {!! csrf_field() !!} 
        <div id='form'>
        <input type='hidden' name='level' value='{{$level}}'>
        <input type='hidden' name='course' value='{{$course}}'>
        <?php $count = 1;?>
        @foreach($assessments->groupBy('accountingcode') as $assessment)
        <div class='col-md-2'><input class='form-control' id='acctcode{{$count}}' value='{{$assessment->pluck("accountingcode")->last()}}' name='acctcode[{{$count}}]' readonly='readonly'></div>
        <div class='col-md-4'><input class='form-control coa' id='acct{{$count}}' value='{{$assessment->pluck("accountname")->last()}}' name='acct[{{$count}}]' onkeypress='get_accountcode({{$count}},event)'></div>
        <button type='button' onclick='addsub({{$count}})' class='btn btn-success fa fa-plus' id='addsubacct'></button>
        
       <table class='table table-responsive account' id='account{{$count}}table' style="margin-left:20%">
           <thead>
                <tr>
                    <td>Subsidiary</td>
                    <td>Amount</td>
                    <td>Office</td>
                    <td></td>
                </tr>
            </thead>
        <?php $row = 1;?>
            <tbody>
        @foreach($assessment as $account)
            
                <tr class='rows' id='row{{$count}}{{$row}}'>
                    <td><input value="{{$account->description}}" class='form-control' name='subacct[{{$count}}][{{$row}}]'></td>
                    <td><input value="{{$account->amount}}" class='form-control divide amount' name='amount[{{$count}}][{{$row}}]'></td>
                    <td><input value="{{$account->sub_department}}" class='form-control offices' name='office[{{$count}}][{{$row}}]'></td>
                    <td><button type='button' onclick='deletesub("row{{$count}}{{$row}}")' class='btn btn-danger fa fa-minus'></button></td>
                </tr>
            
        <?php $row++;?>
        @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td><input class='divide' value="{{$assessment->sum('amount')}}" id="total{{$count}}"></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
       </table>
        <?php $count++;?>
        @endforeach
        
        </div>
        <div class='panel-body'><button class="col-md-offset-4 col-md-3 btn btn-success">Submit</button></div>
    </form>
    </div>

</div>
  
<script>
     $('tbody').sortable();
    $('#addacct').click(function(){
        var accountsnumber = $('.account').length +1;
        
        var add = "<div class='col-md-2'>\n\
                    <input class='form-control' id='acctcode"+accountsnumber+"' name='acctcode["+accountsnumber+"]' readonly='readonly'>\n\
                   </div>\n\
                   <div class='col-md-4'>\n\
                    <input class='form-control coa' id='acct"+accountsnumber+"' name='acct["+accountsnumber+"]' onkeypress='get_accountcode("+accountsnumber+",event)'>\n\
                   </div>\n\
                   <button type='button' onclick='addsub("+accountsnumber+")' class='btn btn-success fa fa-plus' id='addsubacct'></button>\n\
                   <table class='table table-responsive account' id='account"+accountsnumber+"table' style='margin-left:20%'>\n\
                   <tbody><tr><td>Subsidiary</td><td>Amount</td><td>Office</td><td></td></tr></tbody>\n\
                   <tfoot>\n\
                        <tr><td>Total</td><td><input class='divide' id='total"+accountsnumber+"'>\n\
                        </td><td></td><td></td></tr>\n\
                    </tfoot>\n\
                    </table>";
                        
        $('#form').append(add);
        
        $('.amount').keyup(function(){
            updateamount()
        })
        
        var coa = [<?php echo '"'.implode('","', $coa).'"' ?>];
        $( ".coa" ).autocomplete({
          source: coa
        });
    });
    
    $('.offices').keypress(function(event){

        if (event.keyCode === 10 || event.keyCode === 13) 
            event.preventDefault();

      });
    
    function get_accountcode(count,evt){
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;

        if(key == 13){
            theEvent.preventDefault();
            
            var arrays={};
            arrays['accountname']=$("#acct"+count).val();
            
            $.ajax({
            type:"GET",
            url:"/getaccountcode",
            data:arrays,
                success:function(data){
                $("#acctcode"+count).val(data)
                }    
            });
            
        }
    }
    
    
    function addsub(account){
        var rownumber = $("#account"+account+"table .rows").length +1;
        var row = "<tr class='rows' id='row"+account+""+rownumber+"'>\n\
                    <td><input class='form-control' name='subacct["+account+"]["+rownumber+"]'></td>\n\
                    <td><input class='form-control amount' name='amount["+account+"]["+rownumber+"]'></td>\n\
                    <td><input class='form-control offices' name='office["+account+"]["+rownumber+"] onkeyup='update(this)'></td>\n\
                    <td><button type='button' onclick='deletesub(\"row"+account+""+rownumber+"\")' class='btn btn-danger fa fa-minus'></button>\n\
                    </td>\n\
                    </tr>";
                        
        $("#account"+account+"table tbody").append(row);
        
        var office = [<?php echo '"'.implode('","', $offices).'"' ?>];
        $( ".offices" ).autocomplete({
          source: office
        });
        
        $('.offices').keypress(function(event){

            if (event.keyCode === 10 || event.keyCode === 13) 
                event.preventDefault();

          });
       
        $('.amount').keyup(function(){
            updateamount()
        })
        
        $('.amount').inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        oncleared: function () { self.Value(''); },
        autoUnmask : true,
        removeMaskOnSubmit  :   true
    });
    }
    
    function deletesub(row){
        $("#"+row).remove();
    }
    
    $('.amount').keyup(function(){
        updateamount()
    })

    function updateamount(){
        $('.account').each(function(index){
            var count = index+1;
            var table = $( this ).attr('id');
            var sum = 0;
            $('#'+table+' .amount').each(function(){
                sum += parseFloat($( this ).val());
            });
            $('#'+table+' #total'+count).val(sum)
        })
    }

    
</script>
@stop