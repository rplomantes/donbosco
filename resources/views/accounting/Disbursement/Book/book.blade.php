@extends('appaccounting')
@section('content')
<?php 
$total = $entries->where('isreverse',0,false);
$entrysundies = \App\RptDisbursementBookSundries::with('RptDisbursementBook')->where('idno',\Auth::user()->idno)->get();

$totalsundries = $entrysundies->filter(function($item){
    if($item->RptDisbursementBook->isreverse == 0){
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
    <div class='col-md-12'><h3>CASH DISBURSEMENT BOOK</h3></div>
    <div class="col-md-12" id='content'>    
        <div class="col-md-2">
            <label>From</label>
            <input type="text" class="form-control" id="from" value="{{$from}}">
        </div>
        <div class="col-md-2">
            <label>To</label>
            <input type="text" class="form-control" id="trandate" value="{{$trandate}}">
        </div> 
        <div class="col-md-2">
            <label></label>
            <button id="processbtn" class="btn btn-primary form-control">View</button>
        </div>
        <div class='row col-md-offset-4 col-md-2' id='wrap'>
            <div class='row'>
                <a  href="{{url('printdisbursementpdf',array($from,$trandate))}}"  class='btn btn-danger col-md-12'>Print</a>
            </div>
            <br>
            <div class='row'>
                <a  href="{{url('dldisbursementbook',array($from,$trandate))}}" class='btn btn-success  col-md-12'>Download</a>
            </div>

        </div>
    </div>
    <div class='col-md-12'>
        <hr>
        
        <table class='table table-bordered table-striped'>
            <tr>
                <th rowspan="2">Voucher No</th>
                <th rowspan="2" width='15%'>Payee</th>
                <th rowspan="2">Voucher Amount</th>
                <th rowspan="2">Advances To Employees</th>
                <th rowspan="2">Cost of Sales</th>
                <th rowspan="2">Instructional  Materials</th>
                <th rowspan="2">Salaries / Allowances</th>
                <th rowspan="2">Personnel <br>Development</th>
                <th rowspan="2">Other Employee Benefit</th>
                <th rowspan="2">Office Supplies</th>
                <th rowspan="2">Travel Expenses</th>
                <th colspan="3" style="text-align:center">Sundries</th>
                <th rowspan="2">Status</th>
            </tr>
            <tr>
                <th>Sundries Debit</th>
                <th>Sundies Credit</th>
                <th>Account</th>
            </tr>
            @foreach($entries as $entry)
            <tr align='right'>
                <td align='left'>{{$entry->voucherno}}</td>
                <td align='left'>{{$entry->payee}}</td>
                <td>{{number_format($entry->voucheramount,2)}}</td>
                <td>{{number_format($entry->advances_employee,2)}}</td>
                <td>{{number_format($entry->cost_of_goods,2)}}</td>
                <td>{{number_format($entry->instructional_materials,2)}}</td>
                <td>{{number_format($entry->salaries_allowances,2)}}</td>
                <td>{{number_format($entry->personnel_dev,2)}}</td>
                <td>{{number_format($entry->other_emp_benefit,2)}}</td>
                <td>{{number_format($entry->office_supplies,2)}}</td>
                <td>{{number_format($entry->travel_expenses,2)}}</td>
                <td>
                    <table border="0" width='100%'>
                        @foreach($entrysundies->where('refno',$entry->refno,false) as $sundry_entry)
                        <tr><td align="right">@if($sundry_entry->debit != 0){{number_format($sundry_entry->debit,2)}}@else &nbsp; @endif</td></tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    <table border="0" width='100%'>
                        @foreach($entrysundies->where('refno',$entry->refno,false) as $sundry_entry)
                        <tr><td align="right">@if($sundry_entry->credit != 0){{number_format($sundry_entry->credit,2)}}@else &nbsp; @endif</td></tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    <table border="0" width='100%'>
                        @foreach($entrysundies->where('refno',$entry->refno,false) as $sundry_entry)
                        <tr><td align="left" style="white-space:nowrap">{{$sundry_entry->particular}}</td></tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    @if($entry->isreversed == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
            <tr align='right'>
                <td align='left' colspan="2">Total</td>
                <td>{{number_format($total->sum('voucheramount'),2)}}</td>
                <td>{{number_format($total->sum('advances_employee'),2)}}</td>
                <td>{{number_format($total->sum('cost_of_goods'),2)}}</td>
                <td>{{number_format($total->sum('instructional_materials'),2)}}</td>
                <td>{{number_format($total->sum('salaries_allowances'),2)}}</td>
                <td>{{number_format($total->sum('personnel_dev'),2)}}</td>
                <td>{{number_format($total->sum('other_emp_benefit'),2)}}</td>
                <td>{{number_format($total->sum('office_supplies'),2)}}</td>
                <td>{{number_format($total->sum('travel_expenses'),2)}}</td>
                <td>{{number_format($total->sum('sundry_debit'),2)}}</td>
                <td>{{number_format($total->sum('sundry_credit'),2)}}</td>
                <td colspan="2"></td>
            </tr>
        </table>
    </div>
</div>

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
                <table class='table table-bordered table-responsive'>
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
    
    <div class="col-md-2">
        <div class="panel panel-danger">
            <div class="panel-body">
                <div>
                    <a  href="{{url('printdisbursementsundriespdf',array($from,$trandate))}}"  class='btn btn-danger col-md-12'>Print Sundrires</a>
                </div>                
            </div>
        </div>
    </div>
</div>
<script>
  var wrap = $("#content").offset().top;
  $(window).scroll(function() { //when window is scrolled
    var currposs = wrap - $(window).scrollTop();
       if (currposs <= 20) {
          $('#wrap').addClass("fixed_PD");
        } else {
          $('#wrap').removeClass("fixed_PD");
        }
        
    });
    
     $("#processbtn").click(function(){
        //alert("hello")
        document.location = "{{url('disbursementbook')}}" + "/" + $("#from").val()+ "/" + $("#trandate").val();
    })
</script>
@stop