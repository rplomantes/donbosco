@extends('appmisc')
@section('content')
<div class="container">
<h3>Operation Income</h3>
<div class="col-md-6">
    <table class="table table-borderless">
    <tr>
        <td>Account</td>
    </tr>
    <tr>
        <td>Revenue</td>
        <td>{{number_format($totalincome)}}</td>
        <td></td>
    </tr>
    @foreach($fund as $fund)
    <tr>
        <td>{{$fund->accountname}}</td>
        <td>{{$fund->debit}}</td>
        <td>{{$fund->credits}}</td>
    </tr>
    @endforeach
    </table>

    
</div>
<div class="col-md-6">
<div style="width:100%;">
    {!!$chart->render()!!}
</div>    
</div>

</div>
@stop