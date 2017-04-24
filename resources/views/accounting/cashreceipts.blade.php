@extends('appaccounting')
@section('content')
<div class="container-fluid">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th>Receipt No.</th>
                <th>Name</th>
                <th>Debit <br> Cash/Checks</th>
                <th>Debit <br> Discount</th>
                <th>Debit <br> FAPE</th>
                <th>Debit <br> Reservation</th>
                <th>Debit <br> Deposit</th>
                <th>E-learning</th>
                <th>Misc</th>
                <th>Books</th>
                <th>Department <br> Facilities</th>
                <th>Registration</th>
                <th>Tuition</th>
                <th>Reservation</th>
                <th>Others</th>
                <th>Status</th>
            </tr>
        </thead>
        <?php
        $cash = 0;
        $discount = 0;
        $fape = 0;
        $dreservation = 0;
        $deposit = 0;
        $elearning = 0;
        $misc = 0;
        $book = 0;
        $department = 0;
        $registration = 0;
        $tuition = 0;
        $creservation = 0;
        $others = 0;
        if(count($forwarder) > 0){
            foreach($forwarder as $forward){
                $cash = $cash + $forward->cash;
                $discount = $discount + $forward->discount;
                $fape = $fape + $forward->fape;
                $dreservation = $dreservation + $forward->dreservation;
                $deposit = $deposit + $forward->deposit;
                $elearning = $elearning + $forward->elearning;
                $misc = $misc + $forward-> misc;
                $book = $book + $forward->book;
                $department = $department + $forward->dept;
                $registration = $registration + $forward->registration;
                $tuition = $tuition + $forward->tuition;
                $creservation = $creservation + $forward->creservation;
                $others = $others + $forward->csundry;
            }
        }
        ?>
        <tbody>
            <tr>
                <td colspan="2">Balance brought forward</td>
                <td>{{$cash}}</td>
                <td>{{$discount}}</td>
                <td>{{$fape}}</td>
                <td>{{$dreservation}}</td>
                <td>{{$deposit}}</td>
                <td>{{$elearning}}</td>
                <td>{{$misc}}</td>
                <td>{{$book}}</td>
                <td>{{$department}}</td>
                <td>{{$registration}}</td>
                <td>{{$tuition}}</td>
                <td>{{$creservation}}</td>
                <td>{{$others}}</td>
                <td></td>
            </tr>
            @foreach($currTrans as $trans)
            <tr>
                <td>{{$trans->receiptno}}</td>
                <td>{{$trans->from}}</td>
                <td>{{$trans->cash}}</td>
                <td>{{$trans->discount}}</td>
                <td>{{$trans->fape}}</td>
                <td>{{$trans->dreservation}}</td>
                <td>{{$trans->deposit}}</td>
                <td>{{$trans->elearning}}</td>
                <td>{{$trans->misc}}</td>
                <td>{{$trans->book}}</td>
                <td>{{$trans->dept}}</td>
                <td>{{$trans->registration}}</td>
                <td>{{$trans->tuition}}</td>
                <td>{{$trans->creservation}}</td>
                <td>{{$trans->csundry}}</td>
                <td>
                    @if($trans->isreverse == 0)
                        OK
                    @else
                        Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop