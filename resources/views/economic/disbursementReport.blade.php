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
            <?php $totaldisbursement ?>
            <tr>
                <td>{{date_format($disbursement->month,"M")}}</td>
                <td>{{number_format($disbursement->amount,2 )}}</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td>{{number_format($disbursement->amount,2 )}}</td>
            </tr>
        </table>
    </div>
</div>
@stop