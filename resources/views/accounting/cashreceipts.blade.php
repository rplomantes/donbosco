@extends('appaccounting')
@section('content')
<div class="container-fluid">
    <h3>Cash Receipt Report</h3>
    
    <div class="form-group">
        <div class="col-md-2">
            <label>For : </label>
            <input type="text" id="fromtran" class="form-control" value="{{$transactiondate}}" readonly="readonly">
        </div>    
    </div>
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
        
        $totalcash = 0;
        $totaldiscount = 0;
        $totalfape = 0;
        $totaldreservation = 0;
        $totaldeposit = 0;
        $totalelearning = 0;
        $totalmisc = 0;
        $totalbook = 0;
        $totaldepartment = 0;
        $totalregistration = 0;
        $totaltuition = 0;
        $totalcreservation = 0;
        $totalothers = 0;
        ?>
        <tbody>
            <tr>
                <td colspan="2">Balance brought forward</td>
                <td>{{number_format($cash, 2, '.', ',')}}</td>
                <td>{{number_format($discount, 2, '.', ',')}}</td>
                <td>{{number_format($fape, 2, '.', ',')}}</td>
                <td>{{number_format($dreservation, 2, '.', ',')}}</td>
                <td>{{number_format($deposit, 2, '.', ',')}}</td>
                <td>{{number_format($elearning, 2, '.', ',')}}</td>
                <td>{{number_format($misc, 2, '.', ',')}}</td>
                <td>{{number_format($book, 2, '.', ',')}}</td>
                <td>{{number_format($department, 2, '.', ',')}}</td>
                <td>{{number_format($registration, 2, '.', ',')}}</td>
                <td>{{number_format($tuition, 2, '.', ',')}}</td>
                <td>{{number_format($creservation, 2, '.', ',')}}</td>
                <td>{{number_format($others, 2, '.', ',')}}</td>
                <td></td>
            </tr>
            @foreach($currTrans as $trans)
            <?php
                if($trans->isreverse == 0){
                    $totalcash = $totalcash + $trans->cash;
                    $totaldiscount = $totaldiscount + $trans->discount;
                    $totalfape = $totalfape + $trans->fape;
                    $totaldreservation = $totaldreservation + $trans->dreservation;
                    $totaldeposit = $totaldeposit + $trans->deposit;
                    $totalelearning = $totalelearning + $trans->elearning;
                    $totalmisc = $totalmisc + $trans->misc;
                    $totalbook = $totalbook + $trans->book;
                    $totaldepartment = $totaldepartment + $trans->dept;
                    $totalregistration = $totalregistration + $trans->registration;
                    $totaltuition = $totaltuition + $trans->tuition;
                    $totalcreservation = $totalcreservation + $trans->creservation;
                    $totalothers = $totalothers + $trans->csundry;
                }
            ?>
            
            <tr>
                <td>{{$trans->receiptno}}</td>
                <td>{{$trans->from}}</td>
                <td>{{number_format($trans->cash, 2, '.', ',')}}</td>
                <td>{{number_format($trans->discount, 2, '.', ',')}}</td>
                <td>{{number_format($trans->fape, 2, '.', ',')}}</td>
                <td>{{number_format($trans->dreservation, 2, '.', ',')}}</td>
                <td>{{number_format($trans->deposit, 2, '.', ',')}}</td>
                <td>{{number_format($trans->elearning, 2, '.', ',')}}</td>
                <td>{{number_format($trans->misc, 2, '.', ',')}}</td>
                <td>{{number_format($trans->book, 2, '.', ',')}}</td>
                <td>{{number_format($trans->dept, 2, '.', ',')}}</td>
                <td>{{number_format($trans->registration, 2, '.', ',')}}</td>
                <td>{{number_format($trans->tuition, 2, '.', ',')}}</td>
                <td>{{number_format($trans->creservation, 2, '.', ',')}}</td>
                <td>{{number_format($trans->csundry, 2, '.', ',')}}</td>
                <td>
                    @if($trans->isreverse == 0)
                        OK
                    @else
                        Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td>{{number_format($totalcash, 2, '.', ',')}}</td>
                <td>{{number_format($totaldiscount, 2, '.', ',')}}</td>
                <td>{{number_format($totalfape, 2, '.', ',')}}</td>
                <td>{{number_format($totaldreservation, 2, '.', ',')}}</td>
                <td>{{number_format($totaldeposit, 2, '.', ',')}}</td>
                <td>{{number_format($totalelearning, 2, '.', ',')}}</td>
                <td>{{number_format($totalmisc, 2, '.', ',')}}</td>
                <td>{{number_format($totalbook, 2, '.', ',')}}</td>
                <td>{{number_format($totaldepartment, 2, '.', ',')}}</td>
                <td>{{number_format($totalregistration, 2, '.', ',')}}</td>
                <td>{{number_format($totaltuition, 2, '.', ',')}}</td>
                <td>{{number_format($totalcreservation, 2, '.', ',')}}</td>
                <td>{{number_format($totalothers, 2, '.', ',')}}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Current Balance</td>
                <td>{{number_format($totalcash+$cash, 2, '.', ',')}}</td>
                <td>{{number_format($totaldiscount+$discount, 2, '.', ',')}}</td>
                <td>{{number_format($totalfape+$fape, 2, '.', ',')}}</td>
                <td>{{number_format($totaldreservation+$dreservation, 2, '.', ',')}}</td>
                <td>{{number_format($totaldeposit+$deposit, 2, '.', ',')}}</td>
                <td>{{number_format($totalelearning+$elearning, 2, '.', ',')}}</td>
                <td>{{number_format($totalmisc+$misc, 2, '.', ',')}}</td>
                <td>{{number_format($totalbook+$book, 2, '.', ',')}}</td>
                <td>{{number_format($totaldepartment+$department, 2, '.', ',')}}</td>
                <td>{{number_format($totalregistration+$registration, 2, '.', ',')}}</td>
                <td>{{number_format($totaltuition+$tuition, 2, '.', ',')}}</td>
                <td>{{number_format($totalcreservation+$creservation, 2, '.', ',')}}</td>
                <td>{{number_format($totalothers+$others, 2, '.', ',')}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <a href="/printcashreceipt" class="btn btn-primary col-md-12">Print</a>
<div class="col-md-12">
    <h4>Sundry Accounts Breakdown</h4>
    <table class="table table-stripped col-md-4">
	<thead>
	  <tr>
		<td>Acct. No</td>
		<td>Account Name</td>
		<td>Amount</td>
	  </tr>
	</thead>
	<tbody>
	@foreach($breakdown as $breakd)
	 <tr>
		<td>{{$breakd->accountingcode}}</td>
		<td>{{$breakd->acctcode}}</td>
		<td>{{$breakd->amount}}</td>
	 </tr>
	@endforeach
	</tbody>
    </table>
    <a href="/printcashbreakdown/{{$transactiondate}}" class="btn btn-primary col-md-12">Print</a>
</div>
</div>
@stop
