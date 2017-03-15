@extends('appaccounting')
@section('content')
<style>
    @media (min-width: 768px){
        .dl-horizontal dd {
            margin-left: 140px;
        }
        .dl-horizontal dt {
            width:130px;
        }
    }
</style>
<div class='container'>
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Accounts</dt>
            <dd>
                <select class="form-control" id='accounts' onchange = "getAccount(this.value)">
                    <option value = "1">Assets</option>
                    <option value = "2">Liabilities</option>
                    <option value = "3">Equity</option>
                    <option value = "4">Income</option>
                    <option value = "5">Expense</option>
                </select>
            </dd>
        </dl>
    </div>
</div>

<div class='container'>
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Account Title</dt>
            <dd>
                <select class="form-control" id='title' >
                </select>
            </dd>
        </dl>
    </div>
</div>

<div class='container'>
    <div class='col-md-6'>
        <dl class="dl-horizontal">
            <dt>Covered Period: </dt>
            <dd>
                <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'>
                <input class="form-control col-md-5" id="fromdate" name="fromdate" value='{{$from}}'>
                </div>
                <div class='col-md-2' style='padding-left: 0px;padding-right: 0px;text-align: center;height: 34px;    padding: 6px 12px;'><b>to</b></div>
                <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'><input class='form-control col-md-5' id="todate" name="todate" value="{{$to}}"></div>
            </dd>
        </dl>
    </div>
</div>
@if($basic > 0)
    <?php
        if($title == "All"){
            $accounts = App\CtrOtherPayment::where('acctcode','like',$basic.'%')->get();
        }else{
            $accounts = App\CtrOtherPayment::where('acctcode',$title)->get();
        }
    ?>

@foreach($accounts as $account)
    <?php 
    $date = $fiscalyear->fiscalyear."-";
    $endOfCycle = $diff;
    $count = 0;
    $startmonth = 5;
    ?>
    <?php do{ 
        $currmonth = $date.$startmonth;?>
        {{$currmonth}}
        <?php 
        if($startmonth == 12){
            $startmonth = 1;
        }else{
            $startmonth = $startmonth+1;
        }
        
        $count++;
     }while($count< $diff+1); ?>
@endforeach

@endif
<script>
    
    function getAccount(group){

               // alert(particular);
                $.ajax({
                type: "GET", 
                url: "/getaccount/" + group, 
                success:function(data){
                    $('#title').html(data);
                  } 
                }); 

            }
</script>
@stop
