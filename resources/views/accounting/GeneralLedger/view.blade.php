@extends('appaccounting')
@section('content')
<div class='container'>
    <div class='col-md-12'><h3>GENERAL LEDGER REPORT</h3></div>
    <div class="row">
        <div class="col-md-6">
            <dl class="dl-horizontal">
                <dt>Accounts</dt>
                <dd>
                    <select class="form-control" id='account' >
                    </select>
                </dd>
            </dl>
            <dl class="dl-horizontal">
                <dt>Covered Period: </dt>
                <dd>
                    <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'>
                        <input class="form-control col-md-5" id="from" name="fromdate" value='{{$from}}'>
                    </div>
                    <div class='col-md-2' style='padding-left: 0px;padding-right: 0px;text-align: center;height: 34px;    padding: 6px 12px;'><b>to</b></div>
                    <div class='col-md-5' style='padding-left: 0px;padding-right: 0px;'><input class='form-control col-md-5' id="to" name="to" value="{{$to}}"></div>
                </dd>
            </dl>
            <a href="#" onclick="gotopage()" class="btn btn-primary navbar-right">View Report</a>
            
        </div>
    </div>
</div>
@stop