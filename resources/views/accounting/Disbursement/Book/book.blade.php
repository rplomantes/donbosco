@extends('appaccounting')
@section('content')
<?php $total = $entries->where('isreverse',0);?>
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
                <th>Voucher No</th>
                <th width='15%'>Payee</th>
                <th>Voucher Amount</th>
                <th>Advances To Employees</th>
                <th>Cost of Sales</th>
                <th>Instructional  Materials</th>
                <th>Salaries / Allowances</th>
                <th>Personnel <br>Development</th>
                <th>Other Employee Benefit</th>
                <th>Office Supplies</th>
                <th>Travel Expenses</th>
                <th>Sundries Debit</th>
                <th>Sundies Credit</th>
                <th>Status</th>
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
                <td style="white-space:nowrap">{!!$entry->sundry_debit_account!!}</td>
                <td style="white-space:nowrap">{!!$entry->sundry_credit_account!!}</td>
                <td>
                    @if($entry->sundry_credit == 0)
                    OK
                    @else
                    Cancelled
                    @endif
                </td>
            </tr>
            @endforeach
            <tr align='right'>
                <td align='left' colspan="2">Total</td>
                <td>{{number_format($entries->sum('voucheramount'),2)}}</td>
                <td>{{number_format($entries->sum('advances_employee'),2)}}</td>
                <td>{{number_format($entries->sum('cost_of_goods'),2)}}</td>
                <td>{{number_format($entries->sum('instructional_materials'),2)}}</td>
                <td>{{number_format($entries->sum('salaries_allowances'),2)}}</td>
                <td>{{number_format($entries->sum('personnel_dev'),2)}}</td>
                <td>{{number_format($entries->sum('other_emp_benefit'),2)}}</td>
                <td>{{number_format($entries->sum('office_supplies'),2)}}</td>
                <td>{{number_format($entries->sum('travel_expenses'),2)}}</td>
                <td>{{number_format($entries->sum('sundry_debit'),2)}}</td>
                <td>{{number_format($entries->sum('sundry_credit'),2)}}</td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<?php 
$sundryCredit = $sundries->where('cr_db_indic',1)->filter(function($item){
    return !in_array(data_get($item, 'accountcode'), array(110012,110013,110014,110015,110016,110017,110018,110019,110020,110021));
});
$sundryDebit = $sundries->where('cr_db_indic',0)->filter(function($item){
    return !in_array(data_get($item, 'accountcode'), array(120103,440601,440701,580000,500000,500500,500300,120201,590200));
});
?>


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
                    @foreach($sundryDebit->groupBy('accountcode') as $debitsundry)
                    <tr>
                        <td>{{$debitsundry->pluck('accountname')->last()}}</td>
                        <td align='right'>{{number_format($debitsundry->sum('debit'),2)}}</td>
                    </tr>
                    @endforeach 
                    <tr>
                        <td>Total</td>
                        <td  align='right'>{{number_format($sundryDebit->sum('debit'),2)}}</td>
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
                    @foreach($sundryCredit->groupBy('accountcode') as $creditsundry)
                    <tr>
                        <td>{{$creditsundry->pluck('accountname')->last()}}</td>
                        <td align='right'>{{number_format($creditsundry->sum('credit'),2)}}</td>
                    </tr>
                    @endforeach 
                    <tr>
                        <td>Total</td>
                        <td align='right'>{{number_format($sundryCredit->sum('credit'),2)}}</td>
                    </tr>
                </table>
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