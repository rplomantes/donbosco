<?php
use App\Http\Controllers\Cashier\Payment\MainPayment;
?>
<style>
    .divide{
        text-align: right;
    }
</style>


<div class="col-md-12">
<p>Due Today</p>
<div class="btn btn-danger form-control"  style="font-size:20px;height:50px;font-weight:bold">
    {{number_format($accountdue,2)}}
</div>
<p>Due Amount</p>
<form method="POST" action="{{route('processmainpayment',$idno)}}">
    
    <table class="table table-bordered" >
        <tr>
            <td>Main Account</td>
            <td><input name='main' class="form-control credit divide" value="{{number_format($maindue,2)}}"></td>
        </tr>
        @if($prevBalance > 0)
        <tr>
            <td >Prev. Balance</td>
            <td><input name='prev' class="form-control credit divide" value="{{number_format($prevBalance,2)}}"></td>
        </tr>
        @endif

        @foreach($otheraccounts as $otheraccount)
            @if(round(MainPayment::singleaccountdue($otheraccount),2) > 0)
            <tr>
                <td>{{$otheraccount->description}}</td>
                <td><input name='other[{{$otheraccount->id}}]' class="form-control credit other divide" value="{{number_format(MainPayment::singleaccountdue($otheraccount),2)}}">
                </td>
            </tr>
            @endif
        @endforeach
        <tr>
            <tr>
                <td>Amount To Be Paid</td>
                <td align="right">
                    <input type="text" name="totalamount" id ="totalamount" style="color: red; font-weight: bold; text-align: right" class="form-control divide" value="{{ number_format($accountdue,2,'.','')}}" readonly>
                </td>
            </tr>
        </tr>
        <tr>
            <td colspan='2' style='background-color:#c1bfbf'>
                <b>Check Payment</b>
                <table>
                    <tr>
                        <td colspan='3'>
                            <div class="checkbox">
                              <label><input type="checkbox" name="iscbc" id="iscbc" value="cbc">China Bank Check</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                              <label for="bank_branch">Bank Branch</label>
                              <input type="text" class="form-control"  name="bank_branch"  id="bank_branch">
                            </div>
                        </td>
                        <td width='2%'>&nbsp;</td>
                        <td>
                            <div class="form-group">
                              <label for="check_number">Check No.</label>
                              <input type="text" class="form-control"  name="check_number"  id="check_number">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr>
                            <label for="receivecheck">Check No.</label>
                            <input class='form-control divide debit' type="text" id="receivecheck" name="receivecheck"  id="receivecheck"  placeholder="0.00" style="text-align: right">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"><div style="color:red;font-weight: bold" id="cashdiff"></div></td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="fape">FAPE</label>
                <input class='form-control divide debit' type="text" name="fape"  id="fape"  placeholder="0.00" style="text-align: right">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="receivecash">Cash Amount Rendered:</label>
                <input class='form-control divide debit' type="text" name="receivecash"  id="receivecash"  placeholder="0.00" style="text-align: right">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label>Change:</label>
                <input style ="text-align: right" type="text" value="0" name="change" id="change" readonly class="form form-control divide">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="col-md-6"><input type="radio" name="depositto" value="China Bank" checked="checked"> China Bank</div>
                <div class="col-md-6"><input type="radio" name="depositto" value="China Bank 2"> China Bank 2</div>

                <div class="col-md-6"><input type="radio" name="depositto" value="BPI 1"> BPI 1</div>
                <div class="col-md-6"><input type="radio" name="depositto" value="BPI 2"> BPI 2</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label>Particular :</label>
                <input type="text" name="remarks" id="remarks" class="form-control" >
            </td>
        </tr>
    </table>
</form>
<script src="{{asset('/js/nephilajs/cashier2.js')}}"></script>