<div class="container">
    <h3>List of Journal Entry</h3>
    <h5>Date : {{$voucher->trandate}}</h5>
        <table class="table table-bordered table-striped"><tr><td>Journal Voucher No</td><td>Remarks</td><td>Amount</td><td>View</td></tr>
         
           <tr><td>{{$voucher->referenceid}}</td><td>{{$voucher->remarks}}</td><td>{{$voucher->amount}}</td><td><a href="{{url('printjournalvoucher',$voucher->refno)}}"> View Voucher </a></td></tr>
           
        </table>
</div>