@extends('appaccounting')
@section('content')
<?php
$forwarded = $records->where('refno','forwarded',false);
$receipts = $records->where('transactiondate',$transactiondate,false);

$subtotal = $receipts->where('isreverse',0,false);
$grand_total = $records->where('isreverse',0,false);


$entrysundies = \App\RptCashReceiptBookSundries::with('RptCashreceiptBook')->where('idno',\Auth::user()->idno)->get();

$totalsundries = $entrysundies->filter(function($item){
    if($item->RptCashreceiptBook->isreverse == 0){
        return true;
    }
});

?>

<style>
    .fixed_PD{
        position:fixed;
        right:45px;
        top:20px;
        z-index:100;
    }
    
    .fixed_bottom{
        bottom:20px;
    }
</style>

<div class='container-fluid'>
    <div class='col-md-12'><h3>CASH RECEIPT BOOK</h3></div>
    <div class="col-md-12" id='content'>    
        <div class="col-md-2">
            <label>Transaction Date</label>
            <input type="text" class="form-control" id="trandate" value="{{$transactiondate}}">
        </div>
        <div class="col-md-2">
            <button id="processbtn" class="btn btn-primary form-control">View</button>
        </div>
        <div class='row col-md-offset-6 col-md-2' id='wrap'>
            <div class='row'>
                <a  href="{{url('printcashreceipt',array($transactiondate))}}"  class='btn btn-danger col-md-12'>Print</a>
            </div>
            <br>
            <div class='row'>
                <a  href="{{url('dlcashreceipt',array($transactiondate))}}"  class='btn btn-success col-md-12'>Download</a>
            </div>

        </div>
    </div>
</div>
<div class='container-fluid'>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th rowspan="2">Receipt No.</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Debit<br>Cash / Check</th>
                <th rowspan="2">Debit<br>Discount</th>

                <th rowspan="2">E - Learning</th>
                <th rowspan="2">Misc</th>
                <th rowspan="2" rowspan="2">Book</th>
                <th rowspan="2">Department<br> Facilities</th>
                <th rowspan="2">Registration Fee</th>
                <th rowspan="2">Tuition Fee</th>
                <th rowspan="2">Credit<br>Reservation</th>
                <th colspan="3" style="text-align:center">SUNDRIES</th>
                <th rowspan="2">Status</th>
            </tr>
            <tr>
                <th>Debit<br>Sundry</th>
                <th>Credit<br>Sundry</th>
                <th>Account</th>
            </tr>
            
        </thead>
        <tbody>
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Forwarding Balance ({{date("M",strtotime($transactiondate))}})</td>
                <td>{{number_format($forwarded->sum('cash'),2)}}</td>
                <td>{{number_format($forwarded->sum('discount'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('elearning'),2)}}</td>
                <td>{{number_format($forwarded->sum('misc'),2)}}</td>
                <td>{{number_format($forwarded->sum('book'),2)}}</td>
                <td>{{number_format($forwarded->sum('dept'),2)}}</td>
                <td>{{number_format($forwarded->sum('registration'),2)}}</td>
                <td>{{number_format($forwarded->sum('tuition'),2)}}</td>
                <td>{{number_format($forwarded->sum('creservation'),2)}}</td>
                
                <td>{{number_format($forwarded->sum('dsundry'),2)}}</td>
                <td>{{number_format($forwarded->sum('csundry'),2)}}</td>
                <td colspan="2"></td>
            </tr>
            @foreach($receipts as $receipt)
            <tr style='text-align: right'>
                <td style='text-align: left'>{{$receipt->receiptno}}</td>
                <td style='text-align: left'>{{$receipt->from}}</td>
                
                <td>{{number_format($receipt->cash,2)}}</td>
                <td>{{number_format($receipt->discount,2)}}</td>

                
                <td>{{number_format($receipt->elearning,2)}}</td>
                <td>{{number_format($receipt->misc,2)}}</td>
                <td>{{number_format($receipt->book,2)}}</td>
                <td>{{number_format($receipt->dept,2)}}</td>
                <td>{{number_format($receipt->registration,2)}}</td>
                <td>{{number_format($receipt->tuition,2)}}</td>
                <td>{{number_format($receipt->creservation,2)}}</td>
                
                <td>
                    <table border="0" width='100%'>
                        @foreach($entrysundies->where('refno',$receipt->refno,false) as $sundry_entry)
                        <tr><td align="right">@if($sundry_entry->debit != 0){{number_format($sundry_entry->debit,2)}}@else &nbsp; @endif</td></tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    <table border="0" width='100%'>
                        @foreach($entrysundies->where('refno',$receipt->refno,false) as $sundry_entry)
                        <tr><td align="right">@if($sundry_entry->credit != 0){{number_format($sundry_entry->credit,2)}}@else &nbsp; @endif</td></tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    <table border="0" width='100%'>
                        @foreach($entrysundies->where('refno',$receipt->refno,false) as $sundry_entry)
                        <tr><td align="left" style="white-space:nowrap">{{$sundry_entry->particular}}</td></tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    @if($receipt->isreverse == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Total</td>
                <td>{{number_format($subtotal->sum('cash'),2)}}</td>
                <td>{{number_format($subtotal->sum('discount'),2)}}</td>
                
                
                <td>{{number_format($subtotal->sum('elearning'),2)}}</td>
                <td>{{number_format($subtotal->sum('misc'),2)}}</td>
                <td>{{number_format($subtotal->sum('book'),2)}}</td>
                <td>{{number_format($subtotal->sum('dept'),2)}}</td>
                <td>{{number_format($subtotal->sum('registration'),2)}}</td>
                <td>{{number_format($subtotal->sum('tuition'),2)}}</td>
                <td>{{number_format($subtotal->sum('creservation'),2)}}</td>
                
                <td>{{number_format($subtotal->sum('dsundry'),2)}}</td>
                <td>{{number_format($subtotal->sum('csundry'),2)}}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan='16'><br></td>
            </tr>
            <tr style='text-align: right'>
                <td colspan="2" style='text-align: left'>Current Balance</td>
                <td>{{number_format($grand_total->sum('cash'),2)}}</td>
                <td>{{number_format($grand_total->sum('discount'),2)}}</td>

                
                <td>{{number_format($grand_total->sum('elearning'),2)}}</td>
                <td>{{number_format($grand_total->sum('misc'),2)}}</td>
                <td>{{number_format($grand_total->sum('book'),2)}}</td>
                <td>{{number_format($grand_total->sum('dept'),2)}}</td>
                <td>{{number_format($grand_total->sum('registration'),2)}}</td>
                <td>{{number_format($grand_total->sum('tuition'),2)}}</td>
                <td>{{number_format($grand_total->sum('creservation'),2)}}</td>
                
                <td>{{number_format($grand_total->sum('dsundry'),2)}}</td>
                <td>{{number_format($grand_total->sum('csundry'),2)}}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</div>
<br>

<div class='container-fluid'>
    <div class="col-md-4">
        <div class='panel panel-default'>
            <div class='panel-heading'>Debit Sundry</div>
            <div class='panel-body'>
                <table class='table table-bordered'>
                    <tr>
                        <th>Account</th>
                        <th>Amount</th>
                    </tr>
                    @foreach($totalsundries->groupBy('accountingcode') as $debitsundry)
                    @if($debitsundry->sum('debit') > 0)
                    <tr>
                        <td>{{$debitsundry->pluck('particular')->last()}}</td>
                        <td align='right'>{{number_format($debitsundry->sum('debit'),2)}}</td>
                    </tr>
                    @endif
                    @endforeach 
                    <tr>
                        <td>Total</td>
                        <td  align='right'>{{number_format($totalsundries->sum('debit'),2)}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class='panel panel-default'>
            <div class='panel-heading'>Credit Sundry</div>
            <div class='panel-body'>
                <table class='table table-bordered'>
                    <tr>
                        <th>Account</th>
                        <th>Amount</th>
                    </tr>
                    @foreach($totalsundries->groupBy('accountingcode') as $creditsundry)
                    @if($creditsundry->sum('credit') > 0)
                    <tr>
                        <td>{{$creditsundry->pluck('particular')->last()}}</td>
                        <td align='right'>{{number_format($creditsundry->sum('credit'),2)}}</td>
                    </tr>
                    @endif
                    @endforeach 
                    <tr>
                        <td>Total</td>
                        <td align='right'>{{number_format($totalsundries->sum('credit'),2)}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
     $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('cashreceipt')}}" + "/" + $('#trandate').val();
    });
       
  var wrap = $("#content").offset().top;
  $(window).scroll(function() { //when window is scrolled
    var currposs = wrap - $(window).scrollTop();
       if (currposs <= 20) {
          $('#wrap').addClass("fixed_PD");
        } else {
          $('#wrap').removeClass("fixed_PD");
        }
        
    });
</script>
@stop