@extends('appaccounting')
@section('content')
<div class="conatainer">
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Disbursement</th>
                </tr>
            </thead>
            <?php $totaldisbursement = 0; ?>
            @foreach($disbursements as $disbursement)
            <?php $totaldisbursement = $totaldisbursement + $disbursement->amount; ?>
            <tr>
                <td>{{date("F",strtotime("2017-0".$disbursement->month."-01"))}}</td>
                <td>{{number_format($disbursement->amount,2 )}}</td>
            </tr>
            @endforeach
            <tr>
                <td>Total</td>
                <td>{{number_format($totaldisbursement,2 )}}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6" id="chart-div">
    <?= Lava::render('BarChart', 'Disbursements', 'chart-div') ?>
    </div>
</div>
@stop