@extends('appaccounting')
@section('content')

<div class="container">
    <h3>Daily Debit Memo Report</h3>
    <h5><b>{{date("F d Y",strtotime($trandate))}}</b></h5>
    <table class="table table-striped">
        <tr>
            <td>Ref No.</td>
            <td>Name</td>
            <td>Debit Sundry</td>
            <td>E-learning</td>
            <td>Misc</td>
            <td>Books</td>
            <td>Department <br> Facilities</td>
            <td>Registration</td>
            <td>Tuition</td>
            <td>Others</td>
            <td>Status</td>
        </tr>
        <?php 
        $totaldebits = 0;
        $totalelearning = 0;
        $totalmisc = 0;
        $totalbooks = 0;
        $totaldept = 0;
        $totalreg = 0;
        $totaltuition = 0;
        $totalothers = 0;
        ?>
        @foreach($records as $record)
        <tr style="text-align: right;">
            <td style="text-align: left;">{{$record->refno}}</td>
            <td style="text-align: left;">{{$record->receivefrom}}</td>
            <td>{{number_format($record->debits,2,'.',', ')}}</td>
            <td>{{number_format($record->elearning,2,'.',', ')}}</td>
            <td>{{number_format($record->misc,2,'.',', ')}}</td>
            <td>{{number_format($record->books,2,'.',', ')}}</td>
            <td>{{number_format($record->dept,2,'.',', ')}}</td>
            <td>{{number_format($record->registration,2,'.',', ')}}</td>
            <td>{{number_format($record->tuition,2,'.',', ')}}</td>
            <td>{{number_format($record->others,2,'.',', ')}}</td>
            @if($record->stat == 0)
            <td>OK</td>
            @else
            <td>Cancelled</td>
            @endif

            @if($record->stat == 0)
            <?php 
                $totaldebits = $totaldebits + $record->debits;
                $totalelearning = $totalelearning + $record->elearning;
                $totalmisc = $totalmisc + $record->misc;
                $totalbooks = $totalbooks + $record->books;
                $totaldept = $totaldept + $record->dept;
                $totalreg = $totalreg + $record->registration;
                $totaltuition = $totaltuition + $record->tuition;
                $totalothers = $totalothers + $record->others;
            ?>
            @endif
        </tr>
        @endforeach
        <tr style="background-color: #ffd400;font-weight: bold;text-align: right;">
            <td colspan="2" style="text-align: center;">Total</td>
            <td>{{number_format($totaldebits,2,'.',', ')}}</td>
            <td>{{number_format($totalelearning,2,'.',', ')}}</td>
            <td>{{number_format($totalmisc,2,'.',', ')}}</td>
            <td>{{number_format($totalbooks,2,'.',', ')}}</td>
            <td>{{number_format($totaldept,2,'.',', ')}}</td>
            <td>{{number_format($totalreg,2,'.',', ')}}</td>
            <td>{{number_format($totaltuition,2,'.',', ')}}</td>
            <td>{{number_format($totalothers,2,'.',', ')}}</td>
            <td></td>
        </tr>

    </table>
    <br>
    <a href="{{url("dmreport/print",compact('trandate'))}}" class="col-md-12 btn btn-danger">Print</a>
</div>
@stop